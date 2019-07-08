<?php
    require ("../../private/initialize.php");
    
    if(isset($_POST['inputVal'])){
            $db = Database::getInstance()->getConnection();
            $username = $_POST['inputVal'];
            $sql = "SELECT username FROM users";
            $st = $db->prepare($sql);
            $result = $st->execute();                
            $users = [];
            while($data = $row = $st->fetch(PDO::FETCH_ASSOC)){
                $users[] = $row['username'];
            }

            if(!empty($username)){
                foreach($users as $key => $value){
                    if(stripos($value, $username) !== false){
                        echo "<p class='user'>" . $value ."</p>";
                    } 
                }
            }
        }
        