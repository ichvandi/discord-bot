<?php

namespace Discord;

use Discord\BaseClass;

class PRReviewed extends BaseClass {
    public function __construct($payload) {
        parent::__construct($payload);
    }

    public function prepareBody() {
        $pullRequest = $this->payload['pull_request'];
        $review = $this->payload['review'];
        $hexColor = 'ffbd09';

        $json = '
        {
            "content": "Hey, $user there is a review on your PR :face_with_hand_over_mouth:",
            "embeds": [
                {
                  "title": "PR #$pr_no - $pr_title",
                  "description": "$pr_desc",
                  "url": "$pr_url",
                  "color": "$color",
                  "author": {
                    "name": "$author",
                    "url": "$author_url",
                    "icon_url": "$author_icon"
                  },
                  "footer": {
                    "text": "Reviewed At: $timestamp",
                    "icon_url": "https://www.iconsdb.com/icons/preview/white/clock-7-xxl.png"
                  }
                }
            ]
          }
        ';

        $vars = [
            '$user' => $this->parseGithubUsernameToDiscordId($pullRequest['user']['login']),
            '$pr_no' => $pullRequest['number'],
            '$pr_title' => $pullRequest['title'],
            '$pr_desc' => $pullRequest['body'],
            '$pr_url' => $pullRequest['html_url'],
            '$color' => hexdec($hexColor),
            '$author' => $pullRequest['user']['login'],
            '$author_url' => $pullRequest['user']['html_url'],
            '$author_icon' => $pullRequest['user']['avatar_url'],
            '$timestamp' => $this->convertDateTime($this->parseTimestamp($pullRequest['updated_at'])),
        ];

        $body = json_decode(strtr($json, $vars), true);

        $body['embeds'][0]['fields'][0] = [
            'name' => "Commentator",
            'value' => $this->parseGithubUsernameToDiscordId($review['user']['login']),
            'inline' => true,
        ];

        $body['embeds'][0]['fields'][1] = [
            'name' => "Message",
            'value' => empty(trim($review['body'])) ? "No message" : $review['body'],
            'inline' => true,
        ];

        return json_encode($body);
    }
}
