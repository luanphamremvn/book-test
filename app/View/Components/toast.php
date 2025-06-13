<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class toast extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected string $type = 'success',
        protected string $message = ''
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toast', ['type' => $this->type, 'message' => $this->message]);
    }
}
