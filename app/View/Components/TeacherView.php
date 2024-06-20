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
    public $totalpaid;
    public $totalfree;
    public $totalnotpaid;
    public $totalpart;
    public $percentagepaid;
    public $percentagefree;
    public $percentagenotpaid;
    public $percentagepart;
    public $presentpercentage;
    public $absentpercentage;
    public $excusedpercentage;
    public $latepercentage;
    public $presentpercentageforteachers;
    public $absentpercentageforteachers;
    public $excusedpercentageforteachers;
    public $latepercentageforteachers;
    public $percentageexcusedlate;
    public $percentageclosedearly;
    public $percentagelatecominandearlyclose;
    public $totalteachers;
    public $totalmaleteachers;
    public $totalfemaleteachers;
    public $totalstudents;
    public $totalmalestudents;
    public $totalfemalestudents;
    public $percentageclasspresent;
    public $percentageclasslate;
    public $percentageclassexcused;
    public $percentageclassabsent;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $teacher, 
        $class, 
        $teachers, 
        $graduates, 
        $session, 
        $totalpaid,
        $totalfree,
        $totalnotpaid,
        $totalpart,
        $percentagepaid,
        $percentagefree,
        $percentagenotpaid,
        $percentagepart,
        $presentpercentage,
        $absentpercentage,
        $excusedpercentage,
        $latepercentage,
        $presentpercentageforteachers,
        $absentpercentageforteachers,
        $excusedpercentageforteachers,
        $latepercentageforteachers,
        $percentageexcusedlate,
        $percentageclosedearly,
        $percentagelatecominandearlyclose,
        $totalteachers,
        $totalmaleteachers,
        $totalfemaleteachers,
        $totalstudents,
        $totalmalestudents,
        $totalfemalestudents,
        $percentageclasspresent,
        $percentageclasslate,
        $percentageclassexcused,
        $percentageclassabsent,        
        )
        
    {
        $this->teacher = $teacher;
        $this->class = $class;
        $this->teachers = $teachers;
        $this->graduates = $graduates;
        $this->session = $session;
        $this->totalpaid = $totalpaid;
        $this->totalfree = $totalfree;
        $this->totalnotpaid = $totalnotpaid;
        $this->totalpart = $totalpart;
        $this->percentagepaid = $percentagepaid;
        $this->percentagefree = $percentagefree;
        $this->percentagenotpaid = $percentagenotpaid;
        $this->percentagepart = $percentagepart;
        $this->presentpercentage = $presentpercentage;
        $this->absentpercentage = $absentpercentage;
        $this->excusedpercentage = $excusedpercentage;
        $this->latepercentage = $latepercentage;
        $this->presentpercentageforteachers = $presentpercentageforteachers;
        $this->absentpercentageforteachers = $absentpercentageforteachers;
        $this->excusedpercentageforteachers =$excusedpercentageforteachers ;
        $this->latepercentageforteachers = $latepercentageforteachers;
        $this->percentageexcusedlate = $percentageexcusedlate;
        $this->percentageclosedearly = $percentageclosedearly;
        $this->percentagelatecominandearlyclose = $percentagelatecominandearlyclose;
        $this->totalteachers = $totalteachers;
        $this->totalmaleteachers = $totalmaleteachers;
        $this->totalfemaleteachers = $totalfemaleteachers;
        $this->totalstudents = $totalstudents;
        $this->totalmalestudents = $totalmalestudents;
        $this->totalfemalestudents = $totalfemalestudents;
        $this->percentageclasspresent = $percentageclasspresent;
        $this->percentageclasslate = $percentageclasslate;
        $this->percentageclassexcused = $percentageclassexcused;
        $this->percentageclassabsent = $percentageclassabsent;

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
