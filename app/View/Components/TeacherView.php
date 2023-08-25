<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TeacherView extends Component
{
    public $teacher;
    public $class;
    public $teachers;
    public $graduates;
    public $session;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($teacher, $class, $teachers, $graduates, $session)
    {
        $this->teacher = $teacher;
        $this->class = $class;
        $this->teachers = $teachers;
        $this->graduates = $graduates;
        $this->session = $session;

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
