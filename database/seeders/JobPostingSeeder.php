<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosting;
use Carbon\Carbon;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            // ACADEMIC POSITIONS
            [
                'title' => 'Senior Lecturer in Computer Science',
                'department' => 'Faculty of Engineering',
                'type' => 'academic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(30),
                'description' => 'We are seeking a highly qualified Senior Lecturer in Computer Science to join our dynamic Faculty of Engineering. The successful candidate will be responsible for teaching undergraduate and postgraduate courses, conducting research, and contributing to curriculum development.',
                'requirements' => '• PhD in Computer Science or related field\n• Minimum 5 years of teaching experience at university level\n• Strong research background with publications in reputable journals\n• Experience in software development and programming languages\n• Excellent communication and interpersonal skills\n• Ability to supervise postgraduate students',
                'status' => 'active',
                'applications_count' => rand(5, 25),
            ],
            [
                'title' => 'Assistant Professor in Business Administration',
                'department' => 'Faculty of Business',
                'type' => 'academic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(45),
                'description' => 'The Faculty of Business invites applications for an Assistant Professor position in Business Administration. The role involves teaching, research, and service activities in areas such as management, marketing, and organizational behavior.',
                'requirements' => '• PhD in Business Administration, Management, or related field\n• Demonstrated teaching excellence\n• Active research agenda with potential for publication\n• Industry experience is an advantage\n• Strong analytical and problem-solving skills\n• Commitment to student success and academic excellence',
                'status' => 'active',
                'applications_count' => rand(8, 30),
            ],
            [
                'title' => 'Lecturer in Electrical Engineering',
                'department' => 'Faculty of Engineering',
                'type' => 'academic',
                'employment_type' => 'Contract',
                'deadline' => Carbon::now()->addDays(25),
                'description' => 'Join our Electrical Engineering department as a Lecturer. You will teach courses in electrical circuits, power systems, and electronics, while also engaging in research activities and industry collaborations.',
                'requirements' => '• Master\'s degree in Electrical Engineering (PhD preferred)\n• Teaching experience at tertiary level\n• Knowledge of modern electrical engineering practices\n• Ability to use simulation software (MATLAB, PSpice, etc.)\n• Strong commitment to student learning and development',
                'status' => 'active',
                'applications_count' => rand(3, 18),
            ],
            [
                'title' => 'Associate Professor in Health Sciences',
                'department' => 'Faculty of Health Sciences',
                'type' => 'academic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(40),
                'description' => 'We are looking for an experienced Associate Professor to lead research initiatives and teach in our Health Sciences programs. The position offers opportunities for collaboration with healthcare institutions and research centers.',
                'requirements' => '• PhD in Health Sciences, Public Health, or related field\n• Minimum 8 years of academic experience\n• Strong research portfolio with external funding experience\n• Clinical or professional experience in healthcare\n• Leadership skills and ability to mentor junior faculty\n• Excellent track record in teaching and student supervision',
                'status' => 'active',
                'applications_count' => rand(6, 22),
            ],
            [
                'title' => 'Lecturer in Mathematics',
                'department' => 'Faculty of Science',
                'type' => 'academic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(35),
                'description' => 'The Faculty of Science seeks a Lecturer in Mathematics to teach undergraduate mathematics courses and contribute to the development of our mathematics curriculum. Research opportunities are available.',
                'requirements' => '• Master\'s degree in Mathematics (PhD preferred)\n• Strong background in applied mathematics or statistics\n• Teaching experience preferred\n• Ability to teach a variety of mathematics courses\n• Research interests in applied mathematics or related areas',
                'status' => 'active',
                'applications_count' => rand(4, 20),
            ],

            // NON-ACADEMIC POSITIONS
            [
                'title' => 'IT Support Specialist',
                'department' => 'Information Technology Department',
                'type' => 'nonacademic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(20),
                'description' => 'We are seeking an IT Support Specialist to provide technical support to staff and students, maintain IT infrastructure, and assist with system administration. This role is essential for ensuring smooth operation of our IT services.',
                'requirements' => '• Bachelor\'s degree in Information Technology, Computer Science, or related field\n• Minimum 2 years of IT support experience\n• Knowledge of Windows and Linux operating systems\n• Experience with network administration and troubleshooting\n• Strong problem-solving and communication skills\n• Customer service orientation',
                'status' => 'active',
                'applications_count' => rand(10, 35),
            ],
            [
                'title' => 'Administrative Officer - Human Resources',
                'department' => 'Human Resources Department',
                'type' => 'nonacademic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(30),
                'description' => 'Join our HR team as an Administrative Officer. You will assist with recruitment processes, employee relations, payroll administration, and maintaining employee records. This role offers opportunities for professional growth.',
                'requirements' => '• Bachelor\'s degree in Human Resources, Business Administration, or related field\n• Knowledge of HR practices and employment laws\n• Strong organizational and administrative skills\n• Excellent interpersonal and communication abilities\n• Proficiency in MS Office and HR management systems\n• Attention to detail and confidentiality',
                'status' => 'active',
                'applications_count' => rand(12, 40),
            ],
            [
                'title' => 'Finance Officer',
                'department' => 'Finance Department',
                'type' => 'nonacademic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(25),
                'description' => 'The Finance Department is looking for a Finance Officer to manage financial transactions, prepare budgets, process payments, and maintain accurate financial records. This position plays a key role in our financial operations.',
                'requirements' => '• Bachelor\'s degree in Accounting, Finance, or related field\n• Minimum 2 years of accounting or finance experience\n• Knowledge of accounting principles and financial reporting\n• Proficiency in accounting software and MS Excel\n• Strong analytical and numerical skills\n• Attention to detail and accuracy',
                'status' => 'active',
                'applications_count' => rand(8, 28),
            ],
            [
                'title' => 'Library Assistant',
                'department' => 'Library Services',
                'type' => 'nonacademic',
                'employment_type' => 'Part-time',
                'deadline' => Carbon::now()->addDays(15),
                'description' => 'We are seeking a Library Assistant to support daily library operations, assist students and staff with information resources, manage library materials, and help maintain the library catalog system.',
                'requirements' => '• Diploma or Bachelor\'s degree in Library Science or related field\n• Basic knowledge of library systems and procedures\n• Good customer service skills\n• Ability to work with library management software\n• Organizational skills and attention to detail',
                'status' => 'active',
                'applications_count' => rand(5, 20),
            ],
            [
                'title' => 'Student Affairs Coordinator',
                'department' => 'Student Affairs Office',
                'type' => 'nonacademic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(35),
                'description' => 'Join our Student Affairs team to coordinate student activities, provide student support services, organize events, and assist with student welfare programs. This role is ideal for someone passionate about student development.',
                'requirements' => '• Bachelor\'s degree in Education, Psychology, or related field\n• Experience working with students or in educational setting\n• Strong organizational and event planning skills\n• Excellent communication and interpersonal abilities\n• Ability to work flexible hours including evenings and weekends\n• Empathy and understanding of student needs',
                'status' => 'active',
                'applications_count' => rand(7, 25),
            ],
            [
                'title' => 'Maintenance Technician',
                'department' => 'Facilities Management',
                'type' => 'nonacademic',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(20),
                'description' => 'We need a Maintenance Technician to perform routine maintenance, repairs, and inspections of university facilities and equipment. This hands-on role ensures our campus facilities are well-maintained and safe.',
                'requirements' => '• Technical diploma or certificate in maintenance, engineering, or related field\n• Minimum 3 years of maintenance experience\n• Knowledge of electrical, plumbing, and HVAC systems\n• Ability to read technical drawings and manuals\n• Physical fitness for manual work\n• Valid driver\'s license',
                'status' => 'active',
                'applications_count' => rand(6, 22),
            ],

            // TABUNG STAFF POSITIONS
            [
                'title' => 'Tabung Staff - Administrative Assistant',
                'department' => 'Tabung Department',
                'type' => 'tabung',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(30),
                'description' => 'We are looking for an Administrative Assistant to support the Tabung department operations. The role involves handling administrative tasks, maintaining records, processing documents, and providing general office support.',
                'requirements' => '• Diploma or Bachelor\'s degree in Business Administration or related field\n• Minimum 1 year of administrative experience\n• Proficiency in MS Office applications\n• Good organizational and time management skills\n• Strong communication skills in Malay and English\n• Ability to work independently and as part of a team',
                'status' => 'active',
                'applications_count' => rand(15, 45),
            ],
            [
                'title' => 'Tabung Staff - Data Entry Clerk',
                'department' => 'Tabung Department',
                'type' => 'tabung',
                'employment_type' => 'Contract',
                'deadline' => Carbon::now()->addDays(25),
                'description' => 'Join our Tabung team as a Data Entry Clerk. You will be responsible for accurately entering data into our systems, maintaining database records, verifying information, and ensuring data integrity.',
                'requirements' => '• High school diploma or equivalent\n• Fast and accurate typing skills\n• Attention to detail and accuracy\n• Basic computer skills and familiarity with data entry software\n• Ability to work with confidential information\n• Good concentration and focus',
                'status' => 'active',
                'applications_count' => rand(20, 50),
            ],
            [
                'title' => 'Tabung Staff - Customer Service Representative',
                'department' => 'Tabung Department',
                'type' => 'tabung',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(35),
                'description' => 'We are seeking a Customer Service Representative to handle inquiries, provide information to clients, assist with applications, and ensure excellent service delivery. This role requires strong communication skills and a customer-focused approach.',
                'requirements' => '• Diploma in Business, Communication, or related field\n• Minimum 1 year of customer service experience\n• Excellent verbal and written communication skills\n• Proficiency in Malay and English\n• Strong problem-solving abilities\n• Professional and friendly demeanor',
                'status' => 'active',
                'applications_count' => rand(18, 42),
            ],
            [
                'title' => 'Tabung Staff - Finance Assistant',
                'department' => 'Tabung Department',
                'type' => 'tabung',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(28),
                'description' => 'The Tabung Department needs a Finance Assistant to support financial operations, process transactions, maintain financial records, and assist with budget preparation. This role offers valuable experience in financial management.',
                'requirements' => '• Diploma or Bachelor\'s degree in Accounting, Finance, or related field\n• Basic knowledge of accounting principles\n• Proficiency in MS Excel and accounting software\n• Strong numerical and analytical skills\n• Attention to detail and accuracy\n• Ability to handle confidential financial information',
                'status' => 'active',
                'applications_count' => rand(10, 30),
            ],
            [
                'title' => 'Tabung Staff - Records Management Officer',
                'department' => 'Tabung Department',
                'type' => 'tabung',
                'employment_type' => 'Full-time',
                'deadline' => Carbon::now()->addDays(40),
                'description' => 'We are looking for a Records Management Officer to organize, maintain, and manage Tabung department records and documents. The role involves implementing record-keeping systems and ensuring compliance with documentation standards.',
                'requirements' => '• Diploma or Bachelor\'s degree in Records Management, Information Science, or related field\n• Experience in records management or document control\n• Knowledge of filing systems and document management\n• Strong organizational skills\n• Attention to detail\n• Ability to work with both physical and digital records',
                'status' => 'active',
                'applications_count' => rand(8, 25),
            ],
        ];

        foreach ($jobs as $job) {
            JobPosting::create($job);
        }

        $this->command->info('Job postings seeded successfully! Created ' . count($jobs) . ' job postings.');
    }
}
