<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EditCurriculum extends Component
{

    public $curriculum;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($curriculum)
    {
        $this->curriculum = $curriculum;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.edit-curriculum-modal');
    }
}
