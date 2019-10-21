<?php

// $to      = 'pcummerata@gravitio.info';
$to      = 'till20052@gmail.com';
$subject = 'the subject';
$message = 'hello';

mail($to, $subject, $message, implode(PHP_EOL, [
    'From: webmaster@example.com',
    'Reply-To: webmaster@example.com',
    sprintf('X-Mailer: PHP/%s', PHP_VERSION),
]));
