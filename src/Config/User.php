<?php 

namespace Config;

class User {
	
	private const USERS = [
		'Vandoct' => '<@!337996268628344834>',
		'ichsanachmad' => '<@!207678782339940352>',
		'mdedealf' => '<@!352657838775992321>',
		'calvinfm' => '<@!364613800332230662>',
		'adityadees' => '<@!336864033720369163>',
		'malianzikri' => '<@!588741882868269243>',
		'cindy2400' => '<@!652471517220831282>',
		'zorayaw' => '<@!513220404333248527>',
		'dhiyadc' => '<@!513349043813089280>',
		'IhtiarAlfath' => '<@!499230135606706196>',
		'aryapradata' => '<@!340452495035727876>',
		'zarszz' => '<@!405275428123836428>',
		'fadilahonespot' => '<@!715073388024037468>',
		'Ammarfar' => '<@!632879062435758080>',
		'vroksnak' => '<@!295864406896672768>',
	];

	private const UNKNOWN_USER = 'Wild Boar';

	public static function getDiscordId($username) {
		return array_key_exists($username, self::USERS) ? self::USERS[$username] : self::UNKNOWN_USER;
	}

}

?>