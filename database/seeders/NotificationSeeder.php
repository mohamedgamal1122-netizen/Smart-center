<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Ensure admin user exists
        $admin = User::where('email', 'admin@center.test')->first();
        if (!$admin) return;

        // مثال على إشعار تجريبي
        Notification::create([
            'user_id' => $admin->id,
            'title' => 'إشعار تجريبي',
            'message' => 'هذا إشعار تجريبي للنظام.',
            'type' => 'info',
            'related_url' => '/dashboard',
            'is_read' => false,
        ]);
    }
}
