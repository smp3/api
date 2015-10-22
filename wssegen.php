#!/usr/bin/env php
<?php

date_default_timezone_set('Europe/Warsaw');
function hashPassword($password, $salt)
{
    $salted = $password . '{' . $salt . '}';
    $digest = hash('sha512', $salted, true);

    //5000 iterations: the sysmfony default iterations
    for ($i = 1; $i < 5000; $i++) {
        $digest = hash('sha512', $digest . $salted, true);
    }

    return base64_encode($digest);
}

function wsse_header($username, $hashedPassword)
{
    $nonce = hash_hmac('sha512', uniqid(null, true), uniqid(), true);
    $created = new DateTime('now', new DateTimezone('UTC'));
    $created = $created->format(DateTime::ISO8601);
    $digest = hash('sha512', $nonce . $created . $hashedPassword, true);

    return sprintf(
            'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $username, base64_encode($digest), base64_encode($nonce), $created
    );
}

echo "\n";

echo wsse_header('maciek',
                 hashPassword('test', 'someSalt'));

echo "\n";