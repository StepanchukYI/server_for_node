<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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
			$chatMSG->from_user_id = $item['user'];
			$chatMSG->message = $item['text'];
			$chatMSG->date = $item['time']['date'];
			$chatMSG->time = $item['time']['time'];

			$chatMSG->save();
		}

		DB::commit();

		return $chatMSG->id;
	}


	public static function messages($messageId){
		/** @var LengthAwarePaginator $items */
		$items = self::where('id', '>', $messageId)->with('userSender')->paginate(15);
		$items->setPageName('page_');
		return $items;
	}


	public function userSender(){
		return $this->belongsTo(User::class, 'from_user_id');
	}

	public function userReceiver(){
		return $this->belongsTo(User::class, 'to_user_id');
	}

	public function attachments(){
		return $this->belongsTo(Attachment::class, 'attachment_id');
	}
}
