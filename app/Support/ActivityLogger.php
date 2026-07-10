<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class ActivityLogger
{
    public static function log(string $action, ?string $description = null, array $context = [], ?Authenticatable $user = null): ?ActivityLog
    {
        if (! Schema::hasTable('activity_logs') || ! Schema::hasColumn('activity_logs', 'action')) {
            return null;
        }

        $actor = $user ?? auth()->user();

        $attributes = [
            'action' => $action,
        ];

        if (Schema::hasColumn('activity_logs', 'shop_id')) {
            $attributes['shop_id'] = $actor ? $actor->shop_id : CurrentShop::creationShopId();
        }

        if (Schema::hasColumn('activity_logs', 'user_id')) {
            $attributes['user_id'] = $actor ? $actor->getAuthIdentifier() : null;
        }

        if (Schema::hasColumn('activity_logs', 'description')) {
            $attributes['description'] = $description;
        }

        if (Schema::hasColumn('activity_logs', 'context')) {
            $attributes['context'] = $context !== [] ? $context : null;
        }

        try {
            return ActivityLog::create($attributes);
        } catch (QueryException $exception) {
            return null;
        }
    }
}
