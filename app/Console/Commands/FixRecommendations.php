<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CpdRecommendation;
use App\Models\CpdApplication;

class FixRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendations:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix recommendations with incomplete or incorrect data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking and fixing CPD Applications and Recommendations...');
        
        // First, fix CPD applications with incomplete data
        $this->info('Checking CPD Applications...');
        $applications = CpdApplication::all();
        $fixedApps = 0;
        
        foreach ($applications as $app) {
            $needsFix = false;
            $fixes = [];
            
            // Check if event title is suspicious
            if (is_numeric($app->event_title) || strlen($app->event_title) < 5) {
                $needsFix = true;
                $fixes[] = 'Event title appears to be incomplete';
            }
            
            if ($needsFix) {
                $this->warn("CPD Application ID {$app->id} needs fixing:");
                foreach ($fixes as $fix) {
                    $this->line("  - {$fix}");
                }
                
                // Set a more descriptive event title
                $newTitle = "CPD Event - " . ucfirst($app->event_type) . " (ID: {$app->id})";
                $app->update(['event_title' => $newTitle]);
                $this->info("  ✓ Fixed CPD Application ID {$app->id} - Set event_title to: {$newTitle}");
                $fixedApps++;
            }
        }
        
        if ($fixedApps > 0) {
            $this->info("Successfully fixed {$fixedApps} CPD applications.");
        }
        
        // Now fix recommendations
        $this->info('Checking CPD Recommendations...');
        $recommendations = CpdRecommendation::all();
        $fixed = 0;
        
        foreach ($recommendations as $rec) {
            $needsFix = false;
            $fixes = [];
            
            // Check if event title is suspicious (like "124")
            if (is_numeric($rec->event_title) || strlen($rec->event_title) < 5) {
                $needsFix = true;
                $fixes[] = 'Event title appears to be incomplete';
            }
            
            // Check if any required fields are empty
            if (empty($rec->applicant_name) || empty($rec->event_title) || empty($rec->event_type)) {
                $needsFix = true;
                $fixes[] = 'Missing required fields';
            }
            
            if ($needsFix) {
                $this->warn("Recommendation ID {$rec->id} needs fixing:");
                foreach ($fixes as $fix) {
                    $this->line("  - {$fix}");
                }
                
                // Try to get data from the associated CPD application
                $application = $rec->cpdApplication;
                if ($application) {
                    $this->info("  Attempting to fix with data from CPD Application ID {$application->id}");
                    
                    $updates = [];
                    
                    if (empty($rec->applicant_name) && $application->user) {
                        $updates['applicant_name'] = $application->user->name;
                        $this->line("  - Setting applicant_name to: {$application->user->name}");
                    }
                    
                    if (empty($rec->event_title) || is_numeric($rec->event_title)) {
                        $updates['event_title'] = $application->event_title;
                        $this->line("  - Setting event_title to: {$application->event_title}");
                    }
                    
                    if (empty($rec->event_type)) {
                        $updates['event_type'] = $application->event_type;
                        $this->line("  - Setting event_type to: {$application->event_type}");
                    }
                    
                    if (empty($rec->applicant_post)) {
                        $updates['applicant_post'] = $application->post;
                        $this->line("  - Setting applicant_post to: {$application->post}");
                    }
                    
                    if (empty($rec->programme_area)) {
                        $updates['programme_area'] = $application->programme_area;
                        $this->line("  - Setting programme_area to: {$application->programme_area}");
                    }
                    
                    if (empty($rec->faculty_school_centre_section)) {
                        $updates['faculty_school_centre_section'] = $application->faculty_school_centre_section;
                        $this->line("  - Setting faculty_school_centre_section to: {$application->faculty_school_centre_section}");
                    }
                    
                    if (!empty($updates)) {
                        $rec->update($updates);
                        $fixed++;
                        $this->info("  ✓ Fixed recommendation ID {$rec->id}");
                    }
                } else {
                    $this->error("  ✗ No associated CPD application found for recommendation ID {$rec->id}");
                }
                
                $this->line("");
            }
        }
        
        if ($fixed > 0) {
            $this->info("Successfully fixed {$fixed} recommendations.");
        } else {
            $this->info("No recommendations needed fixing.");
        }
        
        $this->info("Total fixes: {$fixedApps} applications + {$fixed} recommendations = " . ($fixedApps + $fixed));
    }
}
