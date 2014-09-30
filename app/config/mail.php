<?php

return array(
  'driver' => 'smtp',
  'host' => 'smtp.sendgrid.net',
  'port' => 587,
  'from' => array('address' => getenv('EMAIL_REPLYTO_EMAIL'), 'name' => getenv('EMAIL_REPLYTO_NAME')),
  'encryption' => 'tls',
  'username' => getenv('SENDGRID_USERNAME'),
  'password' => getenv('SENDGRID_PASSWORD'),
);
