<?php

return array(
  'driver' => env('MAIL_DRIVER'),
  'host' => env('MAIL_HOST'),
  'port' => env('MAIL_PORT'),
  'from' => 'noreply@noms.debolk.nl',
  'encryption' => 'tls',
  'username' => env('MAIL_USERNAME'),
  'password' => env('MAIL_PASSWORD'),
  'sendmail' => '/usr/sbin/sendmail -bs',
  'pretend' => env('MAIL_PRETEND', false),
);
