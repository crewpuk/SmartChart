	<div class="wrapper">
<?php
// include the jqUtils Class. The class is needed in all jqSuite components.
require_once "scripts/jqSuitePHP/php/jqUtils.php";

// include the jqChart Class
require_once "scripts/jqSuitePHP/php/jqChart.php";

$fname = $_COOKIE['c_name'];
$filename = "_tmp/".$fname.".csv";
$row = 1;
$def_num = 1;
$data_categories = array();
$data_series = array();
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, filesize($filename), ",")) !== FALSE) {
    	if($data[0]=="__INFO__"){
            $title=$data[1];
            $sub_title=$data[2];
            $x_info=$data[3];
    	}
    	else{
	        if($data[0]!="__TIME__"){
	        	$num = count($data);
		        if($row==1)$def_num=$num;
		        for($c=0;$c<$num;$c++){
		        	if($num==$def_num){
			        	if($row==1&&$data[0]=="__SERIES__"){
			        		if($data[$c]!="__SERIES__")$data_categories[] = $data[$c];
			        	}
			        	else{
			        		if($c==0)$data_series['name'][]=$data[$c];
			        		else $data_series[$data_series['name'][count($data_series['name'])-1]]['content'][] = $data[$c];
			        	}
		        	}
		        }
		        $row++;
		    }
		}
    }
    fclose($handle);
}


$chart = new jqChart();

$chart->setChartOptions(array("defaultSeriesType"=>"line","marginRight"=>130,"marginBottom"=>25))
->setTitle(array('text'=>$title,"x"=>-20))
->setSubtitle(array("text"=>$sub_title,"x"=>-20))
->setxAxis(array("categories"=>$data_categories))
->setyAxis(array("title"=>array("text"=>$x_info)))
->setTooltip(array("formatter"=>"function(){return '<b>'+ this.series.name +'</b><br/>'+this.x +': '+ this.y +' ".$x_info."';}"))
->setLegend(array( "layout"=>"vertical","align"=>"right","verticalAlign"=>'top',"x"=>-10,"y"=>100,"borderWidth"=>0));

for($i=0;$i<count($data_series['name']);$i++){
	$series_name = $data_series['name'][$i];
	$series_content = array();
	for($j=0;$j<count($data_series[$data_series['name'][$i]]['content']);$j++){
		$series_content[] = $data_series[$data_series['name'][$i]]['content'][$j];
	}
	$chart->addSeries($series_name, $series_content);
}

// output the chart
echo $chart->renderChart('',true, 500, 350);
?>
    <div class="chart_option">
        <a href="index.php?page=bar"><div class="chart bar" align="center"></div></a>
        <a href="index.php?page=pie"><div class="chart pie" align="center"></div></a>
    </div>
</div>