<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class HomeServer extends Controller
{
    private $url;

    /**
     * HomeServer constructor.
     */
    public function __construct()
    {
        $this->url = config('webTest.url');
    }

    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     * @throws \JsonException
     */
    public function __invoke(Request $request)
    {
        //getting json data from Earthquakes API
        $response = Http::get($this->url);
        //check validate to success for third-party api request
        if ($response->successful()) {
            try {
                //json decode and get body
                $lists = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
                // getting data align with display in UI
                $listData = $this->assignLatAndLag($lists['features']);;
                return view('home', compact('listData'));
            } catch (Exception $e) {
                // if any error, logging into message with json exception message
                Log::error('something wrong in earthquakes api and '.$e->getMessage());
                abort(400);
            }
        }
        abort(404);
    }

    /**
     * create array from api data and align with ui data
     * @param $lists
     * @return array
     */
    private function assignLatAndLag($lists): array
    {
        $dataArray = [];
        foreach ($lists as $list) {
            $data = [
                'position' => [
                    'lng' => $list['geometry']['coordinates'][0],
                    'lat' => $list['geometry']['coordinates'][1]
                ],
                'location' => [
                    'title' => $list['properties']['title'],
                    'place' => $list['properties']['place'],
                    'type' => $list['properties']['type'],
                    'date_time' => Carbon::parse($list['properties']['time'])->format('d-M-Y h:i:s A'),
                ]
            ];
            $dataArray[] = $data;
        }
        return $dataArray;

    }
}
