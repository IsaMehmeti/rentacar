<?php

namespace App\Services;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class TwilioService
{
    protected Client $client;

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->client = new Client($sid, $token);
    }

    public function sendMessage($to, $message): MessageInstance
    {
        $from = env('TWILIO_PHONE_FROM');

        return $this->client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $message,
            ]
        );
    }

}
