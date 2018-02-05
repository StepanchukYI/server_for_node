<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatMessage
 * @package App\Models
 *
 * @property            $id
 * @property            $created_at
 * @property            $updated_at
 * @property User       $user
 * @property Attachment $attachments
 */
class ChatMessage extends Model
{
	/**
	 * @param $request
	 *
	 * @return mixed
	 */
	public static function createMessages( $request )
	{
		DB::beginTransaction();
		foreach ( $request as $item )
		{
			$chatMSG          = new ChatMessage();
			$chatMSG->user_id = $item['user'];
			$chatMSG->message = $item['text'];
			$chatMSG->send_at = $item['time']['date'] . ' ' . $item['time']['time'];

			$chatMSG->save();
		}

		DB::commit();

		return $chatMSG->id;
	}


	/**
	 * @param $chat_room_id
	 * @param $user_id
	 * @param $message
	 *
	 * @return mixed
	 */
	public static function createRoomMessage( $chat_room_id, $user_id, $message )
	{
		return self::create( [
			'room_id' => $chat_room_id,
			'user_id' => $user_id,
			'message' => $message
		] );
	}


	/**
	 * @param $chat_room_id
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public static function reviewMessage( $chat_room_id, $user_id )
	{
		return self::where( 'chat_room_id', $chat_room_id )->where( 'user_id', $user_id )->update(
			[ 'review' => true ]
		);
	}

	/**
	 * @param $messageId
	 *
	 * @return LengthAwarePaginator
	 */
	public static function messages( $messageId )
	{
		/** @var LengthAwarePaginator $items */
		$items = self::where( 'id', '>', (int) $messageId - 10 )->with( 'userSender' )->limit( 25 )->get();

		return $items;
	}

	/**
	 * @param $messageId
	 *
	 * @return LengthAwarePaginator
	 */
	public static function nextMessages( $messageId )
	{
		/** @var LengthAwarePaginator $items */
		$items = self::where( 'id', '>', $messageId )->with( 'userSender' )->limit( 15 )->get();

		return $items;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function userSender()
	{
		return $this->belongsTo( User::class, 'user_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function attachments()
	{
		return $this->belongsTo( Attachment::class, 'attachment_id' );
	}
}
