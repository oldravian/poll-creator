<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoWorkingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the user if it doesn't exist
        $user = User::firstOrCreate(
            ['username' => 'habib'],
            [
                'name' => 'Habib',
                'password' => Hash::make('password'),
            ]
        );

        // Clear existing polls for this user to avoid duplicates
        $user->polls()->delete();

        // Co-working space polls with realistic options
        $pollsData = [
            [
                'question' => 'Should we keep the office open on this rainy day?',
                'options' => [
                    'Yes, business as usual',
                    'Yes, but with flexible hours',
                    'No, remote work day',
                    'Let members decide individually'
                ],
                'expires_at' => now()->addDays(1),
            ],
            [
                'question' => 'What time should we start our weekly team meeting?',
                'options' => [
                    '9:00 AM',
                    '10:00 AM',
                    '11:00 AM',
                    '2:00 PM'
                ],
                'expires_at' => now()->addDays(3),
            ],
            [
                'question' => 'Which coffee brand should we stock in the kitchen?',
                'options' => [
                    'Local roasted beans',
                    'International premium brand',
                    'Budget-friendly option',
                    'Mix of different brands',
                    'Let members bring their own'
                ],
                'expires_at' => now()->addWeek(),
            ],
            [
                'question' => 'Should we implement a quiet hours policy?',
                'options' => [
                    'Yes, 9 AM - 12 PM',
                    'Yes, 2 PM - 5 PM',
                    'Yes, flexible quiet zones',
                    'No, keep current setup'
                ],
                'expires_at' => now()->addDays(5),
            ],
            [
                'question' => 'What type of background music do you prefer?',
                'options' => [
                    'Instrumental/Classical',
                    'Lo-fi/Chill',
                    'Nature sounds',
                    'No music at all',
                    'Let members choose via playlist'
                ],
                'expires_at' => null, // Never expires
            ],
            [
                'question' => 'How often should we have networking events?',
                'options' => [
                    'Weekly',
                    'Bi-weekly',
                    'Monthly',
                    'Quarterly'
                ],
                'expires_at' => now()->addDays(7),
            ],
            [
                'question' => 'Should we upgrade the WiFi to a faster plan?',
                'options' => [
                    'Yes, definitely needed',
                    'Yes, but split the extra cost',
                    'Current speed is fine',
                    'Let\'s test current speed first'
                ],
                'expires_at' => now()->addDays(2),
            ],
            [
                'question' => 'What temperature should we maintain in the office?',
                'options' => [
                    '20°C (68°F)',
                    '22°C (72°F)',
                    '24°C (75°F)',
                    'Variable based on season',
                    'Individual desk fans/heaters'
                ],
                'expires_at' => now()->subDays(4),
            ],
        ];

        // Create polls with options and some sample votes
        foreach ($pollsData as $pollData) {
            $poll = Poll::create([
                'user_id' => $user->id,
                'question' => $pollData['question'],
                'slug' => $this->generateSlug($pollData['question']),
                'expires_at' => $pollData['expires_at'],
            ]);

            // Create options for each poll
            $createdOptions = [];
            foreach ($pollData['options'] as $optionText) {
                $option = PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => $optionText,
                ]);
                $createdOptions[] = $option;
            }

            // Add some random votes to make the polls look realistic
            $this->addRandomVotes($poll, $createdOptions);
        }
    }

    /**
     * Generate a URL-friendly slug from the question
     */
    private function generateSlug(string $question): string
    {
        $slug = strtolower($question);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Limit slug length
        if (strlen($slug) > 50) {
            $slug = substr($slug, 0, 50);
            $slug = rtrim($slug, '-');
        }
        
        return $slug . '-' . time();
    }

    /**
     * Add random votes to make polls look realistic
     */
    private function addRandomVotes(Poll $poll, array $options): void
    {
        // Generate random email addresses for voters
        $voterEmails = [
            'member1@coworking.com',
            'member2@coworking.com',
            'member3@coworking.com',
            'member4@coworking.com',
            'member5@coworking.com',
            'member6@coworking.com',
            'freelancer1@gmail.com',
            'freelancer2@gmail.com',
            'startup@company.com',
            'remote.worker@email.com',
        ];

        // Randomly select how many votes this poll should have (between 3-8)
        $voteCount = rand(3, 8);
        $selectedEmails = array_slice($voterEmails, 0, $voteCount);

        foreach ($selectedEmails as $email) {
            // Randomly select an option for this voter
            $randomOption = $options[array_rand($options)];
            
            Vote::create([
                'poll_option_id' => $randomOption->id,
                'email' => $email,
                'created_at' => now()->subMinutes(rand(10, 1440)), // Random time in last 24 hours
                'updated_at' => now()->subMinutes(rand(10, 1440)),
            ]);
        }
    }
}