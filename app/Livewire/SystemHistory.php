<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class SystemHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom;
    public $dateTo;
    public $actionFilter = '';

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'actionFilter'];

    public function updating($field)
    {
        if (in_array($field, ['search', 'dateFrom', 'dateTo', 'actionFilter'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Activity::with('causer')->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHasMorph('causer', [\App\Models\User::class], function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhere('subject_type', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->actionFilter) {
            $query->where('event', $this->actionFilter)->orWhere('description', 'like', '%' . $this->actionFilter . '%');
        }

        $activities = $query->paginate(20);

        return view('livewire.system-history', [
            'activities' => $activities
        ])->layout('components.layouts.app');
    }
}
