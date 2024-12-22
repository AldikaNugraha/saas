<?php

namespace App\Http\Responses;
 
use App\Filament\Resources\ProjectResource;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
 
class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->to(ProjectResource::getUrl('index'));
    }
}