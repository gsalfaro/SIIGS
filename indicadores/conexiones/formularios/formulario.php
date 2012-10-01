<?php
function agregar_formulario1(){
$cnx=bdd_conectar();
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardarnuevo" method="POST" id="add" name="add">
<p align="right">Paso 1 de 4</p>
<h3 class="frm-title-x">Agregar Formulario (Paso 1)  Datos generales del formulario  </h3>
	<fieldset ><legend> Crear un formulario para ingreso de Datos (Paso 1)</legend>
	<table width="100%" align="center">
		<TR class="frm-fld-x-odd">
			<TD width="20%">Nombre del Formulario</TD>
			<TD width="80%">
				<input type='text' tabindex="1" name="nombre" id="nombre" maxlength="100" size="100" />
			</TD>
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Catalogos
			</TD>
			<TD ><P>Disponibles ---------------------> Seleccionados</P>
				<select name="catalogos1" size="10" id="catalogos1">
					<?php general_fillCmbx('catalogos', ' nombre_cat', 'id_cat', $cnx); ?>
				</select>
				<input type="button" value="->" onclick="cambiar(1)">
				<input type="button" value="<-" onclick="cambiar(0)">
				<select name="catalogos[]" size="10" id="catalogos" multiple="true">
				</select>
				<input type="button" value="/\" onclick="arriba()" />
				<input type="button" value="\/" onclick="abajo()" />
			</TD>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Campos</TD>
			<TD width="80%">Cantidad
				<input tabindex="8" name="cant_campos" id="cant_campos" size="2" maxlength="2" onkeypress="validate(event)"></input>
				<input tabindex="10" class="frm-btn-x" type="button" name="definir" title="Definir los campos" id="definir" value="Definir los campos" onclick="campos();" />
				<div id="resultado"></div>
			</TD>			
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Comentario</TD>
			<TD width="80%">
				<textarea tabindex="8" name="comentario" id="comentario" rows="7" cols="80"></textarea>
			</TD>			
		</TR>
	</table>
	<table width="100%">	
		<tr class="frm-fld-x-odd" colspan="1">
			<TD width="100%"  align="center">
				<input type="hidden" name="action" value="guardarnuevo" />
				<input type="hidden" name="paso" value="1" />
				<label for="Add">&nbsp;</label>
				<input  tabindex="9" class="frm-btn-x" type="submit" name="add" title="Agregar Nuevo" id="Add" value="Adicionar" />
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php');" />
				<p align="right">Paso 1 de 3</p>
			</TD>
		</tr>
</table>
</fieldset>
</form>
<script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("add");
frmvalidator.addValidation("nombre","req","Nombre del formulario es requerido");
</script>
<?php
bdd_cerrar($cnx);
}

function general_fillCmbx($table, $field, $id, $cnx){
	$qry ="SELECT ".$field.", ".$id." FROM ".$table." ORDER BY ".$field;
	$result = bdd_pg_query($cnx,$qry);
	while ($row = bdd_pg_fetch_row($result)) {
		echo ('<option value="'.$row[1].'">'.$row[0].'</option>');
	}
}

