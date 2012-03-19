<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_errors',1);
include_once '../php/jqUtils.php';
include_once 'connections.php';
try {
	include_once '../form_conf.inc.php';
} catch (Exception $e) {
	$demo = false;
}
if(!isset ($demo)) {
	$demo = false;
}
$fname = $_POST['fname'];
if(!file_exists($fname)) {
	echo "file does not exists";
	exit;
}
$path_info = pathinfo($fname);

$doc = new DOMDocument();
$ret = $doc->load( $fname );
$rows = $doc->getElementsByTagName( "row" );
$mainarray = array();

foreach( $rows as $row )
{
	$md = array();
	$ids = $row->getElementsByTagName( "id" );
	$md['id'] = $ids->item(0)->nodeValue;
  
	$props= $row->getElementsByTagName( "prop" );
	$md['prop']= json_decode($props->item(0)->nodeValue, true);

	$names = $row->getElementsByTagName( "fldname" );
	$md['name'] = $names->item(0)->nodeValue;

	$events= $row->getElementsByTagName( "events" );
	if( $events->item(0)->nodeValue != "__EMPTY_STRING_") {
		if(trim($events->item(0)->nodeValue) == "") $ev = "{}";
		else $ev = trim($events->item(0)->nodeValue);
		$md['events']= jqGridUtils::decode( $ev);
	} else {
		$md['events'] = "";
	}
	
	$type = $row->getElementsByTagName( "type" );
	$md['type']= $type->item(0)->nodeValue;
	
	$parent = $row->getElementsByTagName( "parent" );
	$md['parent']= $parent->item(0)->nodeValue;
	
	$mainarray[] = $md;
} 

// user data
$userd = $doc->getElementsByTagName( "userdata" );
$otherset = array();

foreach( $userd as $user )
{
	$unm = $user->getAttribute('name'); 
	$uvalue = $user->nodeValue; 
	$otherset[$unm]= $uvalue;
}

$formdata = $mainarray[0];
$str = "<?php\n";
$str .= "// Include class \n";
$str .= "include_once 'jqformconfig.php'; \n";
$str .= "include_once "."$"."CODE_PATH."."'jqUtils.php'; \n";
$str .= "include_once "."$"."CODE_PATH."."'jqForm.php'; \n";
$jq_conn = "";

if( isset($otherset['bphpscript']) ) {
	if($otherset['bphpscript'] != "")
	{
		$str .= "// Add phpscript before creating the instance \n";
		$str .=  html_entity_decode($otherset['bphpscript'])."\n";
	}
}


$str .= "// Create instance \n";
$the_name   = "$".$formdata['name'];
//$str .= $the_name." = new jqForm();\n";
$str .= $the_name." = new jqForm('".$formdata['name']."',array(";

$i=0;
if(!isset($formdata['prop']['id'])) {
	$formdata['prop']['id'] = $formdata['name'];
	$mainarray[0]['prop']['id'] = $formdata['name'];
}
foreach( $formdata['prop'] as $k=>$v)
{
	if($i==0) {
		$str .= "'".$k."' => '".$v."'";
	} else {
		$str .= ", '".$k."' => '".$v."'";
	}
	$i++;
}
$str .= "));\n";




if( $demo === true) {
	if($otherset['createconn'] == 'yes') {
		$str .= "// Demo Mode creating connection \n";
		$str .= "include_once "."$"."CODE_PATH."."'jqGridPdo.php'; \n";
		$str .= "$"."conn = new PDO(DB_DSN, DB_USER, DB_PASSWORD);\n";
		$str .= $the_name."->setConnection( "."$"."conn". " );\n";
	}
	
} else if(isset ($otherset['createconn']) && $otherset['createconn'] == 'yes'){
	$dbtype = $otherset['dbtype'];
	$condobj = parse_connection_string($otherset['connstring']);
	$jq_conn = "$".$otherset['conname'];
	$str .= "// Create the connection \n";			
	//$str .= "if(".$the_name."->oper != 'no') {\n";
	
	switch ($dbtype)
	{
		case 'mysql':
		case 'pgsql':
		case 'sqlite':
			$str .= "include_once "."$"."CODE_PATH."."'jqGridPdo.php'; \n";
			$str .= $jq_conn." = new PDO('".$dbtype.":host=".$condobj->host.";dbname=".$condobj->database."','".$condobj->user_id."','".$condobj->password."');\n";
			break;
	}
	$str .= $the_name."->setConnection( "."$".$otherset['conname']. ");\n";
	//$str .= "}\n";
}

