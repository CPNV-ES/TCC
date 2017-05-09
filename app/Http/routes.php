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

Route::group(['middleware' => ['web']], function ()
{
    /*
     * Index page
     */
     Route::group(['middleware' => 'profileIsValide'], function() {
       Route::resource('/', 'WelcomeController');
       Route::resource('home', 'WelcomeController');
     });



    /*
    * Login page
    */
    Route::resource('login', 'SessionController');
    Route::get('logout', 'SessionController@destroy');


    /*
     * Booking
     */

    Route::group(['namespace' => 'Booking', 'middleware' => 'profileIsValide'], function()
    {
        Route::resource('booking', 'BookingController');
        Route::get('booking/confirmation/{token}', 'BookingController@confirmation')->name('booking.confirmation');
        Route::post('booking/askcancellation/{id}', 'BookingController@askcancellation')->name('booking.askcancellation');
        Route::get('booking/cancellation/{token}', 'BookingController@cancellation')->name('booking.cancellation');
    });

    /*
     * Staff booking
     */
    Route::group([ 'middleware' => ['profileIsValide', 'userIsStaff']], function(){
       Route::resource('staff_booking', 'StaffBookingController');
    });

    /*
     * MyBooking
     */
    Route::group(['namespace' => 'Booking', 'middleware' => 'profileIsValide'], function()
    {
        Route::delete('mybooking/{id}', 'BookingController@destroy');
        Route::get('mybooking', 'BookingController@MyBookingIndex');

    });



    Route::group(['namespace' => 'Registration', 'middleware' => 'profileIsValide'], function()
    {
        /*
        * register page
        */
        Route::resource('register', 'RegisterController');

        /*
         * reset password
         */
        Route::resource('password/reset', 'PasswordController@index');

        /*
        * password page
        */
        Route::resource('password', 'PasswordController');
    });


    /*
     * Only auth user can access to /profile
     */
    Route::group(['namespace' => 'Profile', 'middleware' => 'auth'], function ()
    {
        Route::resource('/profile', 'ProfileController');
    });


    /*
     * Only auth user that are admin can access to /admin
     */
    Route::group(['namespace' => 'Admin', 'middleware' => ['profileIsValide', 'auth', 'admin']], function ()
    {

        Route::resource('/admin/members', 'MemberController');
        Route::resource('/admin/config/courts', 'CourtController');
        Route::resource('/admin/config/seasons', 'SeasonController');
        Route::resource('/admin/config/subscriptions', 'SubscriptionController');
        Route::resource('/admin/config/other_options', 'OtherOptionController');
        Route::put('/admin/login/update/{id?}', 'MemberController@updateLogin');//to update only the login
        Route::post('/admin/members/checkmail', 'MemberController@checkMailUse');
        Route::resource('/admin', 'AdminController');
    });
});
