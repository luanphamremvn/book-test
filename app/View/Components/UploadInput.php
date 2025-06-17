<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UploadInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(protected string $name)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.upload-input', ['name' => $this->name]);
    }
}
