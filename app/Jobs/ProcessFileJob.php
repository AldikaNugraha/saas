<?php
namespace App\Jobs;

use App\Models\Vector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProcessFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct(Vector $file)
    {
        $this->file = $file;
    }

    public function handle()
    {   
        $geojson = $this->file->geojson;

        $client = new Client();
        $api_url = 'http://127.0.0.1:5001/process-geojson';
        try {
            // Send a POST request with the GeoJSON data and the correct headers
            $response = $client->post($api_url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $geojson,  // Ensure the data is sent as JSON
            ]);

            // Get the response body
            $respone_body = $response->getBody()->getContents();
            // Decode the JSON response to a PHP associative array
            $respone_content = json_decode($respone_body, true); // Pass 'true' to get an associative array
            $this->file->area = $respone_content['area'];
            $this->file->type = $respone_content['type'];
            $this->file->num_features = $respone_content['num_features'];
            $this->file->save();

        } catch (RequestException $e) {
            // Catch the exception and print the full response for debugging
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                dd( "Error: " . $responseBody);
            } else {
                dd( "Error: " . $e->getMessage());
            }
        }
    }

}