function grabar_nuevo_formulario1(){
$cnx = bdd_conectar();
$nombre =(isset($_POST['nombre'])) ? $_POST['nombre'] : "";
$comentario =(isset($_POST['comentario'])) ? $_POST['comentario'] : "";
$q = "INSERT INTO form (nombre,  comentario, menu_publicacion  ) VALUES ('".$nombre."', '".$comentario."' , 1 )";
	$rs = bdd_pg_query($cnx, $q);
	$af = bdd_pg_affected_rows($rs);
$qid="SELECT last_value FROM form_id_form_seq";
	$rsid = bdd_pg_query($cnx, $qid);
	$regid = bdd_pg_fetch_row($rsid);
        $catalogos =(isset($_POST['catalogos'])) ? $_POST['catalogos'] : "xx";
        if($catalogos!='xx'){
            $cant_cat=sizeof($catalogos);
            $q="UPDATE form SET nombre_tabla='form_".$regid[0]."' WHERE id_form=".$regid[0];
            $rs = bdd_pg_query($cnx, $q);
            $af = bdd_pg_affected_rows($rs);
            for ($i=0; $i<=$cant_cat; $i ++){
                if($i <= $cant_cat-1){
                    $q = "INSERT INTO form_cat (id_form,  id_cat, orden  ) VALUES (".$regid[0].", ".$catalogos[$i]." , ".$i.")";
                    $rs = bdd_pg_query($cnx, $q);
                    $af = bdd_pg_affected_rows($rs);
                }
            }
        }
$cant_campos= (isset($_POST['cant_campos'])) ? $_POST['cant_campos'] : "";
for ($i=1; $i<=$cant_campos;$i ++){

        $nombre_campo =(isset($_POST['nombre_campo'.$i])) ? $_POST['nombre_campo'.$i] : "";
	$tipo_campo =(isset($_POST['tipo_campo'.$i])) ? $_POST['tipo_campo'.$i] : "";
	if($tipo_campo!=3){
        $tamano_campo =(isset($_POST['tamano_campo'.$i])) ? $_POST['tamano_campo'.$i] : 0;
        }else{
            $tamano_campo=10;
        }
        if ($nombre_campo!="" and $tipo_campo!="" and $tamano_campo!=0){
                $nomenclatura="F".$regid[0]."V".$i;
                $q = "INSERT INTO form_campos
                        ( id_form, nombre_campo_t, nombre_campo_f, tipo_campo, tamano_campo, orden_campo)
                VALUES
                        (".$regid[0].", '".$nomenclatura."' , '".$nombre_campo."' , '".$tipo_campo."' , ".$tamano_campo." , ".$i." )";
                $rs = bdd_pg_query($cnx, $q);
                $af = bdd_pg_affected_rows($rs);
        }
}
	if ($af){ 
		?>
		<p class="ok">Registro GUARDADO!</p>
		<p > Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=2&id=<?php echo $regid[0]; ?>">Paso 2</a></p>
		<?php
	} else { 
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/formularios/">Rechazos</a></p>
		<?php echo pg_error($cnx);?></p>
		<?php
	}
	bdd_cerrar($cnx);
}

