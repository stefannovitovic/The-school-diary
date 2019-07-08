<?php

class Student extends StudentHandler {
    public $student_id;
    public $name;
    public $lastName;
    public $email;
    public $group_id;
    public $student_JMBG;
    public $parent_id;
    public $grades = array();
    public $tempGrades = array();

    public function __construct($args=[]) {
            $this->db           = Database::getInstance()->getConnection();
            $this->student_id   = isset($args['students_id']) 	? $args['students_id'] 	: '';
            $this->name         = isset($args['name']) 			? $args['name'] 		: '';
            $this->lastName     = isset($args['lastName']) 		? $args['lastName']    	: '';
            $this->email        = isset($args['email']) 		? $args['email']        : '';
            $this->group_id     = isset($args['group_id']) 		? $args['group_id']    	: '';
            $this->student_JMBG         = isset($args['student_JMBG']) ? $args['student_JMBG']: '';
            $this->parent_id    = isset($args['parent']) 		? $args['parent']    	: '';
			$this->tempGrades	= isset($args['subjects']) 		? $args['subjects']    	: '';
            if(isset($args['student_id'])) {
                $this->student_id = $args['student_id'];
            }
    }

    public function fillAllGrades() {
        $this->prepareSubjects();
        $this->prepareSemestar();
        foreach($this->grades as $subject) {
            foreach($subject->semestar as $sem)
            $sem->grades[] = Mapper::findGrades($this->student_id,$subject->subjects_id,$sem->semestar_id);
        }
    }

    protected function prepareSubjects() {
        $subjects = Mapper::selectAllItems('subjects');
        foreach ($subjects as $subject) {
            $s = (array) $subject;
            $this->grades[] = new Subject($s);
        }
        //$this->grades = Mapper::selectAllItems('subjects');
    }

    protected function prepareSemestar() {
        foreach($this->grades as $subject) {
            $subject->semestar = Mapper::selectAllItems('semestar');
        }
    }

    public function setGrades() {
        foreach ($this->tempGrades as $subject) {
           
            $subject = new Subject((object) $subject); 
            $subject->prepareObject(); 
            $this->grades[]=$subject;
        }
        unset($this->tempGrades);
        $allsubjects = Mapper::getSubs($this->group_id);
        $currSubjects = array(); 
        foreach($this->grades as $subject) {
            $currSubjects[] = $subject->subjects_id;
        }
        $missingSubjects = array_diff($allsubjects, $currSubjects); 
        foreach($missingSubjects as $sub) {
            $args['subjects_id'] = $sub; 
            $this->grades[] = new Subject((object) $args); 
        }
        $this->sortGrades();
    }

	public function fillEmptyData() {
		if(empty($this->name) && empty($this->lastName)) {
            $args = Mapper::getSD($this->student_id);
            $this->name = $args['name'];
            $this->lastName = $args['lastName'];
            $this->grades = Mapper::selectAllSub();
        }
    }
    
    protected function sortGrades() {
        usort($this->grades, function($a, $b)
        {
            return $a->subjects_id > $b->subjects_id;
        });
    }

}