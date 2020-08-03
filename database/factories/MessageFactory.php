<?php

use App\User;
use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'author_id' => User::inRandomOrder()->first(),
        'recipient_id' => User::inRandomOrder()->first(),
        'message' => $faker->realText(200)
    ];
});
