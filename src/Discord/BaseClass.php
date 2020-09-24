<?php

namespace Discord;

use DateTime;
use DateTimeZone;
use Config\User;

abstract class BaseClass {
    protected $payload;

    public function __construct($payload) {
        $this->payload = $payload;
    }

    abstract public function prepareBody();

    public function sendDiscordWebhooks() {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => API_URL,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->prepareBody(),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return !$err;
    }

    protected function convertDateTime($date, $format = 'd-m-Y H:i:s') {
        $tz1 = 'UTC';
        $tz2 = 'GMT+7';

        $d = new DateTime($date, new DateTimeZone($tz1));
        $d->setTimeZone(new DateTimeZone($tz2));

        return $d->format($format);
    }

    protected function parseGithubUsernameToDiscordId($username) {
        return User::getDiscordId($username);
    }

    protected function parseTimestamp($timestamp) {
        $time = str_replace('Z', '', str_replace('T', ' ', $timestamp));
        $newDate = DateTime::createFromFormat('Y-m-d H:i:s', $time);

        return $newDate->format('d-m-Y H:i:s');
    }
}
