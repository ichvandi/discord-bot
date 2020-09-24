<?php

namespace Discord;

use Discord\BaseClass;

class PRCommit extends BaseClass {
    public function __construct($payload) {
        parent::__construct($payload);
    }

    public function prepareBody() {
        $pullRequest = $this->payload['pull_request'];
        $reviewers = $pullRequest['requested_reviewers'];
        $hexColor = 'ffbd09';

        $json = '
        {
            "content": "Hey, $users there is a new update in this PR :star_struck:",
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
                    "text": "Updated At: $timestamp",
                    "icon_url": "https://www.iconsdb.com/icons/preview/white/clock-7-xxl.png"
                  }
                }
              ]
          }
        ';

        $vars = [
            '$pr_no' => $pullRequest['number'],
            '$pr_title' => $pullRequest['title'],
            '$pr_desc' => $pullRequest['body'],
            '$pr_url' => $pullRequest['html_url'],
            '$color' => hexdec($hexColor),
            '$author' => $pullRequest['user']['login'],
            '$author_url' => $pullRequest['user']['html_url'],
            '$author_icon' => $pullRequest['user']['avatar_url'],
            '$timestamp' => $this->convertDateTime($this->parseTimestamp($pullRequest['updated_at'])),
            '$users' => implode(" ", array_map(function ($item) {
                return $this->parseGithubUsernameToDiscordId($item['login']);
            }, $reviewers)),
        ];

        $body = json_decode(strtr($json, $vars), true);

        return json_encode($body);
    }
}
