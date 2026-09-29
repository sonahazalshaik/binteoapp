<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = DB::table('users')->where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            $userData = (array) $user;
            // Remove ID to allow auto-increment or keep it if you want to preserve IDs?
            // User requested "credntials and each related elquoent relations". 
            // Preserving ID is CRITICAL to keep relations working (Video.user_id etc).
            
            DB::table('clients')->insert($userData);
        }

        // Delete moved users from users table
        DB::table('users')->where('role', '!=', 'admin')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $clients = DB::table('clients')->get();

        foreach ($clients as $client) {
            // Only insert if it doesn't exist in users
            if (!DB::table('users')->where('id', $client->id)->exists()) {
                DB::table('users')->insert((array) $client);
            }
        }

        // DB::table('clients')->truncate(); // Drop table will handle this
    }
};