/*
$str .= "// Set Form properties\n";
$str .= $the_name."->setFormProperties('".$formdata['name']."',array(";
$i=0;
if(!isset($formdata['prop']['id'])) {
	$formdata['prop']['id'] = $formdata['name'];
	$mainarray[0]['prop']['id'] = $formdata['name'];
}
foreach( $formdata['prop'] as $k=>$v)
{
	if($i==0) {
		$str .= "'".$k."' => '".$v."'";
	} else {
		$str .= ", '".$k."' => '".$v."'";
	}
	$i++;
}
$str .= "));\n";
*/
// Url path

// substr($path_info['basename'], 0, -(strlen($path_info['extension']))
$str .= "// Set url\n";

$url = "";
if(isset ($formdata['prop']['action'])) {
	if(strlen(trim($formdata['prop']['action'])) > 0 ) {
		$url = trim($formdata['prop']['action']);
		$str .= $the_name."->setUrl('".$formdata['prop']['action']."');\n";
	}
}

if( $url == "") {
	$str .= $the_name."->setUrl("."$"."SERVER_HOST."."$"."SELF_PATH."."'".substr($path_info['basename'], 0, -(strlen($path_info['extension'])))."php');\n";
}

if( isset( $otherset['formheader']) && $otherset['formheader']  != "")
{
	$ic = isset($otherset['formicon']) ? $otherset['formicon'] :"";
	if( trim($otherset['formheader']) != ""  ) {
		$str .= "// Set Form header \n";
		$str .= '$formhead='. "'".$otherset['formheader']."';\n";
		$str .= $the_name.'->setFormHeader($formhead,\''.$ic.'\');';
		$str .= "\n";
	}
}
if( isset( $otherset['formfooter']) && $otherset['formfooter']  != "")
{
	if( trim($otherset['formfooter']) != ""  ) {
		$str .= "// Set Form Footer \n";
		$str .= $the_name.'->setFormFooter("'.$otherset['formfooter'].'");';
		$str .= "\n";
	}
}

$str .= "// Set parameters \n";

$prm = "$"."jqformparams";


if(isset($otherset['prmnames'])) {
	if(trim($otherset['prmnames']) != "") {
		$aprm = explode(",",$otherset['prmnames']);
		$adefs = explode(",",$otherset['prmdefs']);
		$atps = explode(",",$otherset['prmtypes']);
		foreach($aprm as $k=>$v) {
			$str .= "$".trim($v)." = jqGridUtils::GetParam('".trim($v)."','".$adefs[$k]."');\n";
			switch ( $atps[$k] ) {
				case 'string':
					$str .= "$".trim($v)." = is_string("."$".trim($v).") ? (string)"."$".trim($v)." : '';\n";
					break;
				case 'int':
					$str .= "$".trim($v)." = is_numeric("."$".trim($v).") ? (int)"."$".trim($v)." : 0;\n";
					break;
				case 'numeric':
					$str .= "$".trim($v)." = is_numeric("."$".trim($v).") ? (float)"."$".trim($v)." : '';\n";
					break;
				case 'bool':
					$str .= "$".trim($v)." = filter_var("."$".trim($v).", FILTER_VALIDATE_BOOLEAN);\n";
					break;
			}
		}
		$str .= $prm." = array(";
		foreach($aprm as $k=>$v) { 
			if($k != 0) {
				$str .= ",$".trim($v);
			} else {
				$str .= "$".trim($v);
			}
		}
		$str .= ");\n";
	} else {
		$str .= $prm." = array();\n";
	}
} else {
	$str .= $prm." = array();\n";
}

$str .= "// Set SQL Command, table, keys \n";

if( isset($otherset['sqlstring']) && $otherset['sqlstring'] != "" )
{
	$str .= $the_name."->SelectCommand = '".$otherset['sqlstring']."';\n";
}

if( isset($otherset['table']) && $otherset['table'] != "" )
{
	$str .= $the_name."->table = '".$otherset['table']."';\n";
}
if( isset($otherset['tblkeys']) && $otherset['tblkeys'] != "" )
{
	$str .= $the_name."->setPrimaryKeys('".$otherset['tblkeys']."');\n";
}
if( isset($otherset['serialkey']) &&  $otherset['serialkey'] == "no" )
{
	$str .= $the_name."->serialKey = false;\n";
} else {
	$str .= $the_name."->serialKey = true;\n";
}


if( isset($otherset['formlayout']) && $otherset['formlayout'] != "" )
{
		$str .= "// Set Form layout \n";
		$str .= $the_name."->setColumnLayout('".$otherset['formlayout']."');\n";
}

