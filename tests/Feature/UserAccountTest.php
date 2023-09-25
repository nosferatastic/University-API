<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Registers a user, then logs in as them, and verifies that they exist, 
     * can be retrieved via Bearer token, and have the submitted details (name).
     *
     * @return void
     */
    public function test_user_creation()
    {
        //User data
        $user = [
            'name' => "Test User",
            'email' => 'test@test.com',
            'password' => 'password123!'
        ];

        //Make a POST request to the /api/register endpoint with the user data
        $response = $this->json('POST', '/api/user/register', $user);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com'
        ]);
    }

    public function test_user_login_and_auth()
    {
        //Create a user in the database
        $user = \App\Models\User::factory()->create();
        //Above will have created user, so we can log in as them
        $response = $this->json('POST', '/api/user/login', [
            'email' => $user->email,
            'password' => "secret"
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);

        $token = $response->json()['token'];

        //Now we include token, expect 200
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
                        ->json('GET', '/api/user');
        $response->assertStatus(200);
        $response->assertJson(['user' => ['name' => $user->name]]);
    }
}
