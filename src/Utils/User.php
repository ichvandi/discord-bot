<?php

namespace Utils;

class User {

	private const UNKNOWN_USER = 'Wild Boar';
    private static $users = null;

	private static function getUsers() {
		if (self::$users === null) {
			$json = file_get_contents('users.json');
			$data = json_decode($json, true);
			self::$users = $data['users'];
		}

		return self::$users;
	}
	
	public static function getDiscordId($username) {
		$users = self::getUsers();
		$user = array_values(array_filter($users, function ($item) use ($username) {
			return $item['github_username'] === $username;
		}));
		return !empty($user) ? $user[0]['discord_id'] : self::UNKNOWN_USER;
	}
}
