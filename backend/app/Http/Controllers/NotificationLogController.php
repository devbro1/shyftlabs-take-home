<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;

class NotificationLogController extends Controller
{
    public function show($id)
    {
        return NotificationLog::findOrFail($id);
    }
}
