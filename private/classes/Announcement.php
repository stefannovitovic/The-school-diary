<?php

class Announcement extends AnnouncementHandler {
    public $announcement_id;
    public $subject;
    public $body;
    public $approved;
    public $sender;
    public $target;
    public $timesent;
    public $data = array();

    public function __construct(StdClass $args) {
        $this->db = Database::getInstance()->getConnection();
        $this->announcement_id      = isset($args->announcement_id) ? $args->announcement_id : '';
        $this->subject              = isset($args->subject)  ? $args->subject : '';
        $this->body                 = isset($args->body)     ? $args->body   : '';
        $this->approved             = isset($args->approved) ? $args->approved    : 0;
        $this->sender               = isset($args->sender)   ? $args->sender : $_SESSION['id'];
        $this->firstName            = isset($args->firstName)? $args->firstName : '';
        $this->lastName             = isset($args->lastName) ? $args->lastName : '';
        $this->timesent             = isset($args->timesent) ? $args->timesent: time();
        if($this->joins_id != 0) {
            $this->joins = $this->getJoins();
        }
    }

    public function setTarget() {
        if(empty($this->target)) {
            $this->target = unserialize($_SESSION['parents']);
        } else return false;
    }

    public function getTarget() {
        $this->joinData();
        foreach($this->data as $target) {
            $this->target[] = $target->target;
        }
    }
}