<?php

require_once __DIR__ .'/vendor/autoload.php';

use Discord\PRAssigned;
use Discord\PRClosed;
use Discord\PRCommit;
use Discord\PRMerged;
use Discord\PROpen;
use Discord\PRReviewed;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$payload = json_decode(file_get_contents('php://input'), true);

switch ($payload['action']) {
    case 'opened':
        $obj = new PROpen($payload);
        $obj->sendDiscordWebhooks();
        break;

    case 'review_requested':
        $obj = new PRAssigned($payload);
        $obj->sendDiscordWebhooks();
        break;

    case 'submitted':
        if ($payload['review']['state'] != 'approved') {
            $obj = new PRReviewed($payload);
            $obj->sendDiscordWebhooks();
        }
        break;

    case 'synchronize':
        $obj = new PRCommit($payload);
        $obj->sendDiscordWebhooks();
        break;

    case 'closed':
        if ($payload['pull_request']['merged_at'] != null) {
            $obj = new PRMerged($payload);
            $obj->sendDiscordWebhooks();
        } else {
            $obj = new PRClosed($payload);
            $obj->sendDiscordWebhooks();
        }
        break;
}
