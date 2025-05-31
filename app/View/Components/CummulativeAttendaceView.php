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
    public $totalattendancerecordsforSession;
    public $presentattendancerecordsforSession;
    public $absentattendancerecordsforSession;
    public $excusedattendancerecordsforSession;
    public $lateattendancerecordsforSession;
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
        $totalattendancerecordsforSession,
        $presentattendancerecordsforSession,
        $absentattendancerecordsforSession,
        $excusedattendancerecordsforSession,
        $lateattendancerecordsforSession,
    )
    {
       $this->totalattendancerecordsforterm = $totalattendancerecordsforterm; 
       $this->presentattendancerecordsforterm = $presentattendancerecordsforterm; 
       $this->absentattendancerecordsforterm = $absentattendancerecordsforterm; 
       $this->excusedattendancerecordsforterm = $excusedattendancerecordsforterm; 
       $this->lateattendancerecordsforterm = $lateattendancerecordsforterm; 
       $this->percentageAttendanceForTerm = $percentageAttendanceForTerm; 
       $this->percentageAttendanceForSession = $percentageAttendanceForSession; 
       $this->totalattendancerecordsforSession = $totalattendancerecordsforSession; 
       $this->presentattendancerecordsforSession = $presentattendancerecordsforSession; 
       $this->absentattendancerecordsforSession = $absentattendancerecordsforSession; 
       $this->excusedattendancerecordsforSession = $excusedattendancerecordsforSession; 
       $this->lateattendancerecordsforSession = $lateattendancerecordsforSession; 
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
