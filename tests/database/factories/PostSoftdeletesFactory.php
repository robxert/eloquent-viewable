<?php

declare(strict_types=1);

use CyrildeWit\EloquentViewable\Tests\TestClasses\Models\PostSoftdeletes;
use Faker\Generator as Faker;

/*
 * This is the Post factory.
 *
 * @var \Illuminate\Database\Eloquent\Factory  $factory
 */
$factory->define(PostSoftdeletes::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'body' => $faker->paragraph,
    ];
});
