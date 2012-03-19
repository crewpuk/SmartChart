<html>
<head>
<title>Coba jqChart</title>
<script type="text/javascript" src="scripts/jquery-ui/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="scripts/jqSuitePHP/js/jquery.jqChart.min.js"></script>
</head>

<body>
<?php
// include the jqUtils Class. The class is needed in all jqSuite components.
require_once "scripts/jqSuitePHP/php/jqUtils.php";

// include the jqChart Class
require_once "scripts/jqSuitePHP/php/jqChart.php";


$filename = "_tmp/dataPie.csv";
$row = 1;
$data_pie = array();
if (($handle=fopen($filename,"r"))!==FALSE){
    while(($data=fgetcsv($handle,filesize($filename),","))!==FALSE){
        $num=count($data);
        for($c=0;$c<$num;$c++){
        	if($data[$c]!="__PIE__"&&$data[$c]!="__TIME__"){
        		$data_pie[$row-2][0]=$data[0];
        		$data_pie[$row-2][1]=floatval($data[1]);
        	}
        }
        $row++;
    }
    fclose($handle);
}
$sum_all = 0;
for($i=0;$i<count($data_pie);$i++){
	$sum_all += $data_pie[$i][1];
}
for($i=0;$i<count($data_pie);$i++){
	$data_pie[$i][1] = round(floatval(($data_pie[$i][1]*100)/$sum_all),2);
}



$chart = new jqChart();

$chart->setTitle(array('text'=>'Browser market shares at a specific website')) 
->setSubTitle(array('text'=>'2010')) 
->setTooltip(array("formatter"=>"function(){return '<b>'+ this.point.name +'</b>: '+ this.y +' %';}")) 
->setPlotOptions(array( 
    "pie"=> array( 
        "allowPointSelect"=> true, 
        "cursor"=> 'pointer', 
        "dataLabels"=>array( 
            "enabled"=>false, 
            "color"=>'#000000', 
            "connectorColor"=>'#000000', 
            "formatter"=>"js:function(){return '<b>'+ this.point.name +'</b>: '+ this.y +' %'}" 
        ) ,
        "showInLegend"=> true 
    ) 
)) 
->addSeries('Browser share',$data_pie)
// ->addSeries('Browser share', array( 
//     array('Firefox',   45.0), 
//     array('IE',       26.8), 
//     array( 
//                "name"=> 'Chrome',     
//                "y"=> 12.8, 
//                "sliced"=> true, 
//                "selected"=> true 
//             ), 
//     array('Safari',    8.5), 
//     array('Opera',     6.2), 
//     array('Others',   0.7) 
// )) 
->setSeriesOption('Browser share', 'type','pie'); 
echo $chart->renderChart('', true, 500, 350); 

/*
__PIE__
Firefox,45.0
IE,26.8
Chrome,12.8
Safari,8.5
Opera,6.2
Others,0.7

$filename = "_tmp/dataPie.csv";
$row = 1;
$data_pie = array();
if (($handle=fopen($filename,"r"))!==FALSE){
    while(($data=fgetcsv($handle,filesize($filename),","))!==FALSE){
        $num=count($data);
        for($c=0;$c<$num;$c++){
        	if($data[$c]!="__PIE__"){
        		$data_pie[$row-2][0]=$data[0];
        		$data_pie[$row-2][1]=floatval($data[1]);
        	}
        }
        $row++;
    }
    fclose($handle);
}
var_dump($data_pie);

$filename = "_tmp/dataPie.csv";
$row = 1;
$data_pie = array();
if (($handle=fopen($filename,"r"))!==FALSE){
    while(($data=fgetcsv($handle,filesize($filename),","))!==FALSE){
        $num=count($data);
        for($c=0;$c<$num;$c++){
        	if($data[$c]!="__PIE__"){
        		$data_pie[$row-2][0]=$data[0];
        		$data_pie[$row-2][1]=floatval($data[1]);
        	}
        }
        $row++;
    }
    fclose($handle);
}
$sum_all = 0;
for($i=0;$i<count($data_pie);$i++){
	$sum_all += $data_pie[$i][1];
}
for($i=0;$i<count($data_pie);$i++){
	$data_pie[$i][1] = floatval(($data_pie[$i][1]*100)/$sum_all);
}
var_dump($data_pie);
*/

?>
</body>
</html>