<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('mens_singles'); // mens_singles, womens_singles, mens_doubles, womens_doubles, mixed_doubles
            $table->string('surface')->default('Hard Court'); // Hard Court, Clay, Grass, Carpet
            $table->string('format')->default('group_knockout'); // group_knockout, knockout, round_robin
            $table->string('match_rule')->default('best_of_3'); // best_of_3, best_of_1, pro_set_8, super_tiebreak
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->json('courts')->nullable();
            $table->string('status')->default('registration'); // registration, in_progress, completed
            $table->integer('groups_count')->default(2);
            $table->integer('advance_per_group')->default(2);
            $table->boolean('has_third_place')->default(true);
            $table->integer('points_win')->default(2);
            $table->integer('points_loss')->default(0);
            $table->timestamps();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('partner_name')->nullable();
            $table->integer('seed')->nullable();
            $table->string('club')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });

        Schema::create('tournament_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('group_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('tennis_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tournament_group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('stage')->default('group'); // group, quarterfinal, semifinal, final, third_place
            $table->integer('round')->default(1);
            $table->integer('order')->default(1);
            $table->unsignedBigInteger('player1_id')->nullable();
            $table->unsignedBigInteger('player2_id')->nullable();
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('court')->nullable();
            $table->date('match_date')->nullable();
            $table->string('match_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, live, completed, walkover
            $table->json('sets')->nullable();
            $table->json('live_score')->nullable();
            $table->unsignedBigInteger('next_match_id')->nullable();
            $table->integer('next_match_slot')->nullable(); // 1 or 2
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tennis_matches');
        Schema::dropIfExists('group_participant');
        Schema::dropIfExists('tournament_groups');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('tournaments');
    }
};
