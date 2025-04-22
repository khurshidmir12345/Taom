<?php

namespace App\Livewire\User\History;

use App\Models\FoodHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HistoryLivewire extends Component
{
    public $timeframe = 'all'; // Default to show all history

    public function render()
    {
        $userId = auth()->id();
        $query = FoodHistory::with(['food', 'food.category'])
            ->where('user_id', $userId);

        // Apply timeframe filter if needed
        if ($this->timeframe === 'week') {
            $query->where('date', '>=', now()->subDays(7));
        } elseif ($this->timeframe === 'month') {
            $query->where('date', '>=', now()->subDays(30));
        }

        // Get histories and group by date
        $histories = $query->orderByDesc('date')->get();
        $groupedHistories = $histories->groupBy('date');

        return view('livewire.user.history.history-livewire',[
            'groupedHistories' => $groupedHistories,
            'isEmpty' => $histories->isEmpty(),
        ]);
    }
}