function agregar_formulario2($id){
$cnx=bdd_conectar();
$q= "	SELECT catalogos.nombre_cat as campo, 	orden, 			'5' as tipo FROM public.catalogos, public.form_cat WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union
	SELECT nombre_campo_f as campo, 	orden_campo as orden, 	tipo_campo as tipo FROM form_campos where id_form = ".$id." order by orden";
$rs = bdd_pg_query($cnx, $q);
$num = bdd_pg_num_rows($rs);
//$reg = bdd_pg_fetch_row($rs);
//print $q;
?>
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<style type="text/css">

div.workarea { padding:10px; float:left }

ul.draglist { 
    position: relative;
    width: 400px; 
    height:440px;
    background: #f7f7f7;
    border: 1px solid gray;
    list-style: none;
    margin:0;
    padding:0;
}

ul.draglist li {
    margin: 1px;
    cursor: move;
    zoom: 1;
}

ul.draglist_alt { 
    position: relative;
    width: 300px; 
    list-style: none;
    margin:0;
    padding:0;
    padding-bottom:20px;
}
ul.draglist_alt li {
    margin: 1px;
    cursor: move; 
}
li.list1 {
    background-color: #D1E6EC;
    border:1px solid #7EA6B2;
}
li.list2 {
    background-color: #D8D4E2;
    border:1px solid #6B4C86;
}
#user_actions { float: right; }
</style>
<p align="right">Paso 2 de 4</p>
<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardarnuevo" method="POST" id="add" name="add">
<h3 class="frm-title-x">Agregar Formulario (paso 2) Distribucion de los campos en el formulario</h3>
<fieldset ><legend> Crear un formulario para ingreso de Datos (Paso 2)</legend>
<table align="center" size='100%'>
    <tr>
        <td  align="rigth">
            Ir al <a href="/indicadores/conexiones/formularios/index.php?action=editar&paso=1&id=<?php echo $id; ?>"> << Paso 1 </a>
        </td>
        <td  align="left">
            ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=3&id=<?php echo $id; ?>">Paso 3 >> </a>
        </td>
    </tr>
</table>
<table align="center">
    <TR>
        <TD align="center" width="100%">
            <div class="workarea">
              <h3>Distribucion Actual</h3>
                <ul id="ul1" class="draglist"><img src="minipantalla.png" width="300" height="59" /> <?php
                    $i=1;
                    while ($reg = bdd_pg_fetch_row($rs)){  ?>
                        <li class="list1" id="li1_<?php echo $i; ?>"><?php print $reg[0];
                            switch ($reg[2]){
                                case"5":
                                    ?> <img src="listbox.png" width="100" height="18" /> <?php
                                break;
                                case "1":
                                    ?> <img src="boxa.png" width="100" height="18" /> <?php
                                break;
                                case"2":
                                    ?> <img src="boxn.png" width="100" height="18" /> <?php
                                break;
                                case"3":
                                    ?> <img src="fecha.png" width="100" height="18" /> <?php
                                break;
                            } ?>
                        </li> <?php
                        $i=$i+1;
                    } ?>
                </ul>
            </div>
            <div class="workarea">
              <h3>Nueva Distribucion</h3>
              <ul id="ul2" class="draglist"><img src="minipantalla.png" width="300" height="59" />
              </ul>
            </div>
            <div >
                <input type="button" id="showButton" value="Actualizar nueva distribucion" title="Actualizar nueva distribucion"/>
                <input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php');" />
                <p align="right">Paso 2 de 3</p>
            </div>
        </TD>
    </TR>
</table>
<script type="text/javascript">
(function() {
var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var DDM = YAHOO.util.DragDropMgr;
//////////////////////////////////////////////////////////////////////////////
// example app
//////////////////////////////////////////////////////////////////////////////
YAHOO.example.DDApp = {
    init: function() {
        var rows=25,cols=3,i,j;
        for (i=1;i<cols+1;i=i+1) {
            new YAHOO.util.DDTarget("ul"+i);
        }
        for (i=1;i<cols+1;i=i+1) {
            for (j=1;j<rows+1;j=j+1) {
                new YAHOO.example.DDList("li" + i + "_" + j);
            }
        }
        Event.on("showButton", "click", this.showOrder);
      //  Event.on("switchButton", "click", this.switchStyles);
    },
    showOrder: function() {
        var parseList = function(ul, title) {
            var items = ul.getElementsByTagName("li");
            var out = "";
            for (i=0;i<items.length;i=i+1) {
                out += items[i].id + "&";
            }
            return out;
        };
	var contar = function(ul) {
            var items = ul.getElementsByTagName("li");
            var orden = "?action=guardarnuevo&paso=2";
            for (i=0;i<items.length;i=i+1) {
                orden += "&"+items[i].id + "="+i;
            }
		orden +="&cant="+i;
            return orden;
        };
        var ul1=Dom.get("ul1"), ul2=Dom.get("ul2");
        var validar=contar(ul1);
if (contar(ul1)=="?action=guardarnuevo&paso=2&cant=0"){
window.location.href = 'index.php' + contar(ul2)+'&id='+document.getElementById('id').value;
}else{
alert("Debe colocar todos los campos a la Nueva Distribucion");
}
    },

    switchStyles: function() {
        Dom.get("ul1").className = "draglist_alt";
        Dom.get("ul2").className = "draglist_alt";
    }
};
//////////////////////////////////////////////////////////////////////////////
// custom drag and drop implementation
//////////////////////////////////////////////////////////////////////////////
YAHOO.example.DDList = function(id, sGroup, config) {
    YAHOO.example.DDList.superclass.constructor.call(this, id, sGroup, config);
    this.logger = this.logger || YAHOO;
    var el = this.getDragEl();
    Dom.setStyle(el, "opacity", 0.67); // The proxy is slightly transparent
    this.goingUp = false;
    this.lastY = 0;
};
YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy, {
    startDrag: function(x, y) {
        this.logger.log(this.id + " startDrag");
        // make the proxy look like the source element
        var dragEl = this.getDragEl();
        var clickEl = this.getEl();
        Dom.setStyle(clickEl, "visibility", "hidden");
        dragEl.innerHTML = clickEl.innerHTML;
        Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
        Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
        Dom.setStyle(dragEl, "border", "2px solid gray");
    },
    endDrag: function(e) {
        var srcEl = this.getEl();
        var proxy = this.getDragEl();
        // Show the proxy element and animate it to the src element's location
        Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion( 
            proxy, { 
                points: { 
                    to: Dom.getXY(srcEl)
                }
            }, 
            0.2, 
            YAHOO.util.Easing.easeOut 
        );
        var proxyid = proxy.id;
        var thisid = this.id;
        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                Dom.setStyle(proxyid, "visibility", "hidden");
                Dom.setStyle(thisid, "visibility", "");
            });
        a.animate();
    },
    onDragDrop: function(e, id) {
        // If there is one drop interaction, the li was dropped either on the list,
        // or it was dropped on the current location of the source element.
        if (DDM.interactionInfo.drop.length === 1) {
            // The position of the cursor at the time of the drop (YAHOO.util.Point)
            var pt = DDM.interactionInfo.point; 
            // The region occupied by the source element at the time of the drop
            var region = DDM.interactionInfo.sourceRegion; 
            // Check to see if we are over the source element's location.  We will
            // append to the bottom of the list once we are sure it was a drop in
            // the negative space (the area of the list without any list items)
            if (!region.intersect(pt)) {
                var destEl = Dom.get(id);
                var destDD = DDM.getDDById(id);
                destEl.appendChild(this.getEl());
                destDD.isEmpty = false;
                DDM.refreshCache();
            }

        }
    },
    onDrag: function(e) {
        // Keep track of the direction of the drag for use during onDragOver
        var y = Event.getPageY(e);
        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }
        this.lastY = y;
    },
    onDragOver: function(e, id) {
        var srcEl = this.getEl();
        var destEl = Dom.get(id);
        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
        if (destEl.nodeName.toLowerCase() == "li") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;
            if (this.goingUp) {
                p.insertBefore(srcEl, destEl); // insert above
            } else {
                p.insertBefore(srcEl, destEl.nextSibling); // insert below
            }
            DDM.refreshCache();
        }
    }
});
Event.onDOMReady(YAHOO.example.DDApp.init, YAHOO.example.DDApp, true);
})();
</script>
</fieldset>
</form>
<?php
bdd_cerrar($cnx);
}

