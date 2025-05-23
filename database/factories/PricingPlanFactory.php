<?php

namespace Database\Factories;

use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PricingPlan>
 */
class PricingPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PricingPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $billingCycle = $this->faker->randomElement(['monthly', 'yearly']);
        $price = $billingCycle === 'monthly' 
            ? $this->faker->randomElement([9.99, 19.99, 29.99, 49.99, 79.99, 99.99])
            : $this->faker->randomElement([99, 199, 299, 499, 799, 999]);
        
        $monthlyPrice = $billingCycle === 'monthly' ? $price : ($price / 12);
        $yearlyPrice = $billingCycle === 'yearly' ? $price : ($price * 10); // 2 months free
        
        $name = $this->faker->randomElement(['Starter', 'Basic', 'Pro', 'Business', 'Enterprise', 'Ultimate']);
        
        return [
            'product_id' => Product::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'stripe_price_id' => 'price_' . Str::random(14),
            'price' => $price,
            'monthly_price' => $monthlyPrice,
            'yearly_price' => $yearlyPrice,
            'billing_cycle' => $billingCycle,
            'features' => $this->getPlanFeatures($name),
            'is_featured' => $this->faker->boolean(20),
            'is_active' => $this->faker->boolean(90),
            'trial_days' => $this->faker->randomElement([0, 7, 14, 30]),
            'metadata' => [
                'popular' => $this->faker->boolean(30),
                'recommended' => $this->faker->boolean(20),
                'best_value' => $this->faker->boolean(25),
                'highlight_color' => $this->faker->randomElement(['blue', 'green', 'purple', 'orange', 'pink']),
                'tag' => $this->faker->randomElement(['New', 'Popular', 'Best Value', null, null]),
            ],
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function monthly()
    {
        return $this->state(function (array $attributes) {
            return [
                'billing_cycle' => 'monthly',
                'price' => $attributes['monthly_price'] ?? $this->faker->randomElement([9.99, 19.99, 29.99, 49.99, 79.99, 99.99]),
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function yearly()
    {
        return $this->state(function (array $attributes) {
            return [
                'billing_cycle' => 'yearly',
                'price' => $attributes['yearly_price'] ?? $this->faker->randomElement([99, 199, 299, 499, 799, 999]),
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
            ];
        });
    }

    /**
     * Get features based on plan name.
     *
     * @param string $planName
     * @return array
     */
    protected function getPlanFeatures(string $planName): array
    {
        $commonFeatures = [
            'Automatic transcription',
            'Basic formatting',
        ];

        $planSpecificFeatures = [
            'Starter' => [
                '5 hours of transcription per month',
                'Standard accuracy',
                'Email support',
                'Web access',
                '1 user'
            ],
            'Basic' => [
                '10 hours of transcription per month',
                'Enhanced accuracy',
                'Email support',
                'Web & mobile access',
                '2 users'
            ],
            'Pro' => [
                '25 hours of transcription per month',
                'High accuracy',
                'Priority email support',
                'Web & mobile access',
                'Multiple export formats',
                '5 users',
                'Basic analytics'
            ],
            'Business' => [
                '50 hours of transcription per month',
                'Premium accuracy',
                'Priority support',
                'Web & mobile access',
                'All export formats',
                '10 users',
                'Advanced analytics',
                'Team collaboration'
            ],
            'Enterprise' => [
                '100 hours of transcription per month',
                'Enterprise-grade accuracy',
                'Dedicated support',
                'Custom integrations',
                'Unlimited users',
                'Advanced security',
                'Custom vocabulary',
                'White labeling'
            ],
            'Ultimate' => [
                'Unlimited transcription',
                'Maximum accuracy',
                '24/7 dedicated support',
                'Custom development',
                'Unlimited users',
                'Enterprise security',
                'Custom vocabulary',
                'White labeling',
                'On-premises option'
            ]
        ];

        return array_merge(
            $commonFeatures,
            $planSpecificFeatures[$planName] ?? []
        );
    }
}
