<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use Filament\Resources\Pages\Page;

class ViewVectorProperties extends Page
{
    protected static string $resource = VectorResource::class;

    protected static string $view = 'filament.resources.vector-resource.pages.view-vector-properties';
}
