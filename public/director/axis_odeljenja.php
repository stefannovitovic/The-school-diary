<?php 
require('../../private/initialize.php');
$id = 4;
$result1 = Mapper::selectAllSubjects();

 if (isset($_GET['send_group'])) {
     $student_group = $_GET['groups'];
   
     $subjec2 = array();
      foreach($result1 as  $name){
        $subject = $name->name;
        $result = Mapper::getGradesByStudentGroup($student_group, $subject, $id);
        if(empty($result)) {
          continue;
        }
        $count = count($result);
        $values =  0;
        foreach($result as $row){
          if ($row->grade_type = '4') {

          }
          $values+=$row->value;
        }
        $average[$subject] = $values / $count;
		  }
      if (empty($average)) {
        $_SESSION['msg'] = true;
      }else{
      $prosek_odeljenja = json_encode($average, JSON_PRETTY_PRINT);
      }
}
?>

<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//www.amcharts.com/lib/4/themes/kelly.js"></script>
<div class="bigtime"><h1>PROSEK JEDNOG ODELJENJA</h1></div>
  <div class="row">
                 <div class="col-lg-8">
                         <form action="axis_odeljenja.php" method="GET">
                        <?php
                            Mapper::set_database();
                            $row = Mapper::find_all("student_group");
                            if(isset($_GET['groups'])) {
                              $grupa = $_GET['groups'];
                            } else $grupa = 0;
                            echo "<select name='groups'>";
                            foreach ($row as $group){
                              ?>
                                <option value=<?php echo $group->student_group_id; ?>  
                                <?php
                                if($grupa==$group->student_group_id) echo " selected ";
                                ?>
                                > <?php echo $group->group_year . "-" . $group->group_number; ?></option>
                            
                            <?php
                        }
                        echo "</select>";
                        ?>
                       
                          <input type="submit" value="Izaberi odeljenje" name="send_group">
                            </form>
                          
                </div>
                </div>  
</div>
<div style="height:70%" id="chartdiv1">
<script>


am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_kelly);

var js = <?= $prosek_odeljenja?> ;


// Create chart instance
var chart = am4core.create("chartdiv1", am4charts.XYChart);

// Add data
chart.data = [{

}
  // "predmet": "srpski",
  // "prosek": ,
];
  for(var key in js) {
    var object = {predmet:"", prosek:""};
    object.predmet = key;
    object.prosek = js[key];
    chart.data.push(object);
  }
  chart.data.shift();
  console.log(chart.data);

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "predmet";
// categoryAxis.title.text = "PROSEK ŠKOLE";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;

var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "VISINA OCENA";
valueAxis.min = 1;
valueAxis.max = 5;
chart.numberFormatter.numberFormat = "#.##";

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "prosek";
series.dataFields.categoryX = "predmet";
series.name = "Prosek";
series.tooltipText = "{name}: [bold]{valueY}[/]";
series.heatRules.push({
 "target": series.columns.template,
 "property": "fill",
 "min": am4core.color("#00ffd4"),
 "max": am4core.color("#fff600"),
 "dataField": "valueY"
});
// This has no effect
// series.stacked = true;

// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend 
chart.legend = new am4charts.Legend();





</script>
<?php
  if(isset($_SESSION['msg'])){
      ?>
          <div class="alert alert-warning"><p class="text-center">Nema zaključnih ocena za izabrano odeljenje</p></div>
      <?php
      unset($_SESSION['msg']);
    }
    ?>
<button class="back"><a href="pick_page.php">Nazad</a></button>
</div>