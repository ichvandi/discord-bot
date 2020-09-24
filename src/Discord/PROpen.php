<?php

namespace Discord;

use Discord\BaseClass;

class PROpen extends BaseClass {
    public function __construct($payload) {
        parent::__construct($payload);
    }

    public function prepareBody() {
        $pullRequest = $this->payload['pull_request'];
        $reviewers = $pullRequest['requested_reviewers'];
        $repository = $this->payload['repository'];
        $hexColor = 'ffbd09';

        $json = '
		{
			"content": "There is a new PR on [$repo_name]($repo_url) :man_technologist:",
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
				  "text": "Opened At: $timestamp",
				  "icon_url": "https://www.iconsdb.com/icons/preview/white/clock-7-xxl.png"
				}
			  }
			]
		}
        ';

        $vars = [
            '$repo_name' => $repository['name'],
            '$repo_url' => $repository['html_url'],
            '$pr_no' => $pullRequest['number'],
            '$pr_title' => $pullRequest['title'],
            '$pr_desc' => $pullRequest['body'],
            '$pr_url' => $pullRequest['html_url'],
            '$color' => hexdec($hexColor),
            '$author' => $pullRequest['user']['login'],
            '$author_url' => $pullRequest['user']['html_url'],
            '$author_icon' => $pullRequest['user']['avatar_url'],
            '$timestamp' => $this->convertDateTime($this->parseTimestamp($pullRequest['created_at'])),
        ];

        $body = json_decode(strtr($json, $vars), true);

        for ($i = 0; $i < count($reviewers); $i++) {
            $body['embeds'][0]['fields'][] = [
                'name' => "Reviewer " . ($i + 1),
                'value' => $this->parseGithubUsernameToDiscordId($reviewers[$i]['login']),
                'inline' => true,
            ];
        }

        return json_encode($body);
    }
}
