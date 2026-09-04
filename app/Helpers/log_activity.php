<?php

use App\Models\ActivityLog;

function logActivity($action, $description, $employee_id = null)
{
    ActivityLog::create([
        'user_id' => auth()->id(),
        'employee_id'  => $employee_id,
        'action'       => $action,
        'description'  => $description,
        'is_read'      => 0,
    ]);
}
