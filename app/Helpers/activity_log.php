<?php

use App\Models\ActivityLog;

if (! function_exists('activity_log')) {
    function activity_log($action, $model = null, $modelId = null, $desc = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $desc,
        ]);
    }
}
