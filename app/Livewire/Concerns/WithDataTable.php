<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;
use Livewire\WithPagination;

trait WithDataTable
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 20;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->resetPage();

        $this->search = '';

        $this->perPage = 20;

        $this->sortField = 'created_at';

        $this->sortDirection = 'desc';

        if (property_exists($this, 'status')) {
            $this->status = '';
        }
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {

            $this->sortDirection =
                $this->sortDirection === 'asc'
                    ? 'desc'
                    : 'asc';

            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {

            $this->selected = $this->messages()
                ->pluck('id')
                ->toArray();

        } else {

            $this->selected = [];

        }
    }

    public function updatedSelected(): void
    {
        $this->selectAll =
            count($this->selected) ===
            $this->messages()->count();
    }
}