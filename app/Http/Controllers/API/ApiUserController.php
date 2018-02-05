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
		$messages = ChatMessage::messages( $user->last_message );
		$last_id = $messages->last()->id;
		return [ 'user' => $user , 'message' => $messages, 'last_id' => $last_id ];
	}


	/**
	 * @param Request $request
	 *
	 * @return boolean
	 */
	public function postLogout( Request $request )
	{
		$user = User::where( 'id' , $request->get( 'user_id' ) )->first();

		$user->update( [
			'last_message' => $request->get( 'last_message' )
		] );

		return [ 'user'         => $user ,
		         'user_id'      => $request->get( 'user_id' ) ,
		         'last_message' => $request->get( 'last_message' )
		];
	}


}
