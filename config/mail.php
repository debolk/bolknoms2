<?php

return array(
  'driver' => env('MAIL_DRIVER'),
  'host' => env('MAIL_HOST', null),
  'port' => env('MAIL_PORT', null),
  'from' => 'noreply@noms.debolk.nl',
  'encryption' => 'tls',
  'username' => env('SENDGRID_USERNAME'),
  'password' => env('SENDGRID_PASSWORD'),
  'sendmail' => '/usr/sbin/sendmail -bs',
  'pretend' => env('MAIL_PRETEND', false),
);
