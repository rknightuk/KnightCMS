<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            'name' => 'Robb',
            'email' => 'desk@rknight.me',
            'password' => Hash::make(env('ROOT_PASSWORD')),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
