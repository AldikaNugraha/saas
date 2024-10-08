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

class GeojsonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $geojson;

    public function __construct(Vector $file, $geojson)
    {
        $this->file = $file;
        $this->geojson = $geojson;
    }

    public function handle()
    {   
        $data = [
            'geojson' => $this->geojson,
            'vector_id' => $this->file->id, 
            'project_id' => $this->file->categorical->project->id 
        ];

        $client = new Client();
        $api_url = 'http://127.0.0.1:5001/process-geojson';
        try {
            $response = $client->post($api_url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $data,  // Ensure the data is sent as JSON
            ]);

            // Get the response body
            $respone_body = $response->getBody()->getContents();
            $respone_content = json_decode($respone_body, true);
            $this->file->area = $respone_content['area'];
            $this->file->type = $respone_content['type'];
            $this->file->num_features = $respone_content['num_features'];
            $this->file->save();

        } catch (RequestException $e) {
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
