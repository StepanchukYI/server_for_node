<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function setMessages( Request $request )
	{
		return [ 'id' => ChatMessage::createMessages( $request->get( 'msg' ) ) ];
	}


	public function getNextMessages( Request $request )
	{
		$messages = ChatMessage::nextMessages( $request->get( 'msg' ) );
		$last_id = $messages->last()->id;

		return [ 'messages' => $messages , 'last_id' => $last_id ];
	}

	public function sendMessageToUser( Request $request )
	{
		//user_id || message || chat_room_id || attachment_id || receiver_id
		if ( ! $user_id = $request->get( 'user_id' ) ) {
			return [ 'error' => 'User id error' ];
		}

		collect();
		$user_ids[] = $user_id;
		$user_ids[] = $request->get( 'receiver_id' );
		$user_ids   = collect( $user_ids )->sort();

		if ( ! $chat_room_id = $request->get( 'chat_room_id' ) ) {
			if ( ! $chat_room_id = ChatRoom::where( 'user_ids' , $user_ids->toJson() )->first() ) {
				$chat_room_id = ChatRoom::createRoom( $user_ids );
			}
		}


		if ( ChatMessage::createRoomMessage( $chat_room_id , $user_id , $request->get( 'message' ) ) ) {
			return [ 'message' => true ];
		} else {
			return [ 'message' => 'Creating message error' ];
		}

	}
}
