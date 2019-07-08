<?php

class Schedule {
    public $schedule_id;
    public $student_group_id;
    public $temp = array();
    public $days = array();

    public function __construct($args=[])  {
        $this->schedule_id      = isset($args[0]['schedule_id'])      ? $args[0]['schedule_id']         : '';
        $this->student_group_id = isset($args[0]['student_group_id']) ? $args[0]['student_group_id']    : '';
        foreach($args as $arg) {
            $this->temp[$arg['days_days_id']][$arg['blocks_blocks_id']] = $arg['subjects_subjects_Id'];
        }
        $this->setDays();
        $this->sortDays();
    }

    public function setDays() {
		
		$allDays = [1,2,3,4,5];
        foreach($this->temp as $id=>$day) {
            $arr['day_id'] = $id;
            $arr['day_data'] = $day;
            $this->days[] = new Day($arr);
			unset($allDays[$id-1]);
        }
        unset($this->temp);
		foreach($allDays as $day) {
			$arr['day_id'] = $day;
			$arr['day_data'] = array ();
			$this->days[] = new Day($arr);
		}
		
    }
    public function sortDays() {
        usort($this->days, function($a, $b)
        {
            return strcmp($a->day_id, $b->day_id);
        });
    }

}