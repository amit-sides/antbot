<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;

trait WithValidation
{
    public Grid $grid;

    protected $rules = [
        'grid.name' => 'required|string|max:12',
        'grid.description' => 'sometimes|string',
        'grid.grid_json' => 'required|json'
    ];

    protected $validationAttributes = [
        'grid_json' => 'Grid configuration'
    ];

    protected function renderData()
    {
        return [
            'grid_modes' => config('antbot.grid_modes')
        ];
    }
}
