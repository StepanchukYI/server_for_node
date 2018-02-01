<?php

namespace App\Http\Controllers;

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
	 * @return User
	 *
	 */
	public function postUserById( Request $request)
	{
		return  User::find($request->get('id'));
	}

	public function postLogout( Request $request)
	{
		return  User::updateUser($request->get('user_id'), $request->get('last_message'));
	}
}