if (!isset($otherset['tablestyle'])) $otherset['tablestyle'] = "";
if (!isset($otherset['labelstyle'])) $otherset['labelstyle'] = "";
if (!isset($otherset['datastyle']))  $otherset['datastyle'] = "";

if( $otherset['tablestyle'] !=""  ||  
	$otherset['labelstyle'] != "" || 
	$otherset['datastyle'] != "" )
{	
	$str .= $the_name."->setTableStyles('".$otherset['tablestyle']."','".$otherset['labelstyle']."','".$otherset['datastyle']."');\n";
}

$str .= "// Add elements\n";
$i=1;
for($i=1;$i<count($mainarray); $i++) {
	$tmp = $mainarray[$i];
	if(!isset ($tmp['prop']['id'])) {
		$mainarray[$i]['prop']['id'] = $formdata['name']."_".$tmp['name'];
		$tmp['prop']['id'] = $formdata['name']."_".$tmp['name'];
	}	
	if($tmp['type'] == 'group') {
		$par = $mainarray[$i+1]['parent'];
		$garname = "$".'elem_'.(string)$i;
		while($par == $mainarray[$i+1]['parent']) {
			//$name, $type, $prop, $opt=null
			$tmp2 = $mainarray[$i+1];
			$str .= $garname."[]=".$the_name."->createElement('".$tmp2['name']."','".$tmp2['type']."', array(";
			$j = 0;
			foreach( $tmp2['prop'] as $k=>$v)
			{
				$pos = substr($v,0,1);
				if($j==0) {
					if($pos === false || $pos != "$" )
						$str .= "'".$k."' => '".$v."'";
					else 
						$str .= "'".$k."' => ".$v;
				} else {
					if($pos === false || $pos != "$" )
						$str .= ", '".$k."' => '".$v."'";
					else 
						$str .= ", '".$k."' => ".$v;
				}
				$j++;
			}
			$str .= "));\n";
			$i++;
			if(!isset($mainarray[$i+1])) {
				break;
			}
		}
		$str .= $the_name.'->addGroup("'.$tmp['name'].'",'.$garname.', array(';
	} else {
		$str .= $the_name."->addElement('".$tmp['name']."','".$tmp['type']."', array(";
	}
	$j = 0;
	foreach( $tmp['prop'] as $k=>$v)
	{
		$pos = substr($v,0,1);
		if($j==0) {
			if($pos === false || $pos != "$" )
				$str .= "'".$k."' => '".$v."'";
			else 
				$str .= "'".$k."' => ".$v;			
		} else {
			if($pos === false || $pos != "$" )
				$str .= ", '".$k."' => '".$v."'";
			else 
				$str .= ", '".$k."' => ".$v;
		}
		$j++;
	}
	$str .= "));\n";
}

$str .= "// Add events\n";

for($i=0;$i<count($mainarray); $i++) 
{
	$tmp = $mainarray[$i];
	if(isset($tmp['events']) &&  count($tmp['events'])>0  ) {
	foreach( $tmp['events'] as $k=>$v)
	{
		$e1 = "$"."on".$k.$tmp['name']." = <<< ".strtoupper($k.$tmp['name'])."\n";
		$e1 .= html_entity_decode( $v )."\n";
		$e1 .= strtoupper($k.$tmp['name']).";\n";
		$str .= $e1;
		$str .= $the_name."->addEvent('".$tmp['prop']['id']."','".$k."',"."$"."on".$k.$tmp['name'].");\n";
	}
	}
}
// Ajax submit
$str .= "// Add ajax submit events\n";
if(isset ($otherset['ajax_beforeSubmit'])){
if(trim($otherset['ajax_beforeSubmit']))
{
	$str .= "$"."beforeSubmit = <<< BS"."\n";
	$str .= "function(arr, form, options) {"."\n";
	$str .=  html_entity_decode($otherset['ajax_beforeSubmit'])."\n";
	$str .= "}"."\n";
	$str .= "BS;"."\n";
}
}

if(isset($otherset['ajax_beforeSerialize'])) {
if(trim($otherset['ajax_beforeSerialize']))
{
	$str .= "$"."beforeSerialize = <<< BSE"."\n";
	$str .= "function( form, options) {"."\n";
	$str .=  html_entity_decode($otherset['ajax_beforeSerialize'])."\n";
	$str .= "}"."\n";
	$str .= "BSE;"."\n";
}
}

if(isset($otherset['ajax_success'])) {
if(trim($otherset['ajax_success']))
{
	$str .= "$"."success = <<< SU"."\n";
	$str .= "function( response, status, xhr) {"."\n";
	$str .=  html_entity_decode($otherset['ajax_success'])."\n";
	$str .= "}"."\n";
	$str .= "SU;"."\n";
}
}

