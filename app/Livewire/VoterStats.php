<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class VoterStats extends Component
{
    public $voterCount = 0;

    protected $listeners = ['voterStatsUpdated' => 'updateStats'];

    public function mount()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->voterCount = User::whereHas('permission', function($query) {
            $query->where('can_vote', true);
        })->count();
    }

    public function render()
    {
        return view('livewire.voter-stats');
    }
}
