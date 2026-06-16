<?php

namespace Database\Factories;

use App\Models\InternalAnnouncement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalAnnouncement>
 */
class InternalAnnouncementFactory extends Factory
{
    protected $model = InternalAnnouncement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'body' => fake()->paragraphs(3, true),
            'category' => fake()->randomElement(array_keys(InternalAnnouncement::CATEGORIES)),
            'priority' => fake()->randomElement(array_keys(InternalAnnouncement::PRIORITIES)),
            'target_roles' => null,
            'target_staff_ids' => null,
            'attachments' => null,
            'is_pinned' => false,
            'published_at' => now(),
            'expires_at' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the announcement is pinned.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }

    /**
     * Indicate that the announcement is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    /**
     * Indicate that the announcement is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
