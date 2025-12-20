<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class ProfileDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $dummyProfiles = [
            [
                // Admin user profile
                'full_name' => 'Ahmad bin Abdullah',
                'ic_number' => '00-123456',
                'ic_color' => 'Yellow',
                'sex' => 'Male',
                'marital_status' => 'Married',
                'birthdate' => Carbon::parse('1985-05-15'),
                'place_of_birth' => 'Bandar Seri Begawan',
                'phone_number' => '+673-7123456',
                'citizenship' => 'Brunei Darussalam',
                'address_of_country_of_origin' => 'Bandar Seri Begawan, Brunei Darussalam',
                'address_in_brunei' => 'No. 123, Jalan Gadong, Bandar Seri Begawan, Brunei Darussalam',
                'passport_number' => 'B1234567',
                'position' => 'Administrator',
                'faculty' => 'Faculty of Engineering',
                'department' => 'Information Technology',
                'employment_type' => 'Permanent',
                'salary_scale' => 'BND 5000-6000',
                'current_salary' => 5500.00,
                'duration_of_appointment' => null,
                'spouse_full_name' => 'Siti binti Ahmad',
                'spouse_ic_number' => '00-234567',
                'spouse_citizenship' => 'Brunei Darussalam',
                'spouse_workplace' => 'Ministry of Education',
                'spouse_position' => 'Senior Officer',
                'spouse_department' => 'Curriculum Development',
                'spouse_address_country_of_origin' => 'Bandar Seri Begawan, Brunei Darussalam',
                'spouse_address_brunei' => 'No. 123, Jalan Gadong, Bandar Seri Begawan, Brunei Darussalam',
            ],
            [
                // Test user profile
                'full_name' => 'Sarah binti Mohd Ali',
                'ic_number' => '00-345678',
                'ic_color' => 'Green',
                'sex' => 'Female',
                'marital_status' => 'Single',
                'birthdate' => Carbon::parse('1992-08-20'),
                'place_of_birth' => 'Kuala Belait',
                'phone_number' => '+673-7234567',
                'citizenship' => 'Brunei Darussalam',
                'address_of_country_of_origin' => 'Kuala Belait, Brunei Darussalam',
                'address_in_brunei' => 'No. 456, Jalan Maulana, Kuala Belait, Brunei Darussalam',
                'passport_number' => null,
                'position' => 'Lecturer',
                'faculty' => 'Faculty of Business',
                'department' => 'Business Administration',
                'employment_type' => 'Permanent',
                'salary_scale' => 'BND 4000-5000',
                'current_salary' => 4500.00,
                'duration_of_appointment' => null,
                'spouse_full_name' => null,
                'spouse_ic_number' => null,
                'spouse_citizenship' => null,
                'spouse_workplace' => null,
                'spouse_position' => null,
                'spouse_department' => null,
                'spouse_address_country_of_origin' => null,
                'spouse_address_brunei' => null,
            ],
        ];
        
        $index = 0;
        foreach ($users as $user) {
            // Use predefined data for first users, generate for others
            if ($index < count($dummyProfiles)) {
                $profileData = $dummyProfiles[$index];
            } else {
                // Generate random but realistic data for additional users
                $isMale = rand(0, 1) === 1;
                $isMarried = rand(0, 1) === 1;
                $birthYear = rand(1980, 1995);
                $birthMonth = rand(1, 12);
                $birthDay = rand(1, 28);
                
                $icColors = ['Yellow', 'Green', 'Red', 'Purple'];
                $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];
                $employmentTypes = ['Permanent', 'Contract', 'Temporary', 'Part-time'];
                $faculties = [
                    'Faculty of Engineering',
                    'Faculty of Business',
                    'Faculty of Science',
                    'Faculty of Arts and Social Sciences',
                    'Faculty of Health Sciences',
                ];
                $departments = [
                    'Information Technology',
                    'Business Administration',
                    'Computer Science',
                    'Electrical Engineering',
                    'Mechanical Engineering',
                    'Accounting',
                    'Marketing',
                ];
                $positions = [
                    'Lecturer',
                    'Senior Lecturer',
                    'Assistant Professor',
                    'Associate Professor',
                    'Professor',
                    'Research Officer',
                    'Administrative Officer',
                ];
                
                $profileData = [
                    'full_name' => $isMale 
                        ? fake()->firstNameMale() . ' bin ' . fake()->lastName()
                        : fake()->firstNameFemale() . ' binti ' . fake()->lastName(),
                    'ic_number' => '00-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'ic_color' => $icColors[array_rand($icColors)],
                    'sex' => $isMale ? 'Male' : 'Female',
                    'marital_status' => $maritalStatuses[array_rand($maritalStatuses)],
                    'birthdate' => Carbon::create($birthYear, $birthMonth, $birthDay),
                    'place_of_birth' => fake()->randomElement(['Bandar Seri Begawan', 'Kuala Belait', 'Tutong', 'Seria']),
                    'phone_number' => '+673-' . rand(7000000, 7999999),
                    'citizenship' => 'Brunei Darussalam',
                    'address_of_country_of_origin' => fake()->address(),
                    'address_in_brunei' => 'No. ' . rand(1, 999) . ', ' . fake()->streetName() . ', ' . fake()->randomElement(['Bandar Seri Begawan', 'Kuala Belait', 'Tutong']) . ', Brunei Darussalam',
                    'passport_number' => rand(0, 1) ? 'B' . rand(1000000, 9999999) : null,
                    'position' => $positions[array_rand($positions)],
                    'faculty' => $faculties[array_rand($faculties)],
                    'department' => $departments[array_rand($departments)],
                    'employment_type' => $employmentTypes[array_rand($employmentTypes)],
                    'salary_scale' => 'BND ' . rand(3000, 8000) . '-' . rand(4000, 9000),
                    'current_salary' => rand(3000, 8000) + (rand(0, 99) / 100),
                    'duration_of_appointment' => $isMarried && rand(0, 1) ? rand(1, 3) . ' years' : null,
                    'spouse_full_name' => $isMarried 
                        ? ($isMale ? fake()->firstNameFemale() : fake()->firstNameMale()) . ' binti ' . fake()->lastName()
                        : null,
                    'spouse_ic_number' => $isMarried ? '00-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) : null,
                    'spouse_citizenship' => $isMarried ? 'Brunei Darussalam' : null,
                    'spouse_workplace' => $isMarried ? fake()->randomElement(['Ministry of Education', 'Ministry of Health', 'Ministry of Finance', 'Private Company']) : null,
                    'spouse_position' => $isMarried ? fake()->randomElement(['Officer', 'Senior Officer', 'Manager', 'Executive']) : null,
                    'spouse_department' => $isMarried ? fake()->randomElement(['Administration', 'Finance', 'Human Resources', 'Operations']) : null,
                    'spouse_address_country_of_origin' => $isMarried ? fake()->address() : null,
                    'spouse_address_brunei' => $isMarried ? 'No. ' . rand(1, 999) . ', ' . fake()->streetName() . ', Bandar Seri Begawan, Brunei Darussalam' : null,
                ];
            }
            
            $user->update($profileData);
            $index++;
        }
        
        $this->command->info('Profile data seeded successfully for ' . $users->count() . ' users.');
    }
}
