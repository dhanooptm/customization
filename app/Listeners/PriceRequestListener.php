<?php

namespace App\Listeners;

use App\Events\ContactSupplierEvent;
use App\Events\PriceRequestEvent;
use App\Traits\EmailTemplateTrait;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PriceRequestListener
{
    use PushNotificationTrait, EmailTemplateTrait;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PriceRequestEvent $event): void
    {
        if ($event->email) {
            $this->sendMail($event);
        }
        if ($event->notification) {
            $this->sendNotification($event);
        }

    }

    private function sendMail(PriceRequestEvent $event): void
    {
        $email = $event->email;
        $data = $event->data;
        try {
            $this->sendingMail(sendMailTo: $email, userType: $data['userType'], templateName: $data['templateName'], data: $data);
        } catch (\Exception $exception) {

        }
    }

    private function sendNotification(PriceRequestEvent $event): void
    {
        $key = $event->notification->key;
        $type = $event->notification->type;
        $order = $event->notification->order;
        $this->sendOrderNotification(key: $key, type: $type, order: $order);
    }
}
