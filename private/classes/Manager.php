<?php

class Manager {
    public $db;

    public $fillData;
    public $last_inserted_id;


    /*
    Metoda za dinamicno unosenje objekta u bazu koristeci PDO i prepared statements
    */
    public function create() {
        $columns = $this->columns;
        if($this->ai) { // u slucaju da tabela ima auto-increment id, on se sklanja iz columns niza (neophodno je da se pri definisanju novih klasa taj id stavi na pocetak niza)
            array_shift($columns);
        }

        /*
        Dinamicno kreiranje sql upita na osnovu podataka iz $table_name i $columns 
        Nakon ovoga, sql upit izgleda ovako:
        INSERT INTO tabela (kolona1,kolona2,kolona3,kolona4) VALUES (:kolona1,:kolona2,:kolona3,:kolona4);
        */
        $sql = "INSERT INTO {$this->table_name} (";
        $sql .= join(', ', array_values($columns));
        $sql .= ") VALUES (";
        foreach($columns as $column) {
            $sql .= ":{$column}, ";
        }
        $sql  = substr($sql,0,-2);
        $sql .= "); ";

        $st=$this->db->prepare($sql);

        /*
        Dinamicno bind-ovanje podataka zbog kojeg je neophodno da su 
        imena polja klasa ista kao i odgovarajuce kolone u tabeli
        jer smo iznad kao placeholder-e(:) stavili imena kolona
        */

        foreach($columns as $column) {
            // $this->$column dinamicki pristupa poljima klase. 
            //Ako je npr. jedna od vrednosti u columns nizu 'user_id', 
            //taj string se nalazi u varijabli $column pa se $this->$column pretvara u $this->user_id
            $st->bindParam(':'.$column, $this->$column); 
        }
        $st->execute();
        $this->last_inserted_id = $this->db->lastInsertId();
    }    

    public function update() {
        $columns = $this->columns;
        if($this->ai) { // u slucaju da tabela ima auto-increment id, on se sklanja iz columns niza (neophodno je da se pri definisanju novih klasa taj id stavi na pocetak niza)
            $idColumn = array_shift($columns);
        }

        /*
        Dinamicno kreiranje sql upita na osnovu podataka iz $table_name i $columns
        Nakon ovoga, sql upit izgleda ovako:
        INSERT INTO tabela (kolona1,kolona2,kolona3,kolona4) VALUES (:kolona1,:kolona2,:kolona3,:kolona4);
        */
        $sql = "UPDATE {$this->table_name} SET ";
        foreach ($columns as $column) {
            $sql .= " ".$column."=:".$column.", ";
        }
        $sql  = substr($sql,0,-2);
        $sql .= " WHERE ".$this->columns[0]."=:".$idColumn;
        $st=$this->db->prepare($sql);
        foreach ($columns as $column) {
            $st->bindParam(':'.$column,$this->$column);
        }
        $st->bindParam(':'.$idColumn,$this->$idColumn);
        $st->execute();
    }

    public function delete() {
        $idColumn = reset($this->columns);
        $sql = "DELETE FROM {$this->table_name} WHERE {$idColumn} = :id";
        echo $sql;
        echo $this->$idColumn;
        $st = $this->db->prepare($sql);
        $st->bindParam(':id', $this->$idColumn);
        $st->execute();
    }

    public function fillAditionalData($fields=[]) {
        if(empty($fields)) {
            $fields[] = reset($this->columns);
        }

        $sql = "SELECT * FROM {$this->table_name} WHERE ";

        for($i=0, $c=count($fields); $i < $c; $i++) {
            $sql .= $fields[$i]. " = :".$fields[$i];
            if($i!=$c-1) {
                $sql .= " AND ";
            }
        }
        $st = $this->db->prepare($sql);
        foreach($fields as $field) {
            $st->bindParam(':'.$field, $this->$field);
        }
        $st->execute();
        $result = $st->fetch();
        if($result) {
            $this->fillData = true;
            foreach($this->columns as $column) {
                $this->$column = $result->$column;
            }    
        } else $this->fillData = false;

    }

    public function getJoins() {
        $sql = "SELECT * FROM table_joins WHERE table_joins_id = {$this->joins_id}";
        $st = $this->db->prepare($sql);
        $st->execute();
        while($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['foreign_table_name']] = $row;
        }
        if(isset($result)) {
            return $result;
        }
    }

    public function joinData($args=[]) {
        $joins = array();
        if(empty($args)) {
            $joins = $this->joins;
        } else if(!array_diff($args, array_keys($this->joins))) {
            foreach($args as $arg) {
                $joins[] = $this->joins[$arg];
            }
        } else return false;
        $sql = "SELECT * FROM {$this->table_name} ";
        foreach($joins as $join) {
             $sql .= "JOIN {$join['foreign_table_name']} ON {$join['table_name']}.{$join['primary_table_column_name']} = {$join['foreign_table_name']}.{$join['foreign_table_column_name']} ";
        }
        $idColumn = reset($this->columns);
        $sql .= "WHERE ".$this->table_name.".".$idColumn. " = ".$this->$idColumn;
        $st = $this->db->prepare($sql);
        $st->execute();
        $this->data = $st->fetchAll();
    }

    public static function createObject($className,$id) {
        $db= Database::getInstance()->getConnection();
        $tableName = self::getClassTableName($className);
        $columns   = self::getClassColumns($className);
        $idColumn = reset($columns);
        $sql = "SELECT * FROM {$tableName} WHERE {$idColumn}=$id";
        $st = $db->prepare($sql);
        $st->execute();
        $row = $st->fetch();
        $result = new $className($row);
        return $result;
    }

    public static function getClassTableName($className) {
        $reflectionClass = new ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty('table_name');
        $reflectionProperty->setAccessible(true);
        $tableName = $reflectionProperty->getValue($reflectionClass->newInstanceWithoutConstructor());
        return $tableName;
    }

    public static function getClassColumns($className) {
        $reflectionClass = new ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty('columns');
        $reflectionProperty->setAccessible(true);
        $columns = $reflectionProperty->getValue($reflectionClass->newInstanceWithoutConstructor());
        return $columns;
    }

    //count

    static public function getAnnouncements() {
        $sql = "SELECT DISTINCT a.announcement_id,ad.sender,u.firstName,u.lastName,a.subject,a.body
          FROM announcement a
          JOIN announcement_data ad ON ad.announcement_id= a.announcement_id
          JOIN users u ON ad.sender=u.users_id
          WHERE a.approved=0";
        $st = self::$db->prepare($sql);
        $result = $st->execute();
        if(!$result) {
            exit("Database query failed.");
        }
        $objectArray = [];
        while($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $objectArray[] = new Announcement($row);
        }
        return $objectArray;
    }
}
