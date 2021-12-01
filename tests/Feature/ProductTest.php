<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImplicateRouteModelBinding() {

        $user = User::factory()->create();      

        $this->seed();
        $id = Product::first()->id;
        $response = $this->actingAs($user)
                         ->get('/product/' . $id);

        $response
            ->assertSeeText('id: ' . $id)
            ->assertStatus(200);
    }
}
