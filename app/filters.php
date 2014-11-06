<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	setlocale(LC_ALL,  'nl_NL');
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| Custom authentication filter for De Bolk OAUth
|
*/
Route::filter('oauth', 'OAuthFilter@filter');
Route::filter('oauth_callback', 'OAuthFilter@callback');

Route::when('administratie*', 'oauth');   // Must authenticate before proceeding
Route::when('oauth', 'oauth_callback');        // Catch the callback to authenticate
