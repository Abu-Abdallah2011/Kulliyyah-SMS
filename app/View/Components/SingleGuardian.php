<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SingleGuardian extends Component
{

    public $guardian;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($guardian)
    {
        $this->guardian = $guardian;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.single-guardian-view');
    }
}
