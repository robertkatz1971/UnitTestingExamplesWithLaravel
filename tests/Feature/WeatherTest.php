<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherTest extends TestCase
{
    public function testDemoIssueWithDependencyOnExternalAPI() 
    {
        //route is tightly coupled with a 3rd party API
        $url = route('weather.getJacketRecommendation') . '?city=Stockholm';

        $response = $this->get($url);

        //results from external are variable (temp changes every day and that effects results)
        $response->assertStatus(200)
        ->assertSee('Wear Regular Jacket');

    }

    public function testWearRegularJacketSuggestion()
    {
        //open source URL: https://rapidapi.com/weatherapi/api/weatherapi-com/
        //documentation: https://laravel.com/docs/7.x/http-client#making-requests 

        //using fake to use our response as substitute for when external api is called.
        Http::fake([
            'https://weatherapi-com.p.rapidapi.com/*' => Http::response(['current' => ['temp_f' => 49]], 200, ['Headers'])
        ]);

        $url = route('weather.getJacketRecommendation') . '?city=Stockholm';

        $response = $this->get($url);

        $response->assertStatus(200)
            ->assertSee('Wear Regular Jacket');
    }

    public function testAllJacketSuggestions() {

        $url = route('weather.getJacketRecommendation') . '?city=Agana';

        Http::fake([
            'https://weatherapi-com.p.rapidapi.com/*' 
                => Http::sequence()
                    ->push(['current' => ['temp_f' => 39]], 200, ['Headers'])
                    ->push(['current' => ['temp_f' => 49]], 200, ['Headers'])
                    ->push(['current' => ['temp_f' => 59]], 200, ['Headers'])
                    ->push(['current' => ['temp_f' => 60]], 200, ['Headers'])
        ]);
 
        $response = $this->get($url);
        $response->assertStatus(200)
            ->assertSee('Wear Winter Jacket');

        $response = $this->get($url);
        $response->assertStatus(200)
            ->assertSee('Wear Regular Jacket');

        $response = $this->get($url);
        $response->assertStatus(200)
            ->assertSee('Wear a Sweatshirt');
        
        $response = $this->get($url);
        $response->assertStatus(200)
            ->assertSee('No Jacket Necessary');
    }
    
}
