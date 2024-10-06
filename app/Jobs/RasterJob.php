<?php
namespace App\Jobs;

use App\Models\Raster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct(Raster $file)
    {
        $this->file = $file;
    }

    public function handle()
    {   
        $file_name = $this->file->path;
        $file_path = asset("storage/{$file_name}");
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        
        $client = new Client();
        $api_url = 'http://127.0.0.1:5001/process-raster';
        
        try {
            $response = $client->post($api_url, [
                'multipart' => [
                    [
                        'name'     => 'raster_id', 
                        'contents' => $this->file->id
                    ],
                    [
                        'name'     => 'project_id', 
                        'contents' => $this->file->project_id
                    ],
                    [
                        'name'     => 'file', 
                        'contents' => file_get_contents($file_path, false, $context),  // Send the file
                        'filename' => basename($file_path)
                    ],
                ],
            ]);

            $respone_body = $response->getBody()->getContents();
            $respone_content = json_decode($respone_body, true); // Pass 'true' to get an associative array
            $this->file->band = $respone_content['band'];
            $this->file->north = $respone_content['north'];
            $this->file->south = $respone_content['south'];
            $this->file->east = $respone_content['east'];
            $this->file->west = $respone_content['west'];
            $this->file->save();
            // dd($respone_body);

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
