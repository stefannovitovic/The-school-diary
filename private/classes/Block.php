<?php

class Block {
    public $time;
    public $block_id;
    public $subject_id;
    public $subject_name;
    public $day_id;
    
    public function __construct($args=[],$day=null) {
        $this->time       = isset($args['time'])       ? $args['time']         : '';
        $this->block_id   = isset($args['block_id'])   ? $args['block_id']         : '';
        $this->subject_id = isset($args['subject_id']) ? $args['subject_id']    : '';
        $this->day_id = $day;
        if(is_numeric($this->block_id)) {
            Mapper::fillBlockData($this);
        }
        $this->subject_id = $this->randomchar(3)."|".$this->subject_id;
    }

    public function fillData() {
        Mapper::fillBlockData($this);
    }
    public function showBlock() {
        echo "<td id='{$this->day_id}|{$this->block_id}' ondrop='drop(event,this.id)' ondragover='allowDrop(event)'>";
        if(!empty($this->subject_name)) {
            echo "<p draggable='true' ondragstart='drag(event)' id='{$this->subject_id}' onmouseover='prikazi(this.id)' onMouseOut='ukloni(this.id)'>{$this->subject_name}</p>";
        }
        echo "</td>";
    } // day, id i subject id su dovoljni za subjectblocks tabelu
    function randomchar($length) {

        for($i=0,$randomString=""; $i<$length; $i++) {
            $randomString.=chr(rand(65,90));
        }
        return $randomString;
}
}