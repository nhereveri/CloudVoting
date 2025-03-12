<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class SupervisorStats extends Component
{
    public $supervisorCount = 0;

    protected $listeners = ['supervisorStatsUpdated' => 'updateStats'];

    public function mount()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->supervisorCount = User::whereHas('permission', function($query) {
            $query
                ->where('is_supervisor', true)
                ->where('is_admin', false);
        })->count();
    }

    public function render()
    {
        return view('livewire.supervisor-stats');
    }
}
