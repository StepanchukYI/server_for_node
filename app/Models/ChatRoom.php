<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{

	public static function createRoom( $user_ids )
	{
		$room = self::create( [
			'user_ids' => $user_ids
		] );

		return $room->id;
	}
}