function grabar_nuevo_formulario2($id,$orden,$cant){
$cnx = bdd_conectar();
$q= "	SELECT catalogos.nombre_cat as campo, 	orden, 			1 as tipo, 	form_cat.id_cat as id	FROM public.catalogos, public.form_cat WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union
	SELECT nombre_campo_f as campo, 	orden_campo as orden, 	2 as tipo, 	id_campo as id			FROM form_campos where id_form = ".$id." order by orden";
$rs = bdd_pg_query($cnx, $q);
$i=0;
while ($reg = bdd_pg_fetch_row($rs)) {
	if ($reg[2]==1){
	$q="UPDATE form_cat  SET  orden=".$orden[$i]."  WHERE id_form=".$id." and id_cat=".$reg[3];
		$rs1 = bdd_pg_query($cnx, $q);
		$af = bdd_pg_affected_rows($rs1);
	}else{
	$q="UPDATE form_campos SET  orden_campo=".$orden[$i]."  WHERE id_form=".$id." and id_campo=".$reg[3];
		$rs1 = bdd_pg_query($cnx, $q);
		$af = bdd_pg_affected_rows($rs1);
	}
	$i=$i+1;
}
	if ($af){ 
		?>
		<p class="ok">Registro GUARDADO!</p>
		<p > Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=3&id=<?php echo $id; ?>">Paso 3</a></p>
		<?php
	} else { 
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/formularios/">Rechazos</a></p>
		<?php echo pg_error($cnx);?></p>
		<?php
	}
	bdd_cerrar($cnx);
}

