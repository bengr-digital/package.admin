<?php

namespace Bengr\Admin\Database\Factories;

use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubpageFactory extends Factory
{
    protected $model = Subpage::class;

    public function definition(): array
    {
        $path = $this->faker->slug();
        $name_code = Str::of(trim($path, '/'))->replace('/', '.')->lower()->value();
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'keywords' => implode(', ', $this->faker->words($this->faker->numberBetween(3, 6), false)),
            'path' => $path,
            'is_active' => true,
            'name_code' => $name_code == '' ? 'index' : $name_code
        ];
    }
}
