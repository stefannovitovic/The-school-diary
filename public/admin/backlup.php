<?php 
ob_start();
include ("../../private/initialize.php");
Mapper::set_database();
if(isset($_POST['add_group'])){
    Mapper::addStudentGroup();
    //dodati i u schedule tabelu novi raspored
}
$teachers = Mapper::getAvailableTeachers();
// trebaju mi iz teaching_group svi predmeti sa group_type > 4
// i svi profesori koji predaju te predmete 
$profesori = Mapper::getAllSubsAndTeachers();
foreach($profesori as $groupYear=>$subjects) {
    foreach($subjects as $subjectId=>$professors) {
        $subject = Manager::createObject('Subject',$subjectId);
        $subject->professors = array();
        foreach($professors as $prof) {
            $p = Manager::createObject('Teacher',$prof);
            $subject->professors[$p->users_id] = $p;
        }
    }
    $all[$groupYear][$subjectId] = $subject;
}

print_r($profesori);
$all = json_encode($all);
$profesori = json_encode($profesori);
print_r($all);

?>



<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Add group
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Add group
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <form action="add_groups.php" method="POST">
                    <div class="form-group">
                        <label for="group_year">Year:</label>
                        <input type="number" name="group_year" id="group_year" class="form-control" oninput="showTeachers()"><br>
                        <label for="group_number">Class:</label>
                        <input type="number" name="group_number" id="group_number" class="form-control"><br>
                        <div id="div1" style="display:none"> <!-- div koji se prikazuje ako korisnik unese razred od 1 do 4 -->
                            <?php if(empty($teachers)) {
                                echo 'No available teachers.';
                            } else {?>
                            <select name='teacher'>
                                <?php
                                echo "<option value='0'>Select teacher</option>";
                                foreach($teachers as $teacher) {
                                    echo "<option value='{$teacher->users_id}'>{$teacher->username}</option>";
                                }
                                ?>
                            </select>
                            <?php } ?><br>
                        </div>
                        <div id="div2" style="display:none"> <!-- div koji se prikazuje ako korisnik unese razred od 5 do 8 -->
     
                        </div>

                        <input type="submit" class="btn btn-primary" name="add_group" value="Add">
                    </div>
            </form>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<script type="text/javascript" src="main.js"></script>

<script>
var professors = <?=$profesori?> ;
var all = <?=$all?>;
function showTeachers() {
    var group =  document.getElementById("group_year").value;
    if(group<5) {
        document.getElementById('div1').style.display= 'block' ;
        document.getElementById('div2').style.display= 'none' ;
        var myNode = document.getElementById("div2");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
    } else {
        document.getElementById('div1').style.display= 'none' ;
        document.getElementById('div2').style.display= 'block' ;
        var myNode = document.getElementById("div2");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
        for(var sub in professors[group]) {
            // var node = document.createElement("input");
            // node.type = "option";
            // node.value = professors[group][s][k];
            // var textnode = document.createTextNode("subject"+professors[group][s][k]);
            // node.appendChild(textnode);
            // document.getElementById("selsubject").appendChild(node);
            console.log(all[group][sub]);
            //kreiraj select za sub
            var par = document.createElement("p");
            par.innerHTML = "Subject "+sub;
            document.getElementById("div2").appendChild(par);
            var select = document.createElement("select");
            select.name = sub;
            select.id = "select"+sub;
            var textnode = document.createTextNode("subject"+sub);
            select.appendChild(textnode);
            document.getElementById("div2").appendChild(select);
            for(var i in all[group][sub]) {
                console.log("ZA "+sub+" profesor je " +all[group][sub][i]);
                var node = document.createElement("option");
                node.value = all[group][sub][i];
                var textnode = document.createTextNode("Profesor"+all[group][sub][i]);
                node.appendChild(textnode);
                document.getElementById("select"+sub).appendChild(node);
            }
            document.getElementById('div2').style.display= 'block';
            var br = document.createElement("br");
            document.getElementById("div2").appendChild(br);
            var br = document.createElement("br");
            document.getElementById("div2").appendChild(br);
        }
     }


}

</script>
    