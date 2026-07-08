<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        return view('superadmin.backups.index', [
            'tables' => $this->tables(),
        ]);
    }

    public function download(): StreamedResponse
    {
        $payload = [
            'created_at' => now()->toIso8601String(),
            'database' => config('database.connections.mysql.database'),
            'tables' => collect($this->tables())->mapWithKeys(fn (string $table) => [
                $table => DB::table($table)->get()->map(fn ($row) => (array) $row)->all(),
            ])->all(),
        ];

        $filename = 'tailor-system-backup-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function restore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'backup_file' => ['required', 'file', 'mimes:json,txt', 'max:51200'],
        ]);

        $payload = json_decode(file_get_contents($validated['backup_file']->getRealPath()), true);

        if (! is_array($payload) || ! isset($payload['tables']) || ! is_array($payload['tables'])) {
            return back()->with('error', 'The uploaded backup file is not valid.');
        }

        $existingTables = collect($this->tables());

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($existingTables as $table) {
                DB::table($table)->delete();
            }

            foreach ($payload['tables'] as $table => $rows) {
                if (! $existingTables->contains($table) || ! is_array($rows) || $rows === []) {
                    continue;
                }

                collect($rows)->chunk(250)->each(function ($chunk) use ($table) {
                    DB::table($table)->insert($chunk->map(fn ($row) => (array) $row)->all());
                });
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        return back()->with('success', 'Backup restored successfully.');
    }

    protected function tables(): array
    {
        $database = config('database.connections.mysql.database');
        $column = 'Tables_in_'.$database;

        return collect(DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
            ->map(fn ($row) => (array) $row)
            ->map(fn (array $row) => $row[$column] ?? reset($row))
            ->filter()
            ->values()
            ->all();
    }
}