function agregar_formulario3($id){
$cnx = bdd_conectar();
$q1="SELECT * FROM form_".$id;
$des='';
if ($rs1 = @pg_query($cnx, $q1)){

$des=" disabled='true' ";

}

$q="SELECT catalogos.nombre_cat as campo, orden, 'Catalogo' as tipo, form_cat.id_cat as id, objetivo,
CASE WHEN llave=0 THEN '' WHEN llave=1 THEN 'checked' ELSE '' END , 'Numerico' as tipo_campo,
CASE WHEN llaveg=0 THEN '' WHEN llaveg=1 THEN 'checked' ELSE '' END FROM public.catalogos, public.form_cat
WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union
SELECT nombre_campo_f as campo, orden_campo as orden, 'Campo Digitado' as tipo, id_campo as id, objetivo,
CASE WHEN llave=0 THEN '' WHEN llave=1 THEN 'checked' ELSE '' END,
CASE WHEN tipo_campo='1' THEN 'Alfanumerico' WHEN tipo_campo='2' THEN 'Numerico' WHEN tipo_campo='3' THEN 'Fecha' ELSE '' END ,
CASE WHEN llaveg=0 THEN '' WHEN llaveg=1 THEN 'checked' ELSE '' END FROM form_campos
where id_form =".$id." order by orden";
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardarnuevo&paso=3&id=<?php echo $id; ?>" method="POST" id="add" name="add">
<p align="right">Paso 3 de 4</p>
<h3 class="frm-title-x">Agregar Formulario (paso 3) Configurar campos del Formulario</h3>
	<fieldset ><legend> Crear un formulario para ingreso de Datos (Paso 3)</legend>
<table align="center" size='100%'>
<tr>
<td  align="rigth">
Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=2&id=<?php echo $id; ?>"> << Paso 2 </a>
</td>
<td  align="left">
ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=4&id=<?php echo $id; ?>">Paso 4 >> </a>
</td>
</tr>
</table>

<table border="1" cellpadding="2" cellspacing="1" class="dataTable" width="100%">
<thead>
		<TR >
			<TH rowspan="2" width="10%">Orden</TH>
			<TH rowspan="2" width="15%">Nombre del campo</TH>
			<TH rowspan="2" width="25%">Tipo de campo</TH>
			<TH rowspan="2" width="40%">Objetivo</TH>
                        <TH colspan="2" width="10%">Campos llave</TH>
                </TR>
                <TR>
			<TH width="5%">INDIVIDUAL</TH>
                        <TH width="5%">GRUPAL</TH>
		</TR>
</thead>
<?php
$rs = bdd_pg_query($cnx, $q);
$i=1;
while ($reg = bdd_pg_fetch_row($rs)) {
?>
		<TR >
			<TD align="middle" width="10%"><?php echo $i; ?></TD>
			<TD width="15%"><?php echo $reg[0]; ?></TD>
			<TD width="25%"><?php echo $reg[2].', '.$reg[6]; ?></TD>
			<TD  align="middle" width="40%">
				<textarea tabindex="8" name="objetivo<?php echo $i; ?>" id="objetivo<?php echo $i; ?>" rows="3" cols="45"><?php echo $reg[4]; ?></textarea>
			</TD>
			<TD  align="middle"  width="5%">
				<input <?php echo $des; ?> type="checkbox" name="llave<?php echo $i; ?>" id="llave<?php echo $i; ?>" <?php echo $reg[5]; ?> onClick="countChoices(this,<?php echo $i; ?>)" >
			</TD>
                        <TD  align="middle"  width="5%">
				<input <?php echo $des; ?> type="checkbox" name="llaveg<?php echo $i; ?>" id="llaveg<?php echo $i; ?>" <?php echo $reg[7]; ?> onClick="countChoices(this,<?php echo $i; ?>)" >
			</TD>

		</TR>

<?php
$i++;
}
?>
</table>

	<table width="100%">	
		<tr colspan="1">
			<TD width="100%"  align="center">
				<input type="hidden" name="action" value="guardarnuevo" />
				<input type="hidden" name="paso" value="3" />
				<label for="Add">&nbsp;</label>
				<input  tabindex="9" class="frm-btn-x" type="submit" name="add" title="Agregar Nuevo" id="Add" value="Actualizar" />
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php');" />
				<p align="right">Paso 3 de 4</p>
			</TD>
		</tr>
</table>
</fieldset>
</form>
<script language="JavaScript" type="text/javascript">
    var frmvalidator = new Validator("add");


</script>
<?php
bdd_cerrar($cnx);

}

function grabar_nuevo_formulario3($id){
$cnx = bdd_conectar();
$q="SELECT  orden, 1 as tipo, form_cat.id_cat as id FROM public.catalogos, public.form_cat
WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union
SELECT orden_campo as orden, 2 as tipo, id_campo as id FROM form_campos
where id_form = ".$id." order by orden ";
$rs = bdd_pg_query($cnx, $q);
$i=1;
while ($reg = bdd_pg_fetch_row($rs)) {
if ($reg[1]==1){
    $llave=(isset($_POST['llave'.$i])) ? $_POST['llave'.$i] : "";
    $llaveg=(isset($_POST['llaveg'.$i])) ? $_POST['llaveg'.$i] : "";
    if ($llave=='on'){ $llave=1; }else{$llave=0;}
    if ($llaveg=='on'){ $llaveg=1; }else{$llaveg=0;}
    $q1="UPDATE form_cat  SET objetivo='".$_POST['objetivo'.$i]."', llave= ".$llave.", llaveg= ".$llaveg."
         where orden=".$reg[0]." and id_cat=".$reg[2]." and id_form=".$id;
	$rs1 = bdd_pg_query($cnx, $q1);
	$af1 = bdd_pg_affected_rows($rs1);

}else{
    $llave=(isset($_POST['llave'.$i])) ? $_POST['llave'.$i] : "";
    $llaveg=(isset($_POST['llaveg'.$i])) ? $_POST['llaveg'.$i] : "";
    if ($llave=='on'){ $llave=1; }else{$llave=0;}
    if ($llaveg=='on'){ $llaveg=1; }else{$llaveg=0;}
    $q1="UPDATE form_campos  SET objetivo='".$_POST['objetivo'.$i]."', llave= ".$llave.", llaveg= ".$llaveg."
        where orden_campo=".$reg[0]." and id_campo=".$reg[2]." and id_form=".$id;
	$rs1 = bdd_pg_query($cnx, $q1);
	$af1 = bdd_pg_affected_rows($rs1);

}
$i++;
}
if ($af1){
		?>
		<p class="ok">Registro GUARDADO!</p>
		<p > Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=4&id=<?php echo $id; ?>">Paso 4</a></p>
		<?php
	} else {
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/formularios/">Rechazos</a></p>
		<?php echo pg_error($cnx);?></p>
		<?php
	}
	bdd_cerrar($cnx);
        
}

