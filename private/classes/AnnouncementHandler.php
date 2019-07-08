<?php

class AnnouncementHandler extends Manager {
    protected $table_name = "announcement";
    protected $columns  = ["announcement_id","subject","body","approved","timesent"];
    protected $joins_id = 0;
    protected $joins = array();
    protected $ai = true;

    public function send() {
        $this->create();
        $sql = "INSERT INTO announcement_data(announcement_id,sender,target) VALUES (:sn,:s,:t)";
        $st = $this->db->prepare($sql);
        $st->bindParam(':sn',$this->last_inserted_id);
        $st->bindParam(':s',$this->sender);
        foreach ($this->target as $target) {
            $st->bindParam(':t',$target);
            $st->execute();
        }
    }

    public function getParents() {
        $sql = "SELECT target
                FROM announcement_data
                WHERE announcement_id = :aid";

        $st = $this->db->prepare($sql);
        $st->bindParam(':aid',$this->announcement_id);
        $st->execute();
        $res = array();
        while($row = $st->fetchColumn()) {
            $res[] = $row;
        }
        $this->target = $res;
    }
}