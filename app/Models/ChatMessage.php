<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChatMessage extends Model
{
	/**
	 * @param $request
	 */
	public static function createMessages( $request )
	{
		DB::beginTransaction();
		foreach ( $request as $item )
		{
			$chatMSG = new ChatMessage();

			$chatMSG->from_user_id = $item->user;
			$chatMSG->message = $item->text;
			$chatMSG->date = $item->time->date;
			$chatMSG->time = $item->time->time;

			$chatMSG->save();
		}

		DB::commit();

		return $chatMSG->id;
	}
}
