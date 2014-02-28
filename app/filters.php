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
	//
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

Route::filter('oauth', 'OAuthFilter');
// Route::filter('oauth', function(){
//     return 'access denied!';
// });

Route::when('administratie*', 'oauth');   // Must authenticate before proceeding
