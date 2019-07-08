<?php

class Teacher extends User {
    protected $table_name   = "users";
    protected $columns      = ['users_id','status','username','password','firstName','lastName'];
    public $schedule = array();
    public $joins_id = 2;
    public $joins = array();
    public $data;
    public $subjects = array();

    public function getSchedule() {
        $blocks = Mapper::getSch($this->users_id);
        foreach($blocks as $block) {
            $this->schedule[$block->days_days_id][$block->blocks_blocks_id]['name']           = $block->name;
            $this->schedule[$block->days_days_id][$block->blocks_blocks_id]['subjects_id']    = $block->subjects_subjects_id;
            $this->schedule[$block->days_days_id][$block->blocks_blocks_id]['group_id']       = $block->student_group_id;
            $this->schedule[$block->days_days_id][$block->blocks_blocks_id]['group_name']     = $block->group_year."/".$block->group_number;
        }
    }

    public function getTeachingSubjects($schedule) {
        $group = Mapper::getGroupBySchedule($schedule);
        foreach($this->schedule as $day) {
            foreach($day as $block) {
                if($block['group_id']==$group) {
                    $this->subjects[] = $block['subjects_id'];
                }
            }
        }
        $this->subjects = array_unique($this->subjects);
    }
}