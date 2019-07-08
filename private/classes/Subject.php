<?php

class Subject extends Manager {
    protected $table_name = "subjects";
    protected $columns  = ["subjects_id","name"];
    protected $joins_id = 1;
    protected $joins = array();
    public $subjects_id;
    public $name;
    public $db;
    public $groupYears = array();
    public $data;
    public $prosek;
    public $professors = array();

    public function __construct( $data) {
        $this->db           = Database::getInstance()->getConnection();
        $this->joins        = $this->getJoins();
        $this->subjects_id  = isset($data->subjects_id) ? $data->subjects_id : '';
        $this->name         = isset($data->name)        ? $data->name        : '';
        $this->tempData		= isset($data->semester)    ? $data->semester    : '';
    }

    public function getAverage() {
        $zbir = 0;
        $brojac = 0;
        foreach($this->data as $grade) {
            if($grade->grade_type==4) {
                $zbir += $grade->value;
                $brojac++;
            }
        }
        $this->prosek = $zbir/$brojac;
    }


    //where da se ubaci

    public function prepareObject() {
		foreach($this->tempData as $sem) {
			$s = new Semestar($sem);
			$this->semester[] = $s;
		}
		unset($this->tempData);
	}


}