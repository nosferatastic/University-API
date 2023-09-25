<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UniversityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests University GET
     *
     * @return void
     */
    public function test_university_get()
    {
        $this->seed();
        $response = $this->json('GET', '/api/university/8');

        $response->assertStatus(200);
        $response->assertJson(['university' => ['name' => "University College of Osteopathy (The)"]]);
    }

    public function test_university_search()
    {
        $this->seed();
        //Search
        $response = $this->json('GET', '/api/search', [
            'search_term' => 'university of'
        ]);

        //Verify that results came in
        $response->assertStatus(200);
        $response->assertJsonStructure(['results']);
        //Verify that it returns universities in the desired format
        $response->assertJsonStructure([
            'message', 
            'results' => [
                '*' => [
                    'id',
                    'name',
                    'logo_path',
                    'reviews_count',
                    'rating',
                    'saved_as_favourite'
                ]
            ]
        ]);

        //Search for a specific element
        $response = $this->json('GET', '/api/search', [
            'search_term' => 'University of Birmingham'
        ]);

        //Verify that results came in
        $response->assertStatus(200);
        $response->assertJsonStructure(['results']);
        //Verify that it returns the desired university
        $response->assertJson([
            'results' => [
                [
                    'name' => 'The University of Birmingham',
                ]
            ]
        ]);
    }
}
