<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;

/**
 * @hideFromAPIDocumentation
 */
class NotificationLogController extends Controller
{
    public function show($id)
    {
        return '';

        return NotificationLog::findOrFail($id);
    }
}
