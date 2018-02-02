<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;


class ApiUserController extends Controller
{
	public function getUserById( User $user )
	{
		return $user;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 */
	public function postLogin( Request $request )
	{
		$user     = User::find( $request->get( 'id' ) );
		$messages = ChatMessage::messages( $user->last_message_id );

		return [ 'user' => $user, 'message' => $messages ];
	}


	/**
	 * @param Request $request
	 *
	 * @return boolean
	 */
	public function postLogout( Request $request )
	{
		return User::Where( 'id', $request->get( 'user_id' ) )->update( [ 'last_user_id' => $request->get( 'last_message' ) ] );
	}


}
