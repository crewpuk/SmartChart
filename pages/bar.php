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

$chart->setChartOptions(array( 
    "defaultSeriesType"=>"column" 
)) 
->setTitle(array('text'=>$title)) 
->setSubtitle(array("text"=>$sub_title)) 
// ->setxAxis(array("categories"=>array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')))
->setxAxis(array("categories"=>$data_categories))
->setyAxis(array( 
    "title"=>array("text"=>$x_info) 
)) 
// ->setLegend(array(  
//     "layout"=>"vertical", 
//     "backgroundColor"=>'#FFFFFF', 
//     "align"=>"left", 
//     "verticalAlign"=>'top', 
//     "x"=>100, 
//     "y"=>70, 
//     "floating"=>true, 
//     "shadow"=>true 
// )) 
->setTooltip(array("formatter"=>"function(){return this.x +': '+ this.y +' ".$x_info."';}")) 
->setPlotOptions(array( 
    "column"=> array( 
//        "pointPadding"=> 0.2, 
        "borderWidth"=> 0 
    ) 
));
// ->addSeries('Tokyo', array(49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4))
// ->addSeries('New York', array(83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3))
// ->addSeries('London', array(83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3))
// ->addSeries('Berlin', array(42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1));


for($i=0;$i<count($data_series['name']);$i++){
	$series_name = $data_series['name'][$i];
	$series_content = array();
	for($j=0;$j<count($data_series[$data_series['name'][$i]]['content']);$j++){
		$series_content[] = $data_series[$data_series['name'][$i]]['content'][$j];
	}
	$chart->addSeries($series_name, $series_content);
}


// ->addSeries('Jhon', array(5, 3, 4, 7, 2)) 
// ->addSeries('Jane', array(2, -2, -3, 2, 1)) 
// ->addSeries('Joe', array( 3, 4, 4, -2, 5)); 
echo $chart->renderChart('', true, 500, 350);

?>
    <div class="chart_option">
        <a href="index.php?page=line"><div class="chart line" align="center"></div></a>
        <a href="index.php?page=pie"><div class="chart pie" align="center"></div></a>
    </div>
</div>