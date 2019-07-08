<?php 
require('../../private/initialize.php');
Mapper::set_database();
if ($_SERVER['REQUEST_METHOD']==="POST"){
    if(!Mapper::checkifgroupexists()) {
        if($_POST['group_year'] > 4) {
            Mapper::updateStudentGroup();
        } else {
            Mapper::updateGroup($_POST);
        }
    }
}

if(isset($_GET['edit'])) {
    $currentProf = Mapper::getCurentProf($_GET['edit']);
    $year = Mapper::get123($_GET['edit']);
    if(isset($currentProf)) {
        $curr       = json_encode($currentProf);
    }
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
            $p->joinData();
            foreach($p->data as $subj) {
                if($subj->subjects_subjects_id==$subjectId) {
                    $p->st_id = $subj->teachers_subjects_id;
                    break;
                }
            }
            $subject->professors[$p->users_id] = $p;
        }
        $data[$groupYear][$subjectId] = $subject;
    }
}
$all = json_encode($data);
$profesori = json_encode($profesori);

?>
<script>
    var professors = <?=$profesori?> ;
    var all = <?=$all?>;
    var curr= <?=$curr?>;
</script>
<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"> Manage Groups
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-file"></i> Blank Page
                    </li>
                </ol>
            </div>
        </div>
        <!--prikaz odeljenja u tabeli-->
        <div class="row">
            <div class="col-lg-6">
                <table class="table table-hover">
                    <tr>
                        <th>Group Id</th>
                        <th>Group Name</th>
                    </tr>
                    <?php
                    Mapper::set_database();
                    $row = Mapper::find_all("student_group");
                    foreach ($row as $group){
                        ?>
                        <tr>
                            <td><?php  echo $group->student_group_id; ?></td>
                            <td><?php  echo $group->group_year."-".$group->group_number; ?></td>
                            <td></td>
                            <td><a href= "edit_group.php?edit=<?php echo $group->student_group_id; ?>">Edit</a></td>
                        </tr>
                    <?php }    ?>
                </table>
                <!-- editovanje oznacenih odeljenja -->
                <?php
                if(isset($_GET['edit'])){
                    $groupRow = Mapper::selectStudentGroupID();
                    foreach ($groupRow as $group){
                        ?>
                        <form action="edit_group.php" method="POST">
                        <div class="form-group">
                        <label for="edit_name_group">Edit group <?php echo $group->group_year."-".$group->group_number; ?>:</label><br>
                        <input type="hidden" name="group_id" value="<?php echo $group->student_group_id;?>" class="form-control"><br>
                        <label for="group_year">Group year:</label>
                        <input type="number" name="group_year" id="group_year" value="<?=$group->group_year?>" class="form-control" oninput="showTeachers()"><br>
                        <label for="group_number">Group Number:</label>
                        <input type="number" name="group_number" id="group_number"value="<?=$group->group_number?>" class="form-control"><br>
                        <?php

                          if($year < 4) {
                              echo "<div id='div1' style='display:block'>";
                          }  else echo "<div id='div1' style='display:none'>";
                          if(empty($teachers)) {
                                echo 'No available teachers.';
                          } else {?>
                            <label for="teacher">
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
                        <div id="div2" style="display:block"> <!-- div koji se prikazuje ako korisnik unese razred od 5 do 8 -->
                        <?php // ako je razred > 4
                        if($groupRow[0]->group_year > 4) {
                            echo "<label for='sghead'>Student Group Head:</label>";
                            echo "<select name='sghead' id='sghead' class='form-control'>";
                            foreach($currentProf as $head) {
                                echo "<option value='{$head->teachers_teachers_id}'";
                                if($head->student_group_head_id === $head->teachers_teachers_id) {
                                    echo "selected";
                                }
                                echo ">{$head->firstName} {$head->lastName}</option>";
                            }
                            echo "</select>";


                            foreach($data[$year] as $subject) { // tu imas
                                echo "<label for='{$subject->subjects_id}'>{$subject->name}:</label>";
                                echo "<select name='{$subject->subjects_id}' id='select{$subject->subjects_id}' onchange='headfunction(this)' class='form-control'>";
                                foreach($subject->professors as $profess) {
                                    foreach($profess->data as $pr) {
                                        if($pr->subjects_subjects_id==$subject->subjects_id) {
                                            echo "<option value='{$pr->teachers_subjects_id}'";
                                            if($currentProf[$subject->subjects_id]->teachers_subjects_id===$pr->teachers_subjects_id) {
                                                echo "selected";
                                            }
                                            echo ">{$pr->firstName} {$pr->lastName}</option>";
                                            break;
                                        }
                                    }
                                }
                                echo "</select>";
                            }

                            ?>
                            </div>

                            </div>
            <?php } ?>
                            <input type="submit" class="btn btn-warning" name="edit_group" value="Edit">
                            </form>
                        <?php } }  ?>

            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<script>
    //TODO ostavi razrednog pri promeni odeljenja
    function showTeachers() {
        var head;
        var group =  document.getElementById("group_year").value;
        var myNode = document.getElementById("div2");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
        if(group<5) {
            document.getElementById('div1').style.display= 'block' ;
            document.getElementById('div2').style.display= 'none' ;
        } else {
            document.getElementById('div1').style.display= 'none' ;
            document.getElementById('div2').style.display= 'block' ;
            //kreiraj select za razrednog
            var par = document.createElement("label");
            par.innerHTML = "Student Group Head:";
            par.setAttribute("for","sghead");
            document.getElementById("div2").appendChild(par);
            var select = document.createElement("select");
            select.name = "sghead";
            select.id = "sghead";
            select.classList.add("form-control");
            document.getElementById("div2").appendChild(select);


            for(var sub in all[group]) {
                subjectId = all[group][sub].subjects_id;
                subjectName = all[group][sub].name;
                //kreiraj label
                var par = document.createElement("label");
                par.innerHTML = subjectName+":";
                par.setAttribute("for",subjectId);
                document.getElementById("div2").appendChild(par);
                //kreiraj select element za svaki predmet
                var select = document.createElement("select");
                select.name = subjectId;
                select.id = "select"+subjectId;
                select.setAttribute("onChange","headfunction(this)");
                var textnode = document.createTextNode(subjectName);
                select.appendChild(textnode);
                document.getElementById("div2").appendChild(select);

                //dodaj select options
                var m = 0;
                for(var i in all[group][sub].professors) {
                    if(m==0) {
                        var node = document.createElement("option");
                        node.value = all[group][sub].professors[i].users_id;
                        var textnode = document.createTextNode(all[group][sub].professors[i].firstName +" "+all[group][sub].professors[i].lastName);
                        node.appendChild(textnode);
                        document.getElementById("sghead").appendChild(node);
                    }

                    //dodaj select opciju
                    var node = document.createElement("option");
                    node.value = all[group][sub].professors[i].st_id;
                    var textnode = document.createTextNode(all[group][sub].professors[i].firstName +" "+all[group][sub].professors[i].lastName);
                    node.appendChild(textnode);
                    select.classList.add("form-control");
                    //TODO dodaj selected ako je subjects u nekom x nizu
                    if (typeof curr[sub] != "undefined" && curr[sub] != null) {
                        if (all[group][sub].professors[i].users_id == curr[sub].users_id) {
                            console.log("ONO OD CEGA SE PRAVI JE ");
                            console.log(all[group][sub].professors[i]);
                            console.log("ONO STO IMAM OD RANIJIH PODATAKA JE ");
                            console.log(curr[sub]);
                            node.setAttribute("selected", true);
                        }
                    }
                    document.getElementById("select"+sub).appendChild(node);
                    m++;
                }

            }
        }


    }

    function headfunction(hello) {
        var yourSelect = document.getElementById(hello.id);
        newProfessor = yourSelect.options[ yourSelect.selectedIndex ].value ;
        // newProfessor --- to je subject_teacher vrednost
        var subjectId = hello.name;
        // subjectId je id subjecta
        var year = document.getElementById("group_year");
        year = year.value;
        var professors = all[year][subjectId].professors;
        console.log(professors);
        for(var i in professors) {
            var profId = professors[i].users_id;
            console.log("DA LI JE "+professors[i].st_id+"="+newProfessor);
            if(professors[i].st_id == newProfessor) {
                console.log("JESTE");
                console.log(professors[i]);
                //dodaj u student head vrednost profId
                var node = document.createElement("option");
                node.value = profId;
                var textnode = document.createTextNode(professors[i].firstName +" "+professors[i].lastName);
                node.appendChild(textnode);
                document.getElementById("sghead").appendChild(node);
            } else {
                //ukloni iz student head ako postoji vrednost profId
                var head = document.getElementById("sghead");
                var count = head.length;
                for(var i = 0; i < count; i++){
                    if(head[i].value == profId) {
                        head[i].remove();
                        break;
                    }
                }

            }
        }

    }

</script>
    
        
