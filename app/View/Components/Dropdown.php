<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected string $name,
        protected array|Collection $options,
        protected array $selected = [],
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown', ['name' => $this->name, 'options' => $this->options, 'selected' => $this->selected]);
    }
}