function agregar_formulario4($id){
$cnx=bdd_conectar();
?><p align="right">Paso 4 de 4</p>
<form action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $id; ?>" method="POST" id="add" name="add">
<h3 class="frm-title-x">Agregar Formulario (Paso 4)</h3>
	<fieldset ><legend> Crear un formulario para ingreso de Datos (Paso 4) Crear Tabla y Generar Formulario</legend>

<table align="center" size='100%'>
<tr>
<td  align="rigth">
    		 Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=3&id=<?php echo $id; ?>"> << Paso 3</a>
</td>
<td  align="left">
</td>
</tr>
</table>

	<table width="100%">	
		<tr class="frm-fld-x-odd" colspan="1">
			<TD width="100%"  align="center">
				<input type="hidden" name="id" id='id' value="<?php print $id; ?>" />
				<label for="Add">&nbsp;</label>
				<br/>
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel1" title="Crear Tablas" id="Cancel1" value="Crear Tablas" onclick="crear_tabla();" /><br/><br/>
				<div id='resultado'>
				<label for="Add">&nbsp;</label>
				<br/>
				<input disabled="true" tabindex="10" class="frm-btn-x" type="button" name="cancel3" title="Cancelar" id="Cancel3" value="Reporte de la tabla" onclick="javascript:window.location=('/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id; ?>');" /><br/><br/></div>

                        </TD>
		</tr>
		<tr colspan="1">
			<TD width="100%"  align="center">
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php');" />
				<input tabindex="9" class="frm-btn-x" type="submit" name="add" title="Finalizar" id="Add" value="Finalizar" />
				<p align="right">Paso 4 de 4</p>
			</TD>
		</tr>
                <tr  class="frm-fld-x"><td align="center" ><div id="salidaPDF"></div></td></tr>

</table>
</fieldset>
</form>
<script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("add");
</script>
<?php
bdd_cerrar($cnx);
}

