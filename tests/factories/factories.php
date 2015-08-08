<?php
$factory('SourceQuartet\VisitorLog\VisitorModel', [
    'sid' => $faker->text(25),
    'ip' => $faker->ipv4,
    'page' => $faker->url,
    'useragent' => $faker->userAgent,
    'created_at' => $faker->dateTime,
    'updated_at' => $faker->dateTime,
]);

$factory('SourceQuartet\Tests\User', [
    'name' => $faker->name,
    'email' => $faker->email,
    'password' => $faker->password,
    'created_at' => $faker->dateTime,
    'updated_at' => $faker->dateTime,
]);
