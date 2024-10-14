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
use Illuminate\Support\Facades\Storage;

class RasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $geotiff_path;

    public function __construct(Raster $file, $geotiff_path)
    {
        $this->file = $file;
        $this->geotiff_path = $geotiff_path;
    }

    public function handle()
    {   
        $stream = fopen($this->geotiff_path, 'r');
        
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
                        'contents' => $stream, // Use the file stream instead of file_get_contents
                        'filename' => basename($this->geotiff_path)
                    ],
                ],
                'verify' => false // Disable SSL verification
            ]);
            
            $respone_body = $response->getBody()->getContents();
            $respone_content = json_decode($respone_body, true); // Pass 'true' to get an associative array
            $this->file->band = $respone_content['bands'];
            $this->file->north = $respone_content['north'];
            $this->file->south = $respone_content['south'];
            $this->file->east = $respone_content['east'];
            $this->file->west = $respone_content['west'];
            $this->file->save();

            Storage::delete($this->geotiff_path);
        } catch (RequestException $e) {
            if (is_resource($stream)) {
                fclose($stream);
            }
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
