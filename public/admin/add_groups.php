<?php 
ob_start();
include ("../../private/initialize.php");
Mapper::set_database();
// &&
if(Sanitizer::ifPost() && Sanitizer::checkFormFields('addGroupFields') && Sanitizer::isCSRFTokenValid() ){
        $_POST['teachers'] = array_filter($_POST, function($key) {
            return is_numeric($key); }, ARRAY_FILTER_USE_KEY);
        $studentGroup = new StudentGroup((object) $_POST);
        $studentGroup->getNewTeachingGroup();
        //
        if(!$studentGroup->checkifexists()) {
            $studentGroup->createGroup();
            $studentGroup->createSchedule();
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
            unset($p->password,$p->loginToken);
            foreach($p->data as &$k) {
                unset($k->password,$k->loginToken);
            }
            $subject->professors[$p->users_id] = $p;
            unset($subject->password,$subject->loginToken);
        }
        $data[$groupYear][$subjectId] = $subject;
    }
}
$all = json_encode($data);
$profesori = json_encode($profesori);

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
            <?=Sanitizer::CSRFTokenTag()?>
                    <div class="form-group">
                        <label for="group_year">Year:</label>
                        <input type="number" name="group_year" id="group_year" class="form-control" oninput="showTeachers()" required><br>
                        <label for="group_number">Class:</label>
                        <input type="number" name="group_number" id="group_number" class="form-control" required><br>
                        <div id="div1" style="display:none"> <!-- div koji se prikazuje ako korisnik unese razred od 1 do 4 -->
                            <?php if(empty($teachers)) {
                                echo 'No available teachers.';
                            } else {?>
                            <label for="sghead">Select Teacher:</label>
                            <select name='sghead'>
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
    var head;
    var group =  document.getElementById("group_year").value;
    if(group > 8) {
        group = 8;
    }
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
    for(var i in professors) {
        var profId = professors[i].users_id;
        if(professors[i].st_id == newProfessor) {
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
    