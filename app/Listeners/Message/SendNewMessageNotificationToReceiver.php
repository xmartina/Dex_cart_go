<?php

namespace App\Listeners\Message;

use App\Models\System;
use App\Models\Message;
use App\Services\FCMService;
use App\Events\Message\NewMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Message\NewMessage as NewMessageNotification;

class SendNewMessageNotificationToReceiver implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewMessage  $event
     * @return void
     */
    public function handle(NewMessage $event)
    {
        if (!config('system_settings')) {
            setSystemConfig($event->message->shop_id);
        }

        $customer_token = optional($event->message->customer)->fcm_token;

        if (!is_null($customer_token)) {
            FCMService::send($customer_token, [
                'title' => trans('notifications.new_message.subject', ['subject' => $event->message->subject]),
                'body' => trans('notifications.new_message.message', ['message' => $event->message->message]),
            ]);
        }

        if ($event->message->label == Message::LABEL_INBOX) {
            if ($event->message->shop_id) {
                if (config('shop_settings.notify_new_message')) {
                    $event->message->shop->notify(new NewMessageNotification($event->message, $event->message->shop->name));
                }
            } elseif (config('system_settings.notify_new_message')) {
                $system = System::orderBy('id', 'asc')->first();
                $system->notify(new NewMessageNotification($event->message, $system->superAdmin->getName()));
            }
        } elseif ($event->message->label == Message::LABEL_SENT) {
            if ($event->message->order_id && $event->message->email) {
                Notification::route('mail', $event->message->email)
                    // ->route('nexmo', '5555555555')
                    ->notify(new NewMessageNotification($event->message, trans('app.guest_customer'), true));
            } else {
                $event->message->customer->notify(new NewMessageNotification($event->message, $event->message->customer->getName()));
            }
        }
    }
}
