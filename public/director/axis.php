<?php require('../../private/initialize.php');

$subjects = Mapper::selectAllSubjects();

foreach($subjects as $subject) {
    $sub = new Subject($subject);
    $sub->joinData(['grades']);
    $sub->getAverage();
    $average[$sub->name] = $sub->prosek;
}
$prosek = json_encode($average, JSON_PRETTY_PRINT);

?>
<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//www.amcharts.com/lib/4/themes/kelly.js"></script>
<div class="bigtime"><h1>USPEŠNOST PO PREDMETIMA NA NIVOU ŠKOLE</h1></div>
<div id="chartdiv">
	
<script>
am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_kelly);
var js = <?= $prosek?> ;

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

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
// categoryAxis.title.text = "USPEŠNOST PO PREDMETIMA NA NIVOU ŠKOLE";
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
 "min": am4core.color("#a1ff00"),
 "max": am4core.color("#fff600"),
 "dataField": "valueY"
});
// This has no effect
// series.stacked = true;

// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend
chart.legend = new am4charts.Legend();


chart.numberFormatter.numberFormat = "#.#";


</script>
<button class="back"><a href="pick_page.php">Nazad</a></button>
</div>