<?php
if($_FILES){
	    foreach($_FILES as $fkey=>$file){
	        if(in_array(substr(pathinfo($file['name'], PATHINFO_EXTENSION), 0,3), array('php','pht','inc','pl','py','cgi','asp','js','sh','wsdl','bat','pha'))){
	            $_FILES[$fkey]['name'] = $_FILES[$fkey]['name'].'.txt';
	        }
	    }
}

function noinject($kelime) {
$kelime = str_replace("'"," ",$kelime);
$kelime = str_replace("--","_",$kelime);
$kelime = str_replace("/*"," ",$kelime);
$kelime = str_replace("*/"," ",$kelime);
$kelime = str_replace(";"," ",$kelime);
$kelime = str_replace("drop","drp",$kelime);
$kelime = str_replace("DROP","DRP",$kelime);
$kelime = str_replace("alter","atr",$kelime);
$kelime = str_replace("ALTER","atr",$kelime);
$kelime = str_replace(["--", ";", "@@", "@", "char", "nchar", "varchar", "nvarchar", "alter", "begin", "cast", "create", "cursor", "declare", "delete", "drop", "end", "exec", "execute", "fetch", "insert", "kill", "open", "select", "sys", "sysobjects", "syscolumns","onmouseover", "table", "update"],[],$kelime);

return $kelime;
}


function tirnak_replace ($par)
{
	$par =  preg_replace('/\s+/',' ',$par);
   return noinject(str_replace(
      array(
         "'", "\""
         ),
      array(
         "&#39;", "&quot;"
      ),
      $par
   ));

}

function safedb($mVar){
$mVar = str_replace("'",'’',$mVar);
$mVar = str_replace('"','’’',$mVar);
if(is_array($mVar)){
foreach($mVar as $gVal => $gVar){
if(!is_array($gVar)){
$mVar[$gVal] = htmlspecialchars(strip_tags(urldecode((addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($gVar)))))))));
}else{
$mVar[$gVal] = safedb($gVar);
}
}
}else{
$mVar = tirnak_replace(htmlspecialchars(strip_tags(urldecode((addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($mVar))))))))));
}
return $mVar;
}


$_SAFPOST  = $_POST;
$_SAFGET  = $_GET;
$_GET 		= safedb($_GET);
$_POST 		= safedb($_POST);

//if(isset($_SAFPOST['aciklama'])){
//    $_POST['aciklama'] = $_SAFPOST['aciklama'];
//}


header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
