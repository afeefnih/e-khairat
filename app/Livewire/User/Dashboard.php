<?php

namespace App\Livewire\User;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.user.dashboard',[
            'payments' => auth()->user()->payments()->with('paymentCategory')->latest()->get(),

        ]);
    }
}
