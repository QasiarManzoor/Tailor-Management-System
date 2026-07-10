<?php

namespace App\Http\Controllers;

use App\Support\CurrentShop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataTransferController extends Controller
{
    private const VERSION = 1;

    private const TABLES = [
        'customers',
        'measurements',
        'workers',
        'orders',
        'payments',
        'order_status_histories',
        'order_checklist_items',
        'order_attachments',
        'worker_payments',
        'inventory_items',
        'inventory_movements',
        'cashbook_entries',
    ];

    public function index(): View
    {
        return view('data-transfer.index', [
            'tables' => self::TABLES,
            'shop' => CurrentShop::contextShop(),
        ]);
    }

    public function export(Request $request): StreamedResponse|RedirectResponse
    {
        $shopId = $this->shopId($request);

        if (! $shopId) {
            return back()->with('error', 'Please select or assign a shop before exporting business data.');
        }

        $payload = $this->exportPayload($shopId);
        $filename = 'tailor-shop-data-'.$shopId.'-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $shopId = $this->shopId($request);

        if (! $shopId) {
            return back()->with('error', 'Please select or assign a shop before importing business data.');
        }

        $validated = $request->validate([
            'import_file' => ['required', 'file', 'mimes:json,txt', 'max:51200'],
            'replace_existing' => ['nullable', 'boolean'],
        ]);

        $payload = json_decode(file_get_contents($validated['import_file']->getRealPath()), true);

        if (! $this->isValidPayload($payload)) {
            return back()->with('error', 'The uploaded import file is not a valid Tailor Shop data export.');
        }

        $replaceExisting = $request->boolean('replace_existing');
        $summary = DB::transaction(function () use ($payload, $shopId, $request, $replaceExisting) {
            if ($replaceExisting) {
                $this->deleteShopData($shopId);
            }

            return $this->importPayload($payload, $shopId, $request->user()?->id);
        }, 3);

        return back()->with('success', sprintf(
            'Data imported successfully: %s customers, %s orders, %s measurements, and related records.',
            $summary['customers'] ?? 0,
            $summary['orders'] ?? 0,
            $summary['measurements'] ?? 0
        ));
    }

    private function exportPayload(int $shopId): array
    {
        $customerIds = DB::table('customers')->where('shop_id', $shopId)->pluck('id');
        $measurementIds = DB::table('measurements')->where('shop_id', $shopId)->pluck('id');
        $workerIds = DB::table('workers')->where('shop_id', $shopId)->pluck('id');
        $orderIds = DB::table('orders')->where('shop_id', $shopId)->pluck('id');
        $inventoryItemIds = DB::table('inventory_items')->where('shop_id', $shopId)->pluck('id');

        $tables = [
            'customers' => $this->rows('customers', fn ($query) => $query->where('shop_id', $shopId)),
            'measurements' => $this->rows('measurements', fn ($query) => $query->whereIn('id', $measurementIds)),
            'workers' => $this->rows('workers', fn ($query) => $query->whereIn('id', $workerIds)),
            'orders' => $this->rows('orders', fn ($query) => $query->whereIn('id', $orderIds)),
            'payments' => $this->rows('payments', fn ($query) => $query->where('shop_id', $shopId)),
            'order_status_histories' => $this->rows('order_status_histories', fn ($query) => $query->whereIn('order_id', $orderIds)),
            'order_checklist_items' => $this->rows('order_checklist_items', fn ($query) => $query->whereIn('order_id', $orderIds)),
            'order_attachments' => $this->rows('order_attachments', fn ($query) => $query->whereIn('order_id', $orderIds)),
            'worker_payments' => $this->rows('worker_payments', fn ($query) => $query->where('shop_id', $shopId)),
            'inventory_items' => $this->rows('inventory_items', fn ($query) => $query->whereIn('id', $inventoryItemIds)),
            'inventory_movements' => $this->rows('inventory_movements', fn ($query) => $query->where('shop_id', $shopId)),
            'cashbook_entries' => $this->rows('cashbook_entries', fn ($query) => $query->where('shop_id', $shopId)),
        ];

        return [
            'type' => 'tailor_shop_business_data',
            'version' => self::VERSION,
            'created_at' => now()->toIso8601String(),
            'shop_id' => $shopId,
            'tables' => $tables,
            'files' => [
                'order_attachments' => $this->attachmentFiles($tables['order_attachments']),
            ],
        ];
    }

    private function importPayload(array $payload, int $shopId, ?int $userId): array
    {
        $tables = $payload['tables'];
        $files = $payload['files']['order_attachments'] ?? [];
        $maps = [];
        $summary = [];

        $maps['customers'] = $this->insertRows('customers', $tables['customers'] ?? [], function (array $row) use ($shopId) {
            $row['shop_id'] = $shopId;
            $row['customer_no'] = $this->uniqueValue('customers', 'customer_no', $row['customer_no'] ?? null);

            return $row;
        });

        $maps['workers'] = $this->insertRows('workers', $tables['workers'] ?? [], function (array $row) use ($shopId) {
            $row['shop_id'] = $shopId;

            return $row;
        });

        $maps['measurements'] = $this->insertRows('measurements', $tables['measurements'] ?? [], function (array $row) use ($shopId, &$maps) {
            $row['shop_id'] = $shopId;
            $row['customer_id'] = $maps['customers'][$row['customer_id'] ?? 0] ?? null;

            return $row['customer_id'] ? $row : null;
        });

        $maps['orders'] = $this->insertRows('orders', $tables['orders'] ?? [], function (array $row) use ($shopId, &$maps) {
            $row['shop_id'] = $shopId;
            $row['order_no'] = $this->uniqueValue('orders', 'order_no', $row['order_no'] ?? null);
            $row['customer_id'] = $maps['customers'][$row['customer_id'] ?? 0] ?? null;
            $row['measurement_id'] = $maps['measurements'][$row['measurement_id'] ?? 0] ?? null;
            $row['worker_id'] = $maps['workers'][$row['worker_id'] ?? 0] ?? null;

            return $row['customer_id'] ? $row : null;
        });

        $maps['inventory_items'] = $this->insertRows('inventory_items', $tables['inventory_items'] ?? [], function (array $row) use ($shopId) {
            $row['shop_id'] = $shopId;

            return $row;
        });

        $this->insertRows('payments', $tables['payments'] ?? [], function (array $row) use ($shopId, &$maps) {
            $row['shop_id'] = $shopId;
            $row['order_id'] = $maps['orders'][$row['order_id'] ?? 0] ?? null;

            return $row['order_id'] ? $row : null;
        });

        $this->insertRows('order_status_histories', $tables['order_status_histories'] ?? [], function (array $row) use (&$maps, $userId) {
            $row['order_id'] = $maps['orders'][$row['order_id'] ?? 0] ?? null;
            $row['changed_by'] = $userId;

            return $row['order_id'] ? $row : null;
        });

        $this->insertRows('order_checklist_items', $tables['order_checklist_items'] ?? [], function (array $row) use (&$maps) {
            $row['order_id'] = $maps['orders'][$row['order_id'] ?? 0] ?? null;

            return $row['order_id'] ? $row : null;
        });

        $this->insertRows('order_attachments', $tables['order_attachments'] ?? [], function (array $row) use (&$maps, $userId, $files) {
            $oldPath = $row['path'] ?? null;
            $row['order_id'] = $maps['orders'][$row['order_id'] ?? 0] ?? null;
            $row['uploaded_by'] = $userId;

            if (! $row['order_id']) {
                return null;
            }

            if ($oldPath && isset($files[$oldPath]['content'])) {
                $row['path'] = $this->writeAttachmentFile($row['order_id'], $oldPath, $files[$oldPath]['content']);
            }

            return $row;
        });

        $this->insertRows('worker_payments', $tables['worker_payments'] ?? [], function (array $row) use ($shopId, &$maps) {
            $row['shop_id'] = $shopId;
            $row['worker_id'] = $maps['workers'][$row['worker_id'] ?? 0] ?? null;
            $row['order_id'] = $maps['orders'][$row['order_id'] ?? 0] ?? null;

            return $row['worker_id'] ? $row : null;
        });

        $this->insertRows('inventory_movements', $tables['inventory_movements'] ?? [], function (array $row) use ($shopId, &$maps) {
            $row['shop_id'] = $shopId;
            $row['inventory_item_id'] = $maps['inventory_items'][$row['inventory_item_id'] ?? 0] ?? null;

            return $row['inventory_item_id'] ? $row : null;
        });

        $this->insertRows('cashbook_entries', $tables['cashbook_entries'] ?? [], function (array $row) use ($shopId) {
            $row['shop_id'] = $shopId;

            return $row;
        });

        foreach (self::TABLES as $table) {
            $summary[$table] = count($tables[$table] ?? []);
        }

        return $summary;
    }

    private function insertRows(string $table, array $rows, callable $transform): array
    {
        $map = [];

        foreach ($rows as $row) {
            $row = (array) $row;
            $oldId = $row['id'] ?? null;
            unset($row['id']);

            $row = $transform($row);

            if (! is_array($row)) {
                continue;
            }

            $newId = DB::table($table)->insertGetId($row);

            if ($oldId) {
                $map[$oldId] = $newId;
            }
        }

        return $map;
    }

    private function rows(string $table, callable $scope): array
    {
        $query = DB::table($table)->orderBy('id');
        $scope($query);

        return $query->get()->map(fn ($row) => (array) $row)->all();
    }

    private function uniqueValue(string $table, string $column, ?string $value): ?string
    {
        if (blank($value) || ! DB::table($table)->where($column, $value)->exists()) {
            return $value;
        }

        $base = $value.'-IMP';
        $sequence = 1;

        do {
            $candidate = $base.$sequence;
            $sequence++;
        } while (DB::table($table)->where($column, $candidate)->exists());

        return $candidate;
    }

    private function attachmentFiles(array $attachments): array
    {
        return collect($attachments)
            ->mapWithKeys(function (array $attachment) {
                $path = $attachment['path'] ?? null;

                if (! $path || ! Storage::disk('public')->exists($path)) {
                    return [];
                }

                return [$path => [
                    'content' => base64_encode(Storage::disk('public')->get($path)),
                ]];
            })
            ->all();
    }

    private function writeAttachmentFile(int $orderId, string $oldPath, string $content): string
    {
        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $filename = 'imported-'.uniqid('', true).($extension ? '.'.$extension : '');
        $path = 'order-attachments/'.$orderId.'/'.$filename;

        Storage::disk('public')->put($path, base64_decode($content, true) ?: '');

        return $path;
    }

    private function deleteShopData(int $shopId): void
    {
        $orderIds = DB::table('orders')->where('shop_id', $shopId)->pluck('id');
        $inventoryItemIds = DB::table('inventory_items')->where('shop_id', $shopId)->pluck('id');

        DB::table('order_attachments')->whereIn('order_id', $orderIds)->delete();
        DB::table('order_checklist_items')->whereIn('order_id', $orderIds)->delete();
        DB::table('order_status_histories')->whereIn('order_id', $orderIds)->delete();
        DB::table('payments')->where('shop_id', $shopId)->delete();
        DB::table('worker_payments')->where('shop_id', $shopId)->delete();
        DB::table('orders')->where('shop_id', $shopId)->delete();
        DB::table('measurements')->where('shop_id', $shopId)->delete();
        DB::table('customers')->where('shop_id', $shopId)->delete();
        DB::table('inventory_movements')->whereIn('inventory_item_id', $inventoryItemIds)->delete();
        DB::table('inventory_items')->where('shop_id', $shopId)->delete();
        DB::table('cashbook_entries')->where('shop_id', $shopId)->delete();
        DB::table('workers')->where('shop_id', $shopId)->delete();
    }

    private function isValidPayload(mixed $payload): bool
    {
        return is_array($payload)
            && ($payload['type'] ?? null) === 'tailor_shop_business_data'
            && isset($payload['tables'])
            && is_array($payload['tables']);
    }

    private function shopId(Request $request): ?int
    {
        return CurrentShop::scopeShopId() ?: $request->user()?->shop_id;
    }
}
