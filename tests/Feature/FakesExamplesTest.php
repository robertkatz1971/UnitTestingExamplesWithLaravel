<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Events\ProductShipped;
use Illuminate\Http\UploadedFile;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductController;
use App\Mail\ProductShipped as ShippedEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FakesExamplesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUseEventAndMailNotSentFakesDontSendMailWhenPriceUnder_50()
    {

        Event::fake();
        Mail::fake();

        $products = Product::factory()->count(1)->create([
            'price' => 59.99,
        ]);

        $pc = new ProductController();
        
        $pc->shipped($products->first());

        Event::assertDispatched(ProductShipped::class);
        Mail::assertNothingSent(ShippedEmail::class);

    }

    public function testUseEventAndMailSentFakesSendMailWhenPriceOver_50() {
        
        Event::fake();
        Mail::fake();

        $products = Product::factory()->count(1)->create([
            'price' => 60.01,
        ]);

        $pc = new ProductController();
        
        $pc->shipped($products->first());

        Event::assertDispatched(ProductShipped::class);
        Mail::assertSent(ShippedEmail::class);
    }

    public function testUseStorageFake()
    {
        Storage::fake('local');

        $fileName = 'productImage.jpg';

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('products.uploadImage', [ 'fileName' => $fileName]), [
            'image' => UploadedFile::fake()->image('sourceProductImage.jpg')
        ]);

        //assert get 200 response code
        $response->assertOk();

        // Assert the file was stored...
        Storage::disk('local')->assertExists($fileName);

        // Assert a file does not exist...
        Storage::disk('local')->assertMissing('missing.jpg');
    }
}
