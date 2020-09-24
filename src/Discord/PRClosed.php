<?php

namespace Discord;

use Discord\BaseClass;

class PRClosed extends BaseClass {
    public function __construct($payload) {
        parent::__construct($payload);
    }

    public function prepareBody() {
        $pullRequest = $this->payload['pull_request'];
        $sender = $this->payload['sender'];
        $hexColor = 'ffbd09';

        $json = '
        {
            "content": "Hey, $user your PR has been closed! :cry:",
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
                  "fields": [
                    {
                      "name": "Closed by",
                      "value": "$closed_by",
                      "inline": true
                    }
                  ],
                  "footer": {
                    "text": "Closed At: $timestamp",
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
            '$closed_by' => $this->parseGithubUsernameToDiscordId($sender['login']),
            '$timestamp' => $this->convertDateTime($this->parseTimestamp($pullRequest['closed_at'])),
        ];

        $body = json_decode(strtr($json, $vars), true);

        return json_encode($body);
    }
}
