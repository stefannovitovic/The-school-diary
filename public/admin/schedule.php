<?php
include ("../../private/initialize.php");
Mapper::set_database();
// uzima sva odeljenja, dane i predmete
$groups	  = Mapper::getGSids();
$days     = Mapper::selectAllItems('days');
$blocks = Mapper::selectAllItems('blocks');
//proverava da li je zatrazen raspored nekog specificnog odeljenja, ako nije, stavlja schedule_id za prvo odeljenje
if(isset($_POST['schedule_id'])) {
    $schedule_id = $_POST['schedule_id'];
} else if (isset($_POST['student_group_id'])) {
    $schedule_id = Mapper::getScheduleId($_POST['student_group_id']);
} else $schedule_id = 1;

// proverava da li je menjan i sabmitovan novi raspored
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateschedule']) && Sanitizer::isCSRFTokenValid() ) {
    $schedule = json_decode($_POST['schedule']);
    //brise stari raspored u scheduleblocks
    Mapper::removeSchedule($schedule_id);
    //ubacuje novi raspored u bazu
    foreach($schedule as $day) {
        foreach($day->blocks as $id=>$block) {
            $block_id =(int) $id + 1;
            if($block ===0)
                continue;
            $temp = explode("|",$block);
            $subject_id =(int) $temp[1];
            Mapper::insertBlock($schedule_id,$block_id,$subject_id,$day->id);
        }
    }
    
    //$notification = "Your schedule has been changed";
    //$teacher = Mapper::getTeacherIdBySchedule($schedule_id);
    //Mapper::addNotification($notification,$teacher);
}
$subjects = Mapper::getAvailableSubjects($schedule_id);
$schedule = Mapper::getSchedule($schedule_id);
$jssubjects[0] = "a";
foreach ($subjects as $s) {
    $jssubjects[$s->subjects_id] = $s->name;
}
$teachers = Mapper::allTeachers($schedule_id);
$pteachers = array();
foreach($teachers as $pteacherId=>$psubjectId) {
    $pteacher = Manager::createObject('Teacher',$pteacherId);
    $pteacher->getSchedule();
    $pteacher->getTeachingSubjects($schedule_id);
    foreach($pteacher->subjects as $sub) {
        $pteachers[$sub] = $pteacher->schedule;
    }
}
$notAllowed = Mapper::allowedSubjects($teachers);
$notAllowed = json_encode($notAllowed);
$teachers = json_encode($teachers);
$js = json_encode($jssubjects);
?>
    <head>
        <style>
            table {
                font-size:16px;
            }
            table {
                width:100%;
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 15px;
                text-align: left;
            }
            table#t01 tr:nth-child(even) {
                background-color: #eee;
            }
            table#t01 tr:nth-child(odd) {
                background-color: #fff;
            }
            table#t01 th {
                background-color: #333;
                color: white;
            }

            table#t02 {
                width: 100%;
                max-height: 400px;
                table-layout: fixed;
            }

            table#t02 tr {
                line-height: 4px; height: 4px;
            }
            table#t02, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

            #subjects {
                width: 100%;
                height: 235px;
                border-radius:8px;
                margin-top: 2px;
                border: 1px solid black;
            }
            table#t02 tr:nth-child(even) {
                background-color: #eee;
            }
            table#t02 tr:nth-child(odd) {
                background-color: #fff;
            }
            table#t02 th {
                background-color: #333;
                color: white;
            }
        </style>
        <script>

            // globalni objekat sa prikazivanjem u kom bloku moze koji predmet

            var oldBlockCombo;
            var notAllowed = <?=$notAllowed?> ;
            var teachers = <?=$teachers?>;

            // struktura allowed varijable je sledeca:
            // polja objekta su numericka i predstavljaju ID predmeta
            // unutar svakog polja nalazi se day|block combo string koji predstavlja cas i dan na koji se taj predmet ne moze prevuci
            // kada se predmet prevlaci na polje, treba da se uzme ID predmeta koji se prevlaci (smesta se u varijablu subjectId)
            // da bi se preko njega pristupilo polju allowed objekta
            // uz subjectId mora se uzeti day|block combo polja NA KOJE je prevucen objekat(smesta se u varijablu newBlockCombo)
            // sa subjectId se proverava da li u tom polju allowed objekta postoji newBlockCombo string
            // ukoliko ne postoji, novi blok moze da prihvati predmet
            // newBlockCombo treba da se doda u polja objekta(predmete) koje taj profesor predaje
            // mora se uzeti stari day|block combo SA KOJEG je predmet prevucen (smesta se u promenljivu OldBlockCombo)
            // oldBlockCombo treba da se doda u polja objekta(predmete) koje taj profesor predaje
            // console.log(notAllowed);
            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                ev.dataTransfer.setData("text", ev.target.id);
                oldBlockCombo = $(ev.target).closest("p")[0].parentElement.id;
            }

            function drop(ev,id) {
                //TODO ne radi vracanje u box
                ev.preventDefault();
                var newBlockCombo = id;
                var subjectsToChange=[];
                // newBlockCombo je day|block combo polja na koje je prevucen element
                var data = ev.dataTransfer.getData("text");
                //console.log(data);
                var subjectId = data.split("|")[1];
                //var subjectId=subject[1]; // id predmeta koji sluzi za pristupanje polju objekta
                //8-1, istorija, cetvrtak peti cas
                console.log("Old Block "+oldBlockCombo);
                console.log("New Block "+newBlockCombo);
                console.log("Subject ID "+subjectId);
                var element = document.getElementById(newBlockCombo);
                if(typeof newBlockCombo === "undefined") { // ako je undefined, onda ga je ubacio u BOX
                    //ovde je samo potrebno ukloniti odlblockcombo iz objekta
                    ev.target.appendChild(document.getElementById(data));
                    Object.keys(teachers).forEach(teacherId => {
                        for(z=0, x = teachers[teacherId].length; z<x ;z++) {
                        if(teachers[teacherId][z]==subjectId) {
                            subjectsToChange = teachers[teacherId];
                        }
                    }
                });
                    for(i=0,  c=subjectsToChange.length; i<c ; i++) {
                        var s = subjectsToChange[i];
                        for (var j=0, co = notAllowed[s].length; j<co; j++) { //length - 1
                            if (notAllowed[s][j] === oldBlockCombo) {
                                notAllowed[s].splice(j, 1);
                                break;
                            }
                        }
                    }
                } else {


                    if(!notAllowed[subjectId].includes(newBlockCombo) && !element.firstChild) { //ako taj predmet moze tad i ako ne postoji vec jedan tu"; // && !element.firstChild
                        ev.target.appendChild(document.getElementById(data));
                        //ako uspe da se dropuje, treba dodati i ukloniti
                        //prvo proveri za koje sve to predmete vazi(u slucaju da jedan nastavnik predaje vise predmeta tom odeljenju)
                        Object.keys(teachers).forEach(teacherId => {


                            for(z=0, x = teachers[teacherId].length; z<x ;z++) {
                                if(teachers[teacherId][z]==subjectId) {
                                    subjectsToChange = teachers[teacherId];
                                }
                            }
//                            if(teachers[teacherId].includes(subjectId)) {
//                                console.log('udjem');
//                                subjectsToChange = teachers[teacherId];
//                            }
                    });


                        //console.log("Subjects to change " + subjectsToChange);
                        for(i=0,  c=subjectsToChange.length; i<c ; i++) {
                            var s = subjectsToChange[i];
                            notAllowed[s].push(newBlockCombo);
                            for (var j=0, co = notAllowed[s].length; j<co; j++) { //length - 1
                                if (notAllowed[s][j] === oldBlockCombo) {
                                    notAllowed[s].splice(j, 1);
                                    break;
                                }
                            }
                        }
                    } else {
                        popTheWindow(subjectId,newBlockCombo);
                        //window.alert("Nope");
                    }
                }



                console.log(notAllowed);
            }
            function populate($id) {
                var subjects = <?=$js?>;
                var element = document.createElement("p");
                var r = Math.random().toString(36).substring(7);
                element.id = r+"|"+$id;
                element.draggable="true";
                element.ondragstart = drag;
                element.innerHTML = subjects[$id];
                document.getElementById('subjects').appendChild(element);
            }

            function clearAll() {
                var elements = [];
                for(i=1; i<6; i++) {
                    for(j=1;j<7;j++) {
                        elements.push(i+"|"+j);
                    }
                }
                elements.forEach(function(entry) {
                    var myNode = document.getElementById(entry);
                    if(myNode.hasChildNodes()) {
                        myNode.removeChild(myNode.firstChild);
                    }

                });
            }

            function popTheWindow(subjectId,blockCombo) {
                var day = blockCombo.split("|")[0];
                var block = blockCombo.split("|")[1];
                console.log(subjectId);
                switch(day) {
                    case '1':
                        dayName = "Monday";
                        break;
                    case '2':
                        dayName = "Tuesday";
                        break;
                    case '3':
                        dayName = "Wednesday";
                        break;
                    case '4':
                        dayName = "Thursday";
                        break;
                    case '5':
                        dayName = "Friday";
                }
                window.alert("Teacher already have class on "+dayName+", block No. "+block);
            }

            function getSchedule() {
                var days = [
                    {
                        name:"njet",
                        id:0,
                        blocks:[]
                    },
                    {
                        name:"ponedeljak",
                        id: 1,
                        blocks: []
                    },
                    {
                        name:"utorak",
                        id: 2,
                        blocks: []
                    },
                    {
                        name:"sreda",
                        id: 3,
                        blocks: []
                    },
                    {
                        name:"cetvrtak",
                        id: 4,
                        blocks: []
                    },
                    {
                        name:"petak",
                        id: 5,
                        blocks: []
                    }
                ];

                var table = document.getElementById("t01");
                for (var i = 0, row; row = table.rows[i]; i++) {
                    //rows would be accessed using the "row" variable assigned in the for loop
                    for (var j = 0, col; col = row.cells[j]; j++) {
                        child = col.firstElementChild;
                        if (child !== null) {
                            child = child.getAttribute("id");
                        } else {
                            child = 0;
                        }
                        days[i].blocks.push(child);
                        //columns would be accessed using the "col" variable assigned in the for loop
                    }
                    days[i].blocks.shift();
                }
                days.shift();
                document.getElementById("raspored").value = JSON.stringify(days);
                console.log(days);
            }

            function prikazi(id) {
                var id = 'r'+id.match(/\d+/g).map(Number);
                document.getElementById(id).style.display = 'inline';
            }

            function ukloni(id) {
                var id = 'r'+id.match(/\d+/g).map(Number);
                document.getElementById(id).style.display = 'none';
            }

        </script>
    </head>
    <div id="page-wrapper">

        <div class="container-fluid">
            <!-- INFO DIVOVI -->
            <?php $i=0; foreach($pteachers as $subjeId=>$teach) { $i++; ?>
            <div style="display: none; position:absolute; top:0; left:0; z-index:99999;" id="r<?=$subjeId?>">
                    <table id="t02">
                        <tr>
                            <th>Dan</th>
                            <th>07:30 - 08:15</th>
                            <th>08:20 - 09:05</th>
                            <th>09:20 - 10:05</th>
                            <th>10:10 - 10:55</th>
                            <th>11:00 - 11:45</th>
                            <th>11:50 - 12:35</th>
                        </tr>
                        <?php
                        foreach($days as $day) {
                            echo "<tr>";
                            echo "<td>{$day->name}</td>";
                            foreach($blocks as $block){
                                if(isset($teach[$day->days_id][$block->blocks_id])) {
                                    ?>
                                                    <td>
                                                    <span><?=$teach[$day->days_id][$block->blocks_id]['name']?>-<?=$teach[$day->days_id][$block->blocks_id]['group_name']?></span>
                                                    </td>
                                                    <?php
                                } else {
                                    echo "<td></td>";
                                }
                            }
                            echo "</tr>";
                        }


                        ?>
                    </table>
                </div>
            <?php }  ?>
            <!-- INFO DIVOVI KRAJ->>
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    foreach($groups as $g) {
                        if($g->schedule_id==$schedule_id) {
                            echo "<h1 class='page-header'>Raspored za {$g->group_year}-{$g->group_number}</h1>";
                            break;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i> Schedule
                        </li>
                    </ol>

                </div>
                <div class="col-lg-1">
                    <form action="schedule.php" method="POST">
                        <input type="submit" class="btn btn-warning" name="getschedule" value="Get Schedule" >

                </div>
                <div class="col-lg-1">
                    <select class="form-control form-control-sm" name="student_group_id">
                        <?php

                        foreach($groups as $group) {
                            echo "<option value='{$group->student_group_id}' ";
                            if($group->schedule_id==$schedule_id) {
                                echo "selected='selected'";
                            }
                            echo ">{$group->group_year}-{$group->group_number}</option>";
                        }
                        ?>
                    </select>

                    </form>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-10">
                    <table id="t01">
                        <tr>
                            <th>Dan</th>
                            <th>07:30 - 08:15</th>
                            <th>08:20 - 09:05</th>
                            <th>09:20 - 10:05</th>
                            <th>10:10 - 10:55</th>
                            <th>11:00 - 11:45</th>
                            <th>11:50 - 12:35</th>
                        </tr>
                        <?php
                        if($schedule) {
                            foreach ($schedule->days as $day) {
                                ?>
                                <tr>
                                    <td><?=$day->name?></td>
                                    <?php foreach ($day->blocks as $block) {
                                        $block->showBlock();
                                    }?>
                                </tr>
                            <?php } }//END DAYS FOREACH ?>
                    </table>
                </div>
                <div class="col-lg-2 text-center">
                    <p>Add Subject:</p>
                    <select class="form-control form-control-sm" name="tip" id="slct1" onChange="populate(value)">
                        <option value="">Izaberi</option>
                        <?php
                        foreach($subjects as $subject) {
                            echo "<option value='{$subject->subjects_id}'>{$subject->name}</option>";
                        }
                        ?>
                    </select>
                    <div id="subjects"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                    <form action="schedule.php" method="POST" onsubmit="getSchedule()">
                        <?=Sanitizer::CSRFTokenTag()?>
                        <input type="hidden" name="schedule_id" value="<?=$schedule_id?>">
                        <input type="hidden" id="raspored" name="schedule" value="">
                        <input class="btn btn-success" type="submit" name="updateschedule" value="UPDATE" >
                    </form><br>
                    <button type="button" onclick="clearAll()">Clear All</button>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
