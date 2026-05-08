<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Credential;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Credential>
 */
class CredentialFactory extends Factory
{
    protected $model = Credential::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'login' => $this->faker->word(),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(8, 24),
            'url' => $this->faker->url(),
            'category_id' => Category::factory(),
            'user_id' => User::query()
                ->where('email', 'test@localhost')
                ->first()
                ->id
            ?? User::factory(),
        ];
    }
}
