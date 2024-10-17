<?php

namespace Tests\Feature\Livewire;

use App\Livewire\NumericalChart1;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class NumericalChart1Test extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(NumericalChart1::class)
            ->assertStatus(200);
    }
}
