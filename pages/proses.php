<?php
$sum_caption = $_POST['sum_caption'];
$sum_x_axis = $_POST['sum_x_axis'];

$type_chart = "line";

$title=(isset($_POST['title']))?$_POST['title']:"";
$sub_title=(isset($_POST['sub_title']))?$_POST['sub_title']:"";
$x_info=(isset($_POST['x_info']))?$_POST['x_info']:"";

$row=array('__TIME__,'.date("U"),"__INFO__,".$title.",".$sub_title.",".$x_info);

function str2csv($string){return (preg_match("#([\,]+)#", $string))?'"'.$string.'"':$string;}
function generate_name($digit=33,$delimiter=TRUE){
    $max=$digit;
    $name='';
    for($i=0;$i<$max;$i++){
        if($delimiter&&($i%9==0&&$i>0))$name.="_";
        $name.=rand(0,9);
    }
    return $name;
}

$error=false;
if($type_chart!="pie"){
	$row[2] = "__SERIES__";
	for($i=1;$i<=$sum_x_axis;$i++){
		if(isset($_POST['x_'.$i]))$row[2] .= ','.str2csv($_POST['x_'.$i]);
	}
	for($j=1;$j<=$sum_caption;$j++){
		if(isset($_POST['cap_'.$j])){
			$row[$j+2]=str2csv($_POST['cap_'.$j]);
			for($i=1;$i<=$sum_x_axis;$i++){
				if(isset($_POST['v_'.$i.'_'.$j])){
					if(preg_match("#([^0-9\.\-]+)#", str_replace(",", ".", $_POST['v_'.$i.'_'.$j]))) {
						$error = true;
					}
					else{
						$row[$j+2].=','.str2csv($_POST['v_'.$i.'_'.$j]);
					}
				}
			}
		}
	}
}
if($error){header("location: play.php?error=1&type=mismatch");}

$gen_name = generate_name(15,false);
setcookie('c_name',$gen_name,time()+(60*60*24),'/');
$file_name = $gen_name;
$text_for_csv = "";

for($i=0;$i<count($row);$i++){
	if($i>0)$text_for_csv .= "\n";
	$text_for_csv .= $row[$i];
}
//echo($text_for_csv);
//var_dump($row)

$handle = fopen('../_tmp/'.$gen_name.'.csv','w');
fwrite($handle, $text_for_csv);
fclose($handle);

$chart_option=$_POST['chart_option'];
$path_for_next = '../index.php?page=';
$path_for_next .= ($chart_option=="line")?"line":(($chart_option=="bar")?"bar":"pie");
if(!$error){header("location: ".$path_for_next);}

?>