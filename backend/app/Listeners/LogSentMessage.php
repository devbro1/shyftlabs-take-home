<?php

namespace App\Listeners;

use App\Models\NotificationLog;

class LogSentMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     */
    public function handle($event)
    {
        $notification_log = new NotificationLog();
        $notification_log->sender = $event->message->getSender() ?? '';
        $notification_log->subject = $event->message->getSubject();
        $notification_log->to = $event->message->getTo()[0]->getAddress();
        $notification_log->cc = '???'; // implode(', ', array_keys($event->message->getCc()[0]->getAddress() ?? []));
        $notification_log->bcc = '???'; // implode(', ', array_keys($event->message->getBcc()[0]->getAddress() ?? []));
        $notification_log->body = $event->message->getBody()->getParts()[0]->getBody();
        $notification_log->save();
    }
}
