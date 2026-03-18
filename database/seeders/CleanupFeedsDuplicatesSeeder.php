<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupFeedsDuplicatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cleanup duplicate feed_likes (keep latest created_at)
        DB::statement('DELETE fl1 FROM `feed_likes` fl1 
            INNER JOIN `feed_likes` fl2 WHERE 
            fl1.feed_id = fl2.feed_id 
            AND fl1.user_id = fl2.user_id 
            AND fl1.created_at > fl2.created_at');
        
        // Cleanup duplicate feed_user_joins (keep latest created_at) 
        DB::statement('DELETE fuj1 FROM `feed_user_joins` fuj1 
            INNER JOIN `feed_user_joins` fuj2 WHERE 
            fuj1.feed_id = fuj2.feed_id 
            AND fuj1.user_id = fuj2.user_id 
            AND fuj1.created_at > fuj2.created_at');
        
        $this->command->info('✅ Feed duplicates cleaned up! Unique constraints now enforced.');
    }
}

