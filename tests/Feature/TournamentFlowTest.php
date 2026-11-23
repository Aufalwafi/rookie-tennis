<?php

namespace Tests\Feature;

use App\Models\Tournament;
use App\Models\Participant;
use App\Models\TennisMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_tournament_index_page_loads_with_seeded_data(): void
    {
        $this->seed();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Rookie Tennis');
    }

    public function test_tournament_report_page_loads(): void
    {
        $this->seed();
        $tournament = Tournament::first();
        $response = $this->get("/tournaments/{$tournament->id}/report");
        $response->assertStatus(200);
        $response->assertSee($tournament->name);
    }

    public function test_tournament_creation(): void
    {
        $response = $this->post('/tournaments', [
            'name' => 'Surabaya Masters 2026',
            'category' => 'mens_singles',
            'surface' => 'Hard Court',
            'format' => 'knockout',
            'match_rule' => 'best_of_3',
            'courts' => 'Court 1, Court 2',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tournaments', ['name' => 'Surabaya Masters 2026']);
    }

    public function test_tournament_deletion(): void
    {
        $this->seed();
        $tournament = Tournament::first();
        $id = $tournament->id;

        $response = $this->delete("/tournaments/{$id}");
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('tournaments', ['id' => $id]);
    }
}
