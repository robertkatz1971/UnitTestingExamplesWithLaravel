<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getJacketRecommendation(Request $request) {

        $city = $request->input('city');

        $response = Http::withHeaders([
            'x-rapidapi-host' => 'weatherapi-com.p.rapidapi.com',
            'x-rapidapi-key' => '334980cd44msh83ff23cf9df4ca5p19e0d9jsn7b129bf7ca5d'
        ])->get('https://weatherapi-com.p.rapidapi.com/forecast.json', [
            'q' => $city,
            'days' => '1'
        ]);

        $tempF = json_decode($response->body())->current->temp_f;

        //dd($tempF);

        $suggestion = '';
        switch(true) {
            case ($tempF < 40):
                $suggestion =  'Wear Winter Jacket'; 
            break;
            case ($tempF < 50):
                $suggestion =  'Wear Regular Jacket'; 
            break;
            case ($tempF < 60):
                $suggestion =  'Wear a Sweatshirt'; 
            break;
            default: 
                $suggestion =  'Nothing Necessary'; 
        }
        
        return $suggestion;
    }
}
