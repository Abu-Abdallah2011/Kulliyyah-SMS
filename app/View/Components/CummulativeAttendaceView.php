<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CummulativeAttendaceView extends Component
{

    public $totalattendancerecordsforterm;
    public $presentattendancerecordsforterm;
    public $absentattendancerecordsforterm;
    public $excusedattendancerecordsforterm;
    public $lateattendancerecordsforterm;
    public $percentageAttendanceForTerm;
    public $percentageAttendanceForSession;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $totalattendancerecordsforterm,
        $presentattendancerecordsforterm,
        $absentattendancerecordsforterm,
        $excusedattendancerecordsforterm,
        $lateattendancerecordsforterm,
        $percentageAttendanceForTerm,
        $percentageAttendanceForSession,
    )
    {
       $this->totalattendancerecordsforterm = $totalattendancerecordsforterm; 
       $this->presentattendancerecordsforterm = $presentattendancerecordsforterm; 
       $this->absentattendancerecordsforterm = $absentattendancerecordsforterm; 
       $this->excusedattendancerecordsforterm = $excusedattendancerecordsforterm; 
       $this->lateattendancerecordsforterm = $lateattendancerecordsforterm; 
       $this->percentageAttendanceForTerm = $percentageAttendanceForTerm; 
       $this->percentageAttendanceForSession = $percentageAttendanceForSession; 
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
      return view('components.Guardian-attendance-view');
    }
}
