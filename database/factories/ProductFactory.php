<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'AI Transcript Basic',
            'AI Transcript Pro',
            'AI Transcript Enterprise',
            'AI Transcript Teams',
            'AI Transcript Developer',
            'Voice Analytics Suite',
            'Meeting Intelligence Platform',
            'Interview Transcription Tool',
            'Podcast Transcription Service',
            'Medical Transcription AI'
        ]);
        
        $features = $this->getFeatures($name);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentence(10),
            'features' => $features,
            'is_active' => $this->faker->boolean(80),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'image_path' => 'images/products/' . $this->faker->numberBetween(1, 5) . '.jpg',
            'metadata' => [
                'recommended' => $this->faker->boolean(30),
                'popular' => $this->faker->boolean(40),
                'new' => $this->faker->boolean(20),
                'category' => $this->faker->randomElement(['transcription', 'analytics', 'enterprise', 'developer']),
                'icon' => $this->faker->randomElement(['document', 'mic', 'chart', 'cloud', 'message', 'video']),
            ],
        ];
    }

    /**
     * Get product-specific features.
     *
     * @param string $productName
     * @return array
     */
    protected function getFeatures(string $productName): array
    {
        $commonFeatures = [
            'Speech-to-text transcription',
            'Multiple language support',
            'Custom vocabulary',
            'Speaker diarization',
        ];

        $productSpecificFeatures = [
            'AI Transcript Basic' => [
                'Up to 10 hours of transcription per month',
                'Basic language detection',
                'Standard support',
                'Web access only'
            ],
            'AI Transcript Pro' => [
                'Up to 50 hours of transcription per month',
                'Advanced accuracy engine',
                'Priority email support',
                'Mobile and web access',
                'Export in multiple formats'
            ],
            'AI Transcript Enterprise' => [
                'Unlimited transcription hours',
                'Dedicated account manager',
                'Custom API integration',
                'On-premises deployment option',
                'Advanced security features',
                'SLA guaranteed uptime'
            ],
            'AI Transcript Teams' => [
                'Shared workspace',
                'Team collaboration features',
                'Role-based permissions',
                'Team analytics dashboard',
                'Bulk transcription processing'
            ],
            'AI Transcript Developer' => [
                'Full API access',
                'Webhook integrations',
                'Developer documentation',
                'Custom model training',
                'Sandbox environment'
            ],
            'Voice Analytics Suite' => [
                'Sentiment analysis',
                'Keyword extraction',
                'Topic clustering',
                'Voice pattern recognition',
                'Emotion detection'
            ],
            'Meeting Intelligence Platform' => [
                'Automatic meeting notes',
                'Action item extraction',
                'Calendar integration',
                'Meeting highlights',
                'Follow-up reminders'
            ],
            'Interview Transcription Tool' => [
                'Interview templates',
                'Candidate scoring',
                'Question-answer pairing',
                'Hiring team collaboration',
                'ATS integration'
            ],
            'Podcast Transcription Service' => [
                'Podcast-specific vocabulary',
                'Show notes generation',
                'Episode segmentation',
                'Distribution platform integration',
                'SEO optimization'
            ],
            'Medical Transcription AI' => [
                'HIPAA compliance',
                'Medical terminology database',
                'EMR/EHR integration',
                'Physician voice profile',
                'Medical specialty customization'
            ]
        ];

        return array_merge(
            $commonFeatures,
            $productSpecificFeatures[$productName] ?? []
        );
    }
}
