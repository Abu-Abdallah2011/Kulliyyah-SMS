<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TeacherView extends Component
{
    public $teachers;
    public $class;
    public $teacher;
    public $graduates;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($teachers, $class, $teacher, $graduates)
    {
        $this->teachers = $teachers;
        $this->class = $class;
        $this->teacher = $teacher;
        $this->graduates = $graduates;

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
