<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','locale']
],function(){
	Route::group(['middleware' => 'IfNotLogin'],function(){
		Route::get('/', function () {
			return view('welcome');
		});
	});

	Auth::routes();

	Route::post('user/register','UserRegistretionController@userRegistration');
	//Route::get('/home', 'HomeController@index')->name('home');

	Route::post('/login/custom',[
		'uses' => 'LoginController@login',
		'as' => 'login.custom'
	]);

	// Stripe Payment GetWay Routes
	Route::get('addmoney/stripe', array('as' => 'addmoney.paywithstripe','uses' => 'AddMoneyController@payWithStripe'));
	Route::post('addmoney/stripe', array('as' => 'addmoney.stripe','uses' => 'AddMoneyController@postPaymentWithStripe'));

});

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','locale','auth','admin']
],function(){
	Route::get('/admin/dashboard','Admin\AdminController@AdminHome');

	Route::get('edit/admin/profile/{user_id}','Admin\AdminController@editUserProfile');
	Route::post('update/admin/profile','Admin\AdminController@UpdateAdminProfile');
});

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','locale','auth','user']
],function(){
	Route::get('/user/dashboard','User\UserController@UserHome');
	Route::get('edit/user/profile/{user_id}','User\UserController@editUserProfile');
	Route::post('update/user/profile','User\UserController@UpdateAdminProfile');
});