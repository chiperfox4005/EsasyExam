<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run Migration
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        Schema::create('users', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic User
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            // Email boleh kosong untuk siswa
            $table->string('email')
                ->nullable()
                ->unique();

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->string('password');


            /*
            |--------------------------------------------------------------------------
            | Role System
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'role',
                [
                    'admin',
                    'guru',
                    'siswa'
                ]
            )->default('siswa');


            /*
            |--------------------------------------------------------------------------
            | Additional Profile
            |--------------------------------------------------------------------------
            */

            // Login siswa
            $table->string('nisn')
                ->nullable()
                ->unique();

            // Login guru
            $table->string('nip')
                ->nullable()
                ->unique();

            $table->string('phone')
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->string('avatar')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | User Activity
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_online')
                ->default(false);

            $table->timestamp('last_seen')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Auth
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | PASSWORD RESET
        |--------------------------------------------------------------------------
        */

        Schema::create(
            'password_reset_tokens',
            function (Blueprint $table) {

                $table->string('email')
                    ->primary();

                $table->string('token');

                $table->timestamp('created_at')
                    ->nullable();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SESSION
        |--------------------------------------------------------------------------
        */

        Schema::create(
            'sessions',
            function (Blueprint $table) {

                $table->string('id')
                    ->primary();

                $table->foreignId('user_id')
                    ->nullable()
                    ->index();

                $table->string(
                    'ip_address',
                    45
                )->nullable();

                $table->text('user_agent')
                    ->nullable();

                $table->longText('payload');

                $table->integer('last_activity')
                    ->index();
            }
        );
    }


    /**
     * Reverse Migration
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');

        Schema::dropIfExists(
            'password_reset_tokens'
        );

        Schema::dropIfExists('users');
    }
};
