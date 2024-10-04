<?php

namespace App\Http\Controllers;

use App\Models\Shapefile;
use App\Jobs\ProcessFileJob;
use Illuminate\Http\Request;

class SpatialController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the file
        $request->validate(['file' => 'required|file']);

        // Store the file
        $path = $request->file('file')->store('uploads');

        // Create the file record
        $file = Shapefile::create([
            'file_path' => $path,
        ]);
        
        // Dispatch background job to process the file
        ProcessFileJob::dispatch($file);

        return back()->with('success', 'File uploaded and is being processed!');
    }

    public function getResult(Shapefile $file)
    {
        return response()->json($file);
    }
}
