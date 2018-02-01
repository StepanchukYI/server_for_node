<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function getUserById( User $user )
	{
		return  $user;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 */
	public function postUserById( Request $request)
	{
		$user = User::find($request->get('id'));
		$messages = ChatMessage::messages($user->last_message);
		return  ['user' => $user, 'messages' => $messages] ;

	}

	/**
	 * @param Request $request
	 *
	 * @return boolean
	 */
	public function postLogout( Request $request)
	{
		return  User::updateUser($request->get('user_id'), $request->get('last_message'));
	}
}
