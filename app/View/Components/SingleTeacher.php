<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SingleTeacher extends Component
{
    public $teacher;
    public $class;
    public $malams;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($teacher, $class, $malams)
    {
        $this->teacher = $teacher;
        $this->class = $class;
        $this->malams = $malams;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.single-teacher-view');
    }
}
