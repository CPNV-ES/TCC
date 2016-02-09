<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
 * Admin page
 */
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
	Route::get('index', function()
	{
		return "My little poney from admin panel";
	});
});



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| 
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/ 

Route::group(['middleware' => ['web']], function () {
    // 
});


/*
 * Only auth user can acces to /admin
 */
Route::controllers(['auth' => 'Auth\AuthController',
					'password' => 'Auth\PasswordController']);
