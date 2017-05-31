<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'api_user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
 
        /**
        * By default, laravel will assume there are columns (created_at, updated_at) in the table
        * that this model connects to and will try to update them on saving this model, to disable
        * that define timestamps and set to false. http://laravel.com/docs/4.2/eloquent#timestamps
        * @var boolean 
        */
       public $timestamps = false;
}
