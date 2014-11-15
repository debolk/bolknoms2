<?php

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
App::error(function(Exception $exception, $code)
{
    // Log relevant data
    $log = "$code at " . Request::url();
    if ($code >= 500) {
        $log .= ". Stack trace:\n$exception";
    }
    Log::error($log);

    // Send notification to the technical administrator, except for common errors we don't want to see
    if (in_array($code, [403, 404])) {
        $reported_automatically = false;
    }
    else {
        MailerBug::send_bug_notification($log);
        $reported_automatically = true;
    }

    // Show a friendly error page
    return Response::view('error/index', ['code' => $code, 'reported_automatically' => $reported_automatically], $code);
});
