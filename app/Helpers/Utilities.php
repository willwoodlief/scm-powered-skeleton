<?php

namespace App\Helpers;

use App\Exceptions\UserException;
use App\Models\User;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use NumberFormatter;

class Utilities {

    static function markUnusedVar($arg, ...$arr) {
        //nothing done here, function prevents some warning notices for unused
    }

    static function format_phone_number($number) : string  {
        if (empty($number)) {return '';}
        $area_code = substr($number, 0, 3);
        $prefix = substr($number, 3, 3);
        $line_number = substr($number, 6, 4);
        return '(' . $area_code . ') ' . $prefix . '-' . $line_number;
    }

    /**
     * @param $date
     * @return string
     * @throws \Exception
     */
    static function formatDate($date) : string  {
        if (empty($date)) {return '';}
        $date = new DateTime($date);
        $formattedDate = $date->format('F j, Y');
        return $formattedDate;
    }

    static function formatMoney(null|string|float $amount) :string  {
        $fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
        return $fmt->formatCurrency($amount, 'USD');
    }

    /**
     * @throws GuzzleException
     */
    static function getLocalWeather($city) {
        $apiKey= config('scm.open_weather_map_key');

        // Get current weather data

        $client = new Client();
        $request_weather = $client->get(
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'imperial'
                ]
            ]
        );

        $weatherData = json_decode($request_weather->getBody(), true);



        $request_forecast = $client->get(
            'https://api.openweathermap.org/data/2.5/forecast',
            [
                'query' => [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'imperial'
                ]
            ]
        );
        $forecastData = json_decode($request_forecast->getBody(), true);



        // Format the data into a single array
        $data = array(
            'city' => $weatherData['name'],
            'description' => $weatherData['weather'][0]['description'],
            'temperature' => $weatherData['main']['temp'],
            'humidity' => $weatherData['main']['humidity'],
            'wind_speed' => $weatherData['wind']['speed'],
            'icon' => $weatherData['weather'][0]['icon'],
            'forecast' => []
        );

        // Extract the forecast data
        foreach ($forecastData['list'] as $forecast) {
            $forecastDate = date('Y-m-d', strtotime($forecast['dt_txt']));
            if (!isset($data['forecast'][$forecastDate])) {
                $data['forecast'][$forecastDate] = array(
                    'date' => $forecastDate,
                    'temperature' => [],
                    'icon' => $forecast['weather'][0]['icon']
                );
            }
            $data['forecast'][$forecastDate]['temperature'][] = $forecast['main']['temp'];
        }

        // Calculate the average temperature for each forecast day
        foreach ($data['forecast'] as &$forecast) {
            $forecast['temperature'] = round(array_sum($forecast['temperature']) / count($forecast['temperature']));
        }

        return $data;
    }

    public static function to_int_array($given) : array {
        if (empty($given)) {return [];}
        $foremen_array = [];
        foreach ($given as $id_raw ) {
            $da_id = (int)$id_raw;
            if ($da_id <=0) {continue;}
            $foremen_array[] = $da_id;
        }
        return $foremen_array;
    }

    public static function get_logged_user() : ?User {
        /**
         * @var User $user
         */
        $user = Auth::getUser();

        if (empty($user) || empty($user->id)) {
            throw new UserException("User is not logged in");
        }
        return $user;
    }

    public static function replaceArrayValue($old_value,$new_value,$originalArray) {
        $newArray = array_map(function ($value) use($old_value,$new_value) {
            return $value === $old_value ? $new_value : $value;
        }, $originalArray);

        return $newArray;
    }

    public static function urlConvertedToAbsoluteStoragePath(string $url) {
        $root_of_url = URL::to('/');
        $base = str_replace($root_of_url,'',$url);
        $partial =  trim($base,'/');
        return storage_path('app/'.$partial);
    }
}
