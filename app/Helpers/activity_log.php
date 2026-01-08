<?php

use App\Models\ActivityLog;

if (! function_exists('activity_log')) {
    function activity_log(string $action, $model, ?string $description = null): void
    {
        if (! auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $model->id,
            'description' => $description,
        ]);
    }
}
