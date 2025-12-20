<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CpdApplication;
use App\Models\User;
use Carbon\Carbon;

class CpdApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users except administrators (or include them if needed)
        $users = User::where('role', '!=', 'Administrator')->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found to create CPD applications for.');
            return;
        }

        $eventTypes = ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement'];
        $statuses = ['submitted', 'under_review', 'approved', 'rejected'];
        $paymentPreferences = ['bursar_office', 'pay_first_submit_receipt', 'pay_first_reimburse'];
        $ticketPreferences = ['cpd_secretariat', 'purchase_self_submit', 'purchase_self_reimburse'];
        
        $conferences = [
            'International Conference on Engineering and Technology',
            'Asia-Pacific Business Management Conference',
            'Global Health Sciences Summit',
            'International Computer Science Conference',
            'World Conference on Education and Learning',
            'International Research Symposium',
            'Regional Academic Excellence Forum',
            'International Innovation and Technology Expo',
        ];
        
        $venues = [
            'Singapore' => ['Marina Bay Sands', 'Singapore Convention Centre', 'Raffles City Convention Centre'],
            'Malaysia' => ['Kuala Lumpur Convention Centre', 'Putra World Trade Centre', 'Sunway Pyramid Convention Centre'],
            'Thailand' => ['Bangkok International Trade & Exhibition Centre', 'Queen Sirikit National Convention Centre'],
            'Indonesia' => ['Jakarta Convention Centre', 'Bali Nusa Dua Convention Centre'],
            'Philippines' => ['Manila Hotel', 'Philippine International Convention Centre'],
            'Australia' => ['Sydney Convention Centre', 'Melbourne Convention Centre'],
        ];
        
        $organisers = [
            'IEEE',
            'ACM',
            'International Association of Universities',
            'Asia-Pacific Academic Consortium',
            'Global Education Network',
            'International Research Council',
            'World Academic Forum',
        ];

        foreach ($users as $user) {
            // Create 1-3 CPD applications per user
            $numApplications = rand(1, 3);
            
            for ($i = 0; $i < $numApplications; $i++) {
                // Determine event type based on user's position/faculty
                $eventType = $eventTypes[array_rand($eventTypes)];
                
                // Select appropriate conference/event based on user's field
                $eventTitle = $conferences[array_rand($conferences)];
                if (str_contains(strtolower($user->faculty ?? ''), 'engineering')) {
                    $eventTitle = 'International Conference on Engineering and Technology';
                } elseif (str_contains(strtolower($user->faculty ?? ''), 'business')) {
                    $eventTitle = 'Asia-Pacific Business Management Conference';
                } elseif (str_contains(strtolower($user->faculty ?? ''), 'health')) {
                    $eventTitle = 'Global Health Sciences Summit';
                }
                
                // Select country and venue
                $country = array_rand($venues);
                $venue = $venues[$country][array_rand($venues[$country])];
                
                // Set dates (future dates for active applications)
                $eventDate = Carbon::now()->addDays(rand(30, 180));
                $departureDate = $eventDate->copy()->subDays(1);
                $arrivalDate = $eventDate->copy()->addDays(rand(1, 3));
                
                // Determine status
                $status = $statuses[array_rand($statuses)];
                $submittedAt = null;
                if ($status !== 'draft') {
                    $submittedAt = Carbon::now()->subDays(rand(1, 60));
                }
                
                // Create application data
                $applicationData = [
                    'user_id' => $user->id,
                    'ic_number' => $user->ic_number ?? '00-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'passport_number' => $user->passport_number,
                    'post' => $user->position ?? 'Lecturer',
                    'programme_area' => $user->department ?? 'General',
                    'faculty_school_centre_section' => $user->faculty ?? 'Faculty of Engineering',
                    'event_type' => $eventType,
                    'event_title' => $eventTitle,
                    'event_date' => $eventDate,
                    'venue' => $venue,
                    'host_country' => $country,
                    'organiser_name' => $organisers[array_rand($organisers)],
                    'registration_fee' => rand(200, 2000) + (rand(0, 99) / 100),
                    'registration_fee_deadline' => $eventDate->copy()->subDays(rand(7, 30)),
                    'payment_preference' => $paymentPreferences[array_rand($paymentPreferences)],
                    'registration_fee_includes' => 'Conference materials, lunch, coffee breaks, and certificate of attendance.',
                    'other_benefits' => 'Networking opportunities, access to conference proceedings, and professional development credits.',
                    'paper_title_1' => rand(0, 1) ? 'Research on ' . $user->department . ' Applications in Modern Technology' : null,
                    'is_first_author_1' => rand(0, 1) === 1,
                    'paper_title_2' => rand(0, 1) ? 'Advanced Studies in ' . $user->faculty : null,
                    'is_first_author_2' => rand(0, 1) === 1,
                    'relevance_to_post' => 'This event is highly relevant to my current position as ' . ($user->position ?? 'Lecturer') . ' in ' . ($user->department ?? 'the department') . '. It will enhance my knowledge and skills in the latest developments in my field.',
                    'expectations_upon_completion' => 'Upon completion, I expect to gain new insights, network with international peers, and bring back valuable knowledge to share with colleagues and students. This will contribute to improving our curriculum and research activities.',
                    'publication_name' => rand(0, 1) ? 'International Journal of ' . ($user->faculty ?? 'Engineering') : null,
                    'previous_event_type' => rand(0, 1) ? $eventTypes[array_rand($eventTypes)] : null,
                    'previous_event_title' => rand(0, 1) ? $conferences[array_rand($conferences)] : null,
                    'previous_event_date' => rand(0, 1) ? Carbon::now()->subMonths(rand(6, 24)) : null,
                    'previous_event_venue' => rand(0, 1) ? (function() use ($venues) {
                        $prevCountry = array_rand($venues);
                        return $venues[$prevCountry][array_rand($venues[$prevCountry])];
                    })() : null,
                    'paper_published' => rand(0, 1) === 1,
                    'publication_details' => rand(0, 1) ? 'Published in International Journal, Volume ' . rand(1, 50) . ', 2024' : null,
                    'report_submitted' => rand(0, 1) === 1,
                    'airfare_provided_by_organiser' => rand(0, 1) === 1,
                    'flight_route' => 'Brunei International Airport (BWN) to ' . $country . ' International Airport',
                    'departure_date' => $departureDate,
                    'arrival_date' => $arrivalDate,
                    'ticket_purchase_preference' => $ticketPreferences[array_rand($ticketPreferences)],
                    'accommodation_provided_by_organiser' => rand(0, 1) === 1,
                    'hotel_name_address' => rand(0, 1) ? 'Conference Hotel, ' . $venue . ', ' . $country : null,
                    'accommodation_similar_to_venue' => rand(0, 1) === 1,
                    'airport_transfer_provided' => rand(0, 1) === 1,
                    'daily_transportation_provided' => rand(0, 1) === 1,
                    'daily_allowance_provided' => rand(0, 1) === 1,
                    'allowance_amount' => rand(0, 1) ? rand(50, 200) + (rand(0, 99) / 100) : null,
                    'visa_required' => in_array($country, ['Australia', 'Thailand', 'Indonesia', 'Philippines']),
                    'contributed_to_consultancy_fund' => rand(0, 1) === 1,
                    'contribution_amount' => rand(0, 1) ? rand(500, 5000) + (rand(0, 99) / 100) : null,
                    'entitlement_amount' => rand(0, 1) ? rand(1000, 10000) + (rand(0, 99) / 100) : null,
                    'fund_purpose' => rand(0, 1) ? 'To support professional development and research activities' : null,
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                ];
                
                CpdApplication::create($applicationData);
            }
        }
        
        $totalApplications = CpdApplication::count();
        $this->command->info("CPD applications seeded successfully! Created applications for {$users->count()} users. Total applications: {$totalApplications}");
    }
}
