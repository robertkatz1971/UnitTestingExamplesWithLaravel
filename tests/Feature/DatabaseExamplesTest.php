<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseExampleTest extends TestCase
{

    use RefreshDatabase;

    public function testUseDatabaseHas()
    {   
        $payload = [
            'name' =>'Hammer',
            'price' => 3.50,
        ];

        $user = User::factory()->create();

        $this->actingAs($user)
            ->json('POST', route('products.store'), $payload)
            ->assertSuccessful();
        $this->assertDatabaseHas('products', $payload);  
        $this->assertDatabaseCount('products', 1);
    }

    public function testUseDatabaseSeeding() {

        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->json('GET', route('products.index'))
            ->assertOK()
            ->assertJsonCount(3)
            ->assertJsonStructure([
            '*' => ['id', 'name', 'price', 'created_at', 'updated_at'],
         ]);

         $this->assertDatabaseCount('products', 3);
         
    }
}
