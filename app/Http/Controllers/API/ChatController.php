<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
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
		return ChatMessage::createMessages( $request->get('msg' ));
	}
}
