<?php

class Semestar {
    public $semestar_id;
    public $semestar_name;
    public $grades = array();
    public $tempGrades = array();

    public function __construct($args) {
        $this->semestar_id   = isset($args['semesterId'])   ? $args['semesterId']   : '';
        $this->semestar_name = isset($args['name']) 		? $args['name'] 		: '';
        $this->tempGrades = $args['grades'];
        $this->setGrades();
    }

    protected function setGrades() {
        foreach($this->tempGrades as $gradeType => $grades) {
            foreach($grades as $g) {
                $temp['grade_type'] = $gradeType;
                $temp['value']      = $g;
                $this->grades[] = new Grade($temp);
            }
            
        }
        unset($this->tempGrades);
    }
}