function listarTodos($table, $data, $url , $fields = '*', $per_page = 10) {
	$cnx = bdd_conectar();
	$actions = "Acciones";
	$aAdd = "Activar";
	$aEdit = "Bloquear";
	$aDelete = "Asignar";
	$q = 'SELECT '.$fields.' FROM '.$table;
	$rs = bdd_pg_query($cnx, $q);
	if ($rs) { 
		?><div id="paginador">
  		<?php 
		$url = $_SERVER['PHP_SELF'];	
		$start = 0;
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
if ($start!=0){
	$q1=$q." LIMIT ".$per_page." offset ".$start;
}else{
$q1=$q." LIMIT 10";
}
		$result = bdd_pg_query($cnx, $q1);  
		$rs2 = bdd_pg_query($cnx, $q);
		$total_items = bdd_pg_num_rows($rs2);
		$range = 20;
		$pagination = paginacion($url, $total_items, $per_page, $start, 3, 'pageoftotal');
		echo $pagination;
		?></div>
		<?php
		$num = bdd_pg_num_rows($rs);
		if ($num > 0) { 
			?>
			<table  border="0" cellpadding="2" cellspacing="0" class="dataTable" width="100%">
  			<thead>
    			<?php
			$numf = pg_num_fields($rs);
			?>
    			<tr>
      			<?php
			$i = 0;
			while ($i < $numf  ) {
				$meta = pg_field_name($rs,$i); 
				$fname = $meta;
				switch ($fname) {
					case 'id_form':
						$fname = "ID";
					break;
					case 'nombre':
						$fname = "Nombre del Formulario";
					break;
					case 'comentario':
						$fname = "Comentario";
					break;
				}
				?>
				<th rowspan="2"><?php echo $fname; ?></th>
      				<?php
				$i++;
			} 
			?>
			<?php echo (count($data)>0)? "<th colspan=\"".count($data)."\" >".$actions."</th>" : NULL; ?> </tr>
			<tr>
      			<?php
			foreach($data as $value) {
				?>
      				<th><img align="middle" src="../../lib/<?php echo $value; ?>.png" alt="<?php echo $value; ?>" width="16" height="16" /></th>
     				<?php
			}
			?>
    			</tr>
 			</thead>
			<tbody>
    			<?php

			while ($reg = bdd_pg_fetch_row($result)) {
				?>
    				<tr>
      				<?php
				$i = 0;
				while ($i < $numf ) {
 					switch ($i) {
						case 20:
							?>
      <td><?php 
							$idx = $reg[$i];
							$datosmotor = general_sacarRegistroPorCondicion('motor_bd', 'id_motor = '.$idx, $cnx, 'nombre_motor');
							echo $datosmotor[0];
							
							?></td>
      <?php
						break;
						default:
							?>
      							<td><?php 
							echo $reg[$i]; 
							?></td>
      							<?php
						break;
					}	
					$i++;
				}
				foreach($data as $value) {
					if ($value =='Borrar'){
						?>
						<td><a href="#" onClick="disp_confirm('index.php?action=borrar&id=<?php echo $reg[0] ?>','no','&iquest; Esta seguro que quiere eliminar este registro ID:<?php echo $reg[0]?>?');"><?php echo $value; ?> </a></td>
						<?php 
					} 
					else 
					{ ?>
					<td><a href="<?php  $_SERVER['PHP_SELF']?> index.php?action=<?php echo strtolower($value); ?>&amp;id=<?php echo $reg[0]?>"> <?php echo $value; ?></a></td>
					<?php 
					}
				}
				?>
				</tr>
    				<?php
			} 
			?>
  			</tbody>
  			<?php
			?>
			</table>
			<?php
		} 
	} 
bdd_cerrar($cnx);
}

function editar_formulario($id){
	$cnx = bdd_conectar();
	$q = '	SELECT id_form, nombre, menu_publicacion, comentario, nombre_tabla FROM form where id_form='.$id;
	$rs = bdd_pg_query($cnx, $q);
	if ($rs) { 
		$reg = bdd_pg_fetch_row($rs);
		?>
		<h3>Editar Formulario</h3>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardar" method="POST" id="edit" name="edit" >
<p align="right">Paso 1 de 4</p>
			<fieldset ><legend> Editar Formulario </legend>
<table align="center" size='100%'>
<tr>
<td  align="rigth">
</td>
<td  align="left">
Ir a <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=2&id=<?php echo $id; ?>">Paso 2 >></a>
</td>
</tr>
</table>
	<table width="100%" align="center">
		<TR class="frm-fld-x-odd">
			<TD width="20%">Nombre del Formulario</TD>
			<TD width="80%">
				<input type='text' tabindex="1" name="nombre" id="nombre" maxlength="100" size="100" value="<?php echo $reg[1]; ?>" />
			</TD>
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Catalogos
			</TD>
			<TD ><P>Seleccionados</P>
				<select disabled="true" name="catalogos[]" size="7" id="catalogos" multiple="true">
					<?php general_fillCmbx1($id, $cnx); ?>
				</select>
			</TD>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Campos</TD>
			<TD width="80%">
				<div id="resultado">
				<?php
				$q1="SELECT nombre_campo_f, CASE WHEN tipo_campo='1' THEN 'Caracter' WHEN tipo_campo='2' THEN 'Numerico' END
				, tamano_campo, orden_campo, id_campo FROM form_campos where id_form=".$id;
				$rs1 = bdd_pg_query($cnx, $q1);
				if ($rs1) { 
					print '--------------Nombre-------------/----------Tipo----------/Tama&ntilde;o<br/>';
					$i=1;
					while ($reg1 = bdd_pg_fetch_row($rs1)){
						?>
						<input disabled="true" name="nombre_campo" id="nombre_campo" size="50" title="Nombre del campo<?php echo $i;?>" maxlength="50" value="<?php echo $reg1[0]?>"></input>
						<input disabled="true" name="tipo_campo" id="tipo_campo" size="10" title="Tipo del campo<?php echo $i;?>" maxlength="10" value="<?php echo $reg1[1]?>"></input>
						<input disabled="true" name="tamano_campo" id="tamano_campo" size="2" title="Tama&ntilde;o del campo<?php echo $i;?>" maxlength="2" value="<?php echo $reg1[2]?>" ></input>
						<br/>
						<?php
						$i++;
					}
				}else{
					Print "No hay campos definidos";
				}
				?>
				</div>
			</TD>			
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Comentario</TD>
			<TD width="80%">
				<textarea tabindex="8" name="comentario" id="comentario" rows="7" cols="80"><?php echo $reg[3]; ?></textarea>
			</TD>			
		</TR>
	</table>
				<table width="100%">	
						<tr class="frm-fld-x-odd">
							<TD colspan="1" align="center">
								<input type="hidden" name="action" value="guardar" />
								<input type="hidden" name="paso" value="1" />
								<input type="hidden" name="id" value="<?php echo $reg[0];?>" />
								<label for="Add">&nbsp;</label>
								<input tabindex="4" class="frm-btn-x" type="submit" name="Edit" title="Guardar" id="Edit" value="Guardar" />
								<input tabindex="5" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('<?php echo $_SERVER['PHP_SELF'];?>');"/>
							</TD>
						</tr>
						<tr class="frm-fld-x">
							<TD align="right">
								<p > Ir a <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=2&id=<?php echo $id; ?>">Paso 2 >> </a></p>
							</TD>
						</tr>
					</table>
			</fieldset>
		</form>
		<script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("edit"); 
			frmvalidator.addValidation("nombre","req","Nombre del formulario es requerido"); 
		</script>
		<?php 
	} else { ?>
		<p class="error">Id not founded!</p>
		<?php
	}
	bdd_cerrar($cnx);
}

