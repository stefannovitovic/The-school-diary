<?php
ob_start();
include ("../../private/initialize.php");
$schedule = unserialize($_SESSION['schedule']);
$days = Mapper::selectAllItems('days');
$blocks = Mapper::selectAllItems('blocks');

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
            #subjects {
                width: 100%;
                height: 235px;
                border-radius:8px;
                margin-top: 2px;
                border: 1px solid black;
            }
        </style>
    </head>
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class='page-header'>Raspored</h1>
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
                            foreach($days as $day) {
                                echo "<tr>";
                                echo "<td>{$day->name}</td>";
                                foreach($blocks as $block){
                                    if(isset($schedule[$day->days_id][$block->blocks_id])) {
                                        ?>
                                        <td>
                                        <p><?=$schedule[$day->days_id][$block->blocks_id]['name']?></p>
                                        <p><?=$schedule[$day->days_id][$block->blocks_id]['group_name']?></p>
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

            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

