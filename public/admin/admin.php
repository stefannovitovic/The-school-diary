<a href="users.php">User Data</a><br>
<a href="groups.php">Group Data</a><br>
<a href="subjects.php">Subjects Data</a><br>
<a href="schedule.php">Schedule</a><br>
<a href="notifications.php">Notifications</a><br>

<?php

include('../private/initialize.php');
Mapper::set_database();
$students = Mapper::checkStudentData();
$group    = Mapper::checkGroupData();
$notifications = Mapper::getNotifications();

if(!empty ($students)) {
  echo "Missing data from students:<br>";
  foreach ($students as $student) {
     print_r($student);
  }
}
if(!empty ($group)) {
  echo "Group doesn't have a teacher:<br>";
  foreach ($group as $g) {
     print_r($g);
  }
}
if(!empty($notifications)) {
  echo "You have notification to approve <a href='ntf.php'>See more</a>";
  
}

// adminu stoji poruka da treba da odobri, adminov odgovor aproved/not salje se ucitelju

// kad admin odobri u notifications_parents tabeli povlacis sve roditelje tog odeljenja i dodajes notifications_id i parents_id i seen
//  nakon toga saljes poruku