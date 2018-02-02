<?php

namespace App\Models;

use Illuminate\Mail\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	public static function updateUser( $user_id, $last_message )
	{
		$user = self::find( $user_id );
		$user->update( [
			'last_message_id' => $last_message
		] );

		return true;
	}
}