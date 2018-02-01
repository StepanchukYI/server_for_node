<?php

namespace App\Http\Controllers;

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
