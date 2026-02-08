<?php

use App\Models\ActivityLog;

function activity_log($action, $model, $description = null)
{
    ActivityLog::create([
        'user_id'    => auth()->id(),
        'action'     => $action,
        'model'      => get_class($model),
        'model_id'   => $model->id,
        'description' => $description,
        'ip_address' => request()->ip(),
    ]);
}
