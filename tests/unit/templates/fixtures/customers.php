<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
        'name' => $faker->firstNameMale,
        'surname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'time_add' => $faker->dateTimeBetween('-1 years')->format('Y-m-d H:i:s'),
        'status' => $faker->randomElement(['new', 'registered', 'refused', 'unavailable']),
];