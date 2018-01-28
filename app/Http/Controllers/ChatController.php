<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
	public function setMessages( Request $request )
	{
		return [ 'id' => $request->get( 'msg' ) ];

		return ChatMessage::createMessages( $request->msg );
	}
}