$ajax = "array(";
if( isset($otherset['ajax_data']) ) {
if( trim($otherset['ajax_data']) ) {
	$apr = jqGridUtils::decode("{".$otherset['ajax_data']."}");
	$new_array = array_map(create_function('$key, $value', 'return "\"". $key."\" => \"".$value."\" ";'), array_keys($apr), array_values($apr));
	$ajax .= "'data'=>array(".implode($new_array, ",")."),"."\n";
}
}
if( isset($otherset['ajax_dataType']) ) {
if( $otherset['ajax_dataType'] ) {
	if($otherset['ajax_dataType'] == 'null') {
		$ajax .= "'dataType'=>". $otherset['ajax_dataType'].","."\n";
	} else {
		$ajax .= "'dataType'=>'". $otherset['ajax_dataType']."',"."\n";
	}
}
}


if( isset($otherset['ajax_resetForm']) ) {
if( $otherset['ajax_resetForm'] ) {
	$ajax .= "'resetForm' =>". $otherset['ajax_resetForm'].","."\n";
}
}
if( isset($otherset['ajax_clearForm']) ) {
if( $otherset['ajax_clearForm'] ) {
	$ajax .= "'clearForm' => ". $otherset['ajax_clearForm'].","."\n";
}
}

if( isset($otherset['ajax_iframeSrc']) ) {
if( $otherset['ajax_iframeSrc'] ) {
	$ajax .= "'iframeSrc' =>". $otherset['ajax_iframeSrc'].","."\n";
}
}

if( isset($otherset['ajax_beforeSubmit']) ) {
if( trim($otherset['ajax_beforeSubmit']) ) {
	$ajax .= "'beforeSubmit' =>'js:'".'.$beforeSubmit'.","."\n";
		
}
}
if( isset($otherset['ajax_beforeSerialize']) ) {
if( trim($otherset['ajax_beforeSerialize']) ) {
	$ajax .= "'beforeSerialize' =>'js:'".'.$beforeSerialize'.","."\n";
}
}
if( isset($otherset['ajax_success']) ) {
if( trim($otherset['ajax_success']) ) {
	$ajax .= "'success' =>'js:'".'.$success'.","."\n";
		//'js:function( response, status, xhr) {\n". html_entity_decode($otherset['ajax_success'])."}',"."\n";
}
}

if(!isset($otherset['ajax_iframe'])) {
	$otherset['ajax_iframe'] = "false";
}
if(!isset($otherset['ajax_forceSync'])) {
	$otherset['ajax_forceSync'] = "false";
}
$ajax .= "'iframe' => ".$otherset['ajax_iframe'].","."\n";
$ajax .= "'forceSync' =>".$otherset['ajax_forceSync'].")";


$str .= $the_name."->setAjaxOptions( ".$ajax." );\n";
if( isset($otherset['javascript']) ) {
if($otherset['javascript'] != "")
{
	$str .= "// Add javascript code\n";
	$e1 = "$"."javascript".$formdata['name']." = <<< ".strtoupper("js".$formdata['name'])."\n";
	$e1 .=  html_entity_decode($otherset['javascript'])."\n";
	$e1 .= strtoupper("js".$formdata['name']).";\n";
	$str .= $e1;
	$str .= $the_name."->setJSCode("."$"."javascript".$formdata['name'].");\n";
}
}
if( isset($otherset['rphpscript']) ) {
	if($otherset['rphpscript'] != "")
	{
		$str .= "// Add phpscript before render code\n";
		$str .=  html_entity_decode($otherset['rphpscript'])."\n";
	}
}


if($demo) {
	$str .= "// Demo mode - no input \n";
	$str .= $the_name."->demo = true;\n"; 
}
$str .= "// Render the form \n";
$str .= "echo ".$the_name."->renderForm(".$prm.");\n";
//$str .= dirname(__FILE__) . DIRECTORY_SEPARATOR."\n";
//$str .= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$str .= "?>";
$file_so_save = $path_info['dirname']."/".substr($path_info['basename'], 0, -(strlen($path_info['extension'])) )."php";
$fh = fopen($file_so_save, 'w');
if( fwrite($fh, $str) === FALSE ) {
	echo "Cannot write to file ($file_so_save)";
}
fclose($fh);
$act = $_POST['act'];
if($act=='code') {
	echo '<div id="PHPCode" style= "font-size:1.3em !important">';
	echo highlight_file($file_so_save, true);
	echo "</div>";
} else if($act =='execute') {
	if(file_exists($file_so_save)) {
		include $file_so_save;
	}
}
?>
