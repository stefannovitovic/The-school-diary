<?php

class Day {
    public $day_id;
    public $name;
    public $temp = array();
    public $blocks= array();
    public function __construct($args) {
        $this->day_id = isset($args['day_id']) ? $args['day_id'] : '';
        $this->temp   = isset($args['day_data'])   ? $args['day_data']   : '';
        Mapper::fillDayData($this);
        $this->setBlocks($this->temp);
        $this->sortBlocks();
    }

    public function setBlocks($args=[]) {
		if(!empty($args)) {
			foreach($args as $block=>$subject) {
            $arr['block_id'] = $block;
            $arr['subject_id'] = $subject;
            $this->blocks[] = new Block($arr,$this->day_id);
			} 
		} else {
			$arr['time'] = 27000;
			$arr['block_id'] = $this->setBlockId(27000);
            $this->blocks[] = new Block($arr,$this->day_id);
		}
        unset($this->temp);
		
    }

    public function sortBlocks() {
        foreach($this->blocks as $block) {
            $temp[] = $block->time;
        }
        $emptyBlocks = array_merge(array_diff($temp, BLOCKTIMES), array_diff(BLOCKTIMES, $temp));
        foreach($emptyBlocks as $eblock) {
            $arr['time']=$eblock;
            $arr['block_id'] = $this->setBlockId($eblock);
            $this->blocks[] = new Block($arr,$this->day_id);
        }
        usort($this->blocks, function($a, $b)
        {
            return strcmp($a->time, $b->time);
        });
    }

    public function showBlock() {
        // foreach($blocks as $block)
        // echo "<td id='{$this->day}|{$this->id}' ondrop='drop(event)' ondragover='allowDrop(event)'>";
        // if(isset($this->subject)) {
        //     echo "<p draggable='true' ondragstart='drag(event)' id='{$this->subject->id}' >{$this->subject->name}</p>";
        // }
        // echo "</td>";
    } // day, id i subject id su dovoljni za subjectblocks tabelu

    public function setBlockId($time) {

        switch ($time) {
            case 27000:
                $id=1;
                break;
            case 30000:
                $id=2;
                break;
            case 33600:
                $id=3;
                break;
            case 36600:
                $id=4;
                break;
            case 39600:
                $id=5;
                break;
            case 42600:
                $id=6;
        }

        return $id;
    }

}