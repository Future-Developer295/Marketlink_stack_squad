<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * ActivityLogger
 *
 * Tiny helper used across the DashboardController to record who did what
 * and when, for the Activity / Audit Log screen. Every call captures a
 * snapshot of the acting user's name and role so the trail stays readable
 * even if that user account is later edited or removed.
 */
class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        int|string|null $subjectId = null
    ): ?ActivityLog {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => $user->role ?? optional($user->roles->first())->name,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
        ]);
    }
}
