<?php

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory( \App\Models\User::class , 1 )->create( [
			'name'           => 'Stepanchuk Ievgeniy' ,
			'email'          => 'bodunjo855@gmail.com' ,
			'password'       => bcrypt( '123123' ) ,
			'remember_token' => str_random( 10 ) ,
		] );
	}
}
