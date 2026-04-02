<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;

class ActivityLogger
{
    public static function log(string $action, ?string $description = null, array $context = [], ?Authenticatable $user = null): ActivityLog
    {
        $actor = $user ?? auth()->user();

        return ActivityLog::create([
            'user_id' => $actor?->getAuthIdentifier(),
            'action' => $action,
            'description' => $description,
            'context' => $context !== [] ? $context : null,
        ]);
    }
}
