<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_registrations') && Schema::hasColumn('event_registrations', 'ticket_type_id')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->dropConstrainedForeignId('ticket_type_id');
            });
        }

        Schema::dropIfExists('event_speakers');
        Schema::dropIfExists('event_ticket_types');

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'is_paid')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('is_paid');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events') && ! Schema::hasColumn('events', 'is_paid')) {
            Schema::table('events', function (Blueprint $table) {
                $table->boolean('is_paid')->default(false)->after('is_jeudi_opportunite');
            });
        }

        if (! Schema::hasTable('event_ticket_types')) {
            Schema::create('event_ticket_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->unsignedInteger('quantity')->default(0);
                $table->unsignedInteger('sold')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->timestamps();
                $table->index(['event_id', 'is_active']);
            });
        }

        if (Schema::hasTable('event_registrations') && ! Schema::hasColumn('event_registrations', 'ticket_type_id')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->foreignId('ticket_type_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('event_ticket_types');
            });
        }

        if (! Schema::hasTable('event_speakers')) {
            Schema::create('event_speakers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
                $table->string('name');
                $table->string('role')->nullable();
                $table->text('bio')->nullable();
                $table->string('photo')->nullable();
                $table->string('company')->nullable();
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->timestamps();
            });
        }
    }
};
