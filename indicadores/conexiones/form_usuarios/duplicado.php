<?php
include_once("form_usuarios.php");
include_once("../../addons/general/general.php");
$cnx = bdd_conectar();
$dato= 	(isset($_REQUEST['dato'])) ? $_REQUEST['dato'] : NULL;
$id= 	(isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
$campo=	(isset($_REQUEST['campo'])) ? $_REQUEST['campo'] : NULL;
if($dato!= null){
    $qf="SELECT ".$campo."  FROM form_".$id." WHERE ".$campo." = ".$dato;
    $rsf = bdd_pg_query($cnx, $qf);
    $regct = bdd_pg_fetch_row($rsf);
    $num = bdd_pg_num_rows($rsf);
    if($num>=1){ 
       print "<p class='blink' >El Dato Ya Existe</p>";

    }
}
?>