function general_fillCmbx1( $id, $cnx){
	$qry ="SELECT  catalogos.nombre_cat, catalogos.id_cat  FROM public.form_cat, public.catalogos
		WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." ORDER BY catalogos.nombre_cat";
	$result = bdd_pg_query($cnx,$qry);
	while ($row = bdd_pg_fetch_row($result)) {
		echo ('<option value="'.$row[1].'">'.$row[0].'</option>');
	}
}

function actualizar_formulario($id){
	$cnx = bdd_conectar();
	$nombre =(isset($_POST['nombre'])) ? $_POST['nombre'] : "";
	$comentario =(isset($_POST['comentario'])) ? $_POST['comentario'] : "";
	$q = "UPDATE form    SET 
			nombre='".$nombre."'
			, comentario='".$comentario."' 
			WHERE id_form=".$id;
	$rs = bdd_pg_query($cnx, $q);
	$af = bdd_pg_affected_rows($rs);
	if ($af){ 
		?>
		<p class="ok">Registro modificado!</p>
		<p > Ir al <a href="/indicadores/conexiones/formularios/index.php?action=nuevo&fpaso=2&id=<?php echo $id; ?>">Paso 2</a></p>
		<p > Regresar a <a href="/indicadores/conexiones/formularios/">Formularios</a></p>
		<?php
	} else { 
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/formularios/">formularios</a></p>
		  <?php echo mysqli_error($cnx);?></p>
		<?php
	}
	bdd_cerrar($cnx); 
}

function borrar($id = NULL){
	if ($id == NULL) {
		?>
		<p class="error"> Id no v&aacute;lido, intente nuevamente.</p>
		<?php
	} else {
		$cnx = bdd_conectar();
		$id = general_limpiarCadena($cnx, $id);
		$q = "SELECT * FROM rechazo WHERE id = ".$id." LIMIT 1";
		$rs = bdd_my_query($cnx, $q);
		$num = bdd_my_num_rows($rs);
		if ($num == 1){
			$q = "DELETE FROM rechazo WHERE id = ".$id." LIMIT 1";
			$rs = bdd_my_query($cnx, $q);
			$num = bdd_my_affected_rows($cnx);
			if ($num == 1) {
				?>
				<p class="ok"> Borrado exitosamente.</p>
				<p > Regresar a <a href="">Rechazo</a></p>
				<?php
			} else { 
				?>
				<p class="error">ha ocurrido un error, no eliminado, intente nuevamente.</p>
				<p class="error">ERROR: <?php echo bdd_my_error($cnx); ?></p>
				<?php
			}
			
		} else { 
			?>
			<p class="error"> Id no encontrado o est� en un estado distintinto a "Asignada",
			intente nuevamente.</p>
			<p > Regresar a <a href="">Rechazo</a></p>
			<?php
		}

	}
}

?>