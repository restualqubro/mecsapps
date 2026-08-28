<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use App\Filament\Resources\Services\ServiceDataResource;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = filament()->auth()->user();

        // Sesuaikan 'Nama Role' dengan role yang Anda maksud
        if ($user->hasRole('magang')) { 
            // Arahkan langsung ke halaman index resource tersebut
            return redirect()->to(ServiceDataResource::getUrl('index'));
        }

        // Untuk role lain (Admin, Owner, dll), arahkan ke Dashboard default
        return redirect()->intended(Filament::getUrl());
    }
}