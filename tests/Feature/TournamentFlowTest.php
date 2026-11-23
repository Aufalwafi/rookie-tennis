<?php

namespace Tests\Feature;

use App\Models\Tournament;
use App\Models\Participant;
use App\Models\TennisMatch;
use App\Models\User;
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
        $this->seed();
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/tournaments', [
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
        $admin = User::where('role', 'admin')->first();
        $tournament = Tournament::first();
        $id = $tournament->id;

        $response = $this->actingAs($admin)->delete("/tournaments/{$id}");
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('tournaments', ['id' => $id]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Selamat datang kembali');
        $response->assertSee('admin@rookietennis.com');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Satu akun, dua peran');
        $response->assertSee('Penyelenggara');
        $response->assertSee('Peserta');
    }

    public function test_user_can_login_and_logout(): void
    {
        $this->seed();

        $response = $this->post('/login', [
            'email' => 'admin@rookietennis.com',
            'password' => 'admin123',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_user_can_register_with_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'user',
            'phone' => '081299998888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => 'user',
        ]);
    }

    public function test_participant_self_registration(): void
    {
        $this->seed();
        $user = User::where('role', 'user')->first();
        $tournament = Tournament::first();

        // Ensure user is not already registered
        $tournament->participants()->where('name', $user->name)->delete();

        $response = $this->actingAs($user)->post("/tournaments/{$tournament->id}/participants/self-register", [
            'club' => 'Bandung Smashers',
            'city' => 'Bandung',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('participants', [
            'tournament_id' => $tournament->id,
            'name' => $user->name,
            'club' => 'Bandung Smashers',
        ]);
    }

    public function test_role_switch(): void
    {
        $this->seed();

        $responseAdmin = $this->post('/switch-role', ['role' => 'admin']);
        $responseAdmin->assertRedirect();
        $this->assertEquals('admin', auth()->user()->role);

        $responseUser = $this->post('/switch-role', ['role' => 'user']);
        $responseUser->assertRedirect();
        $this->assertEquals('user', auth()->user()->role);

        $responseGuest = $this->post('/switch-role', ['role' => 'guest']);
        $responseGuest->assertRedirect();
        $this->assertGuest();
    }
}
