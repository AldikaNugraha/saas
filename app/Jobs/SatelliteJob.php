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
use Log;

class SatelliteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;
    protected $is_delete;
    protected array $satellite_data;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;
    public $backoff = 10; // Wait 10 seconds before retrying
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    public function __construct(Raster $file, array $satellite_data = [], $is_delete = false )
    {
        $this->file = $file;
        $this->satellite_data = $satellite_data;
        $this->is_delete = $is_delete;
    }

    public function handle(): void
    {
        $api_url = env('FLASK_API_URL') . ($this->is_delete ? '/delete-satellite' : '/process-satellite');   
        $client = new Client();
        
        $promise = $client->postAsync($api_url, [
            'headers' => [
                'Authorization' => env('FLASK_API_TOKEN'),
            ],
            'multipart' => [
                [
                    'name' => 'raster_id', 
                    'contents' => $this->file->id
                ],
                [
                    'name' => 'project_id', 
                    'contents' => $this->file->project_id
                ],
                [
                    'name' => 'name', 
                    'contents' => $this->file->name
                ],
                [
                    'name' => 'is_delete',
                    'contents' => $this->is_delete
                ],
                [
                    'name' => 'data', 
                    'contents' => json_encode($this->satellite_data)
                ],
            ],
            'verify' => false,
        ]);

        $promise->then(
            function ($response) {
                if (!$this->is_delete) {
                    $response_body = $response->getBody()->getContents();
                    $response_content = json_decode($response_body, true)[0];
                    $this->file->band = $response_content['bands'];
                    $this->file->north = $response_content['north'];
                    $this->file->south = $response_content['south'];
                    $this->file->east = $response_content['east'];
                    $this->file->west = $response_content['west'];
                    $this->file->save();
                    Log::info("Raster data updated successfully for ID: {$this->file->id}");
                }
            },
            function (RequestException $e) {
                if ($e->hasResponse()) {
                    Log::error("Error response from API: " . $e->getResponse()->getBody()->getContents());
                } else {
                    Log::error("Error: " . $e->getMessage());
                }
                // Optionally fail the job
                $this->fail($e);
            }
        );
    
        // Optional: If you want to wait for the promise to resolve during testing or debugging
        $promise->wait();
        
        // try {
        //     $response = $this->client->post($api_url, [
        //         'headers' => [
        //             'Authorization' => env('FLASK_API_TOKEN')
        //         ],
        //         'multipart' => [
        //             [
        //                 'name'     => 'raster_id', 
        //                 'contents' => $this->file->id
        //             ],
        //             [
        //                 'name'     => 'project_id', 
        //                 'contents' => $this->file->project_id
        //             ],
        //             [
        //                 'name'     => 'name', 
        //                 'contents' => $this->file->name
        //             ],
        //             [
        //                 'name'     => 'is_delete', 
        //                 'contents' => $this->is_delete
        //             ],
        //             [
        //                 'name'     => 'data', 
        //                 'contents' => json_encode($this->satellite_data),
        //             ],
        //         ],
        //         'verify' => false // Disable SSL verification
        //     ]);
        //     if (!$this->is_delete) {
        //         $response_body = $response->getBody()->getContents();
        //         Log::info("Response received: " . $response_body);
        //     }

        // } catch (RequestException $e) {
        //     if ($e->hasResponse()) {
        //         $response = $e->getResponse();
        //         $responseBody = $response->getBody()->getContents();
        //         Log::error("Error response from API: " . $responseBody);
        //     } else {
        //         Log::error("Error: " . $e->getMessage());
        //     }
        //     // Optionally, mark the job as failed.
        //     $this->fail($e);
        // }
    }
}
