<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TeacherView extends Component
{
    public $allteachers;
    public $teachers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($teachers, $allteachers = null)
    {
        $this->teachers = $teachers;
        $this->allteachers = $allteachers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.teacher-view');
    }
}
