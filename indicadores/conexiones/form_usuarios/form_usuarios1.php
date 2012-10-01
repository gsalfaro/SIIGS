<?php
function agregar_conexion(){
$cnx=bdd_conectar();
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardarnuevo" method="POST" id="add" name="add">
<h3 class="frm-title-x">Agregar Conexi&oacute;n a una Base de Datos</h3>
	<fieldset ><legend> Ingreso de Conexi&oacute;n a una Base de Datos</legend>
	<table width="100%" align="center">
		<TR class="frm-fld-x-odd">
			<TD width="20%">Nombre de la conexi&oacute;n</TD>
			<TD width="80%">
				<input type='text' tabindex="1" name="nombre_conexion" id="nombre_conexion" maxlength="100" size="100" />
			</TD>
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Motor de la Base de Datos</TD>
			<TD width="80%">
				<select name="id_motor" tabindex="2" size="1" id="id_motor">
					<option selected="selected"> [Seleccione Uno ..]</option>
					<?php general_fillCmb('motor_bd', 'id_motor', 'id_motor', 'nombre_motor', NULL, NULL, $cnx); ?>
				</select>
			</TD>
		</TR>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Puerto</TD>
			<TD>
				<input type='text' tabindex="1" name="puerto" id="puerto" maxlength="5" size="5" onkeypress='validate(event)' />
			</TD>
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Instancia</TD>
			<TD>
				<input type='text' tabindex="1" name="instancia" id="instancia" maxlength="50" size="50" />
			</TD>
		</TR>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Direcci&oacute;n IP</TD>
			<TD width="80%">
				<input type='text' tabindex="3" name="ip" id="ip" onBlur="validarip()" size="15" maxlength="15" />

			</TD>
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%">Usuario</TD>
			<TD width="80%">
				<input type='text' tabindex="4" name="usuario" id="usuario" size="25" maxlength="25"/>
			</TD>
		</TR>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Clave</TD>
			<TD width="80%">
				<input type="password" tabindex="5" name="clave" id="clave" onblur="compararps()" />
		</TR>
		<TR class="frm-fld-x">
			<TD width="20%" align="right">Confirmar Clave</TD>
			<TD width="80%">
				<div id="resultado1">
				<input type="password" disabled="true" tabindex="5" name="clave1" id="clave1" onblur="comparar1()"/>
				</div>
				<div id='resultado2'>
				</div>
			</TD>
		</TR>
		<TR class="frm-fld-x-odd">
			<TD width="20%">Nombre de la Base de Datos</TD>
			<TD width="80%">
				<input type='text' tabindex="6" name="nombre_base_datos" id="nombre_base_datos" size="50" maxlength="50" />
				<div id="resultado"></div>
				<input  tabindex="7" class="frm-btn-x" type="button" name="probar" title="Verifica si hace conexi&oacute;n con los datos antes escritos" id="Probar Conexi&oacute;n" value="Probar Conexi&oacute;n" onclick="probar_conexion()" />
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
				<label for="Add">&nbsp;</label>
				<input tabindex="9" class="frm-btn-x" type="submit" name="add" title="Agregar Nuevo" id="Add" value="Adicionar" />
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php');" />
				
			</TD>
		</tr>
</table>
</fieldset>
</form>
<script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("add");
frmvalidator.addValidation("nombre_conexion","req","Nombre de la conexion es requerido"); 
frmvalidator.addValidation("ip","req","La direccion IP es requerida"); 
frmvalidator.addValidation("usuario","req","El nombre del usuario es requerido"); 
frmvalidator.addValidation("clave","req","La clave es requerida"); 
frmvalidator.addValidation("nombre_base_datos","req","El nombre de la base de datos es requerido"); 
frmvalidator.addValidation("id_motor","dontselect=0","El nombre del motor de la base de datos es requerido"); 
</script>
<?php
bdd_cerrar($cnx);
}


function grabar_nuevo_conexion(){
$cnx = bdd_conectar();
$tem =(isset($_POST['nombre_conexion'])) ? $_POST['nombre_conexion'] : "";
$puerto =(isset($_POST['puerto'])) ? $_POST['puerto'] : "";
$instancias =(isset($_POST['instancia'])) ? $_POST['instancia'] : "";
$q = "INSERT INTO conexion (nombre_conexion, ip, usuario, clave, nombre_base_datos, id_motor, comentario, puerto, instancia  ) VALUES (
  '".$tem."', 
  '".$_POST['ip']."', 
  '".$_POST['usuario']."', 
  '".$_POST['clave']."', 
  '".$_POST['nombre_base_datos']."', 
  ". $_POST['id_motor'] .", 
  '".$_POST['comentario']."',
  '". $puerto ."',
  '". $instancias ."')";
	$rs = bdd_pg_query($cnx, $q);
	$af = bdd_pg_affected_rows($rs);
	if ($af){ 
		?>
		<p class="ok">Registro modificado!</p>
		<p > Regresar a <a href="/indicadores/conexiones/conexion/">Formulario</a></p>
		<?php
	} else { 
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/conexion/">Formulario</a></p>
		<?php echo pg_error($cnx);?></p>
		<?php
	}
	bdd_cerrar($cnx);
}

function titulo($id){
$cnx1=bdd_conectar();
$q1="SELECT id_form, nombre, menu_publicacion, comentario, nombre_tabla  FROM form where id_form=".$_SESSION['id'];
$rs1 = bdd_pg_query($cnx1, $q1);
$reg1 = bdd_pg_fetch_row($rs1);
bdd_cerrar($cnx1);
return $reg1[1];
}

function listarTodos($table, $data, $url , $fields = '*', $id, $per_page = 10) {
	$cnx = bdd_conectar();
	$actions = "Acciones";
	$aAdd = "Activar";
	$aEdit = "Bloquear";
	$aDelete = "Asignar";
	$q = 'SELECT '.$fields.' FROM '.$table;
	$rs = bdd_pg_query($cnx, $q);
	if ($rs) { 
		?><div id="paginador"> <?php 
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
		?></div> <?php
		$num = bdd_pg_num_rows($rs);
		if ($num > 0) { 
			?> <table  border="0" cellpadding="2" cellspacing="0" class="dataTable" width="100%"> <thead> <?php
			$numf = pg_num_fields($rs);
			?> <tr> <?php
			$i = 0;
			while ($i < $numf  ) {
				$meta = pg_field_name($rs,$i); 
				$fname = buscar_nombre_campo($id,$meta,$cnx);
				?> <th rowspan="2"><?php echo $fname; ?></th> <?php
				$i++;
			} 
			?> <?php echo (count($data)>0)? "<th colspan=\"".count($data)."\" >".$actions."</th>" : NULL; ?> </tr> <tr> <?php
			foreach($data as $value) {
				?> <th><img align="middle" src="../../lib/<?php echo $value; ?>.png" alt="<?php echo $value; ?>" width="16" height="16" /></th> <?php
			}
			?> </tr> </thead> <tbody> <?php
			$i = 0;
				$qct="SELECT form_cat.orden FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id;
				$rsct = bdd_pg_query($cnx, $qct);
				$n=0;
				while ($regct = bdd_pg_fetch_row($rsct)) {
					$c[$n]=$regct[0];
					$n=$n+1;
				}
				$numct = bdd_pg_num_rows($rsct);
			while ($reg = bdd_pg_fetch_row($result)) {
				?>	<tr>	<?php

				while ($i < $numf ) {

echo campo($reg[$i],$i);
print ','.$c[$i];

					if ($i == $c[$i]){
	//					$qct="SELECT catalogos.nombre_tabla, catalogos.campo_llave, catalogos.nombre_campo_descripcion FROM public.form_cat, public.catalogos 
	//					WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." and form_cat.orden=".$c[$h];
	//					$rsct = bdd_pg_query($cnx, $qct);
	//					$regct = bdd_pg_fetch_row($rsct)
						?> <td> <?php 
	//					$idx = $reg[$i];
echo 'catalogo'.$i;
	//					$datosmotor = general_sacarRegistroPorCondicion($regct[0], $regct[1].' = '.$idx, $cnx, $regct[2]);
	//					echo $datosmotor[0];
						?> </td> <?php
					}else{

						?> <td> <?php 
						echo $reg[$i]; 
						?> </td> <?php


}
//				}
 	/**				switch ($i) {
						case 20:
							?> <td><?php 
							$idx = $reg[$i];
							$datosmotor = general_sacarRegistroPorCondicion('motor_bd', 'id_motor = '.$idx, $cnx, 'nombre_motor');
							echo $datosmotor[0];
							?></td><?php
						break;

						default:
							?>
      							<td><?php 
							echo $reg[$i]; 
							?></td>
      							<?php
						break;
					}*/	
					$i++;
				}
				foreach($data as $value) {
					if ($value =='Borrar'){
						?>	<td><a href="#" onClick="disp_confirm('index.php?action=borrar&id=<?php echo $reg[0] ?>','no','&iquest; Esta seguro que quiere eliminar este registro ID:<?php echo $reg[0]?>?');"><?php echo $value; ?> </a></td>	<?php 
					} 
					else 
					{ 
					?>	<td><a href="<?php  $_SERVER['PHP_SELF']?> index.php?action=<?php echo strtolower($value); ?>&amp;id=<?php echo $reg[0]?>"> <?php echo $value; ?></a></td>	<?php 
					}
				}
				?>	</tr>	<?php
			} 
			?>	</tbody>	<?php
			?>	</table>	<?php
		} 
	} 
bdd_cerrar($cnx);
}


function campo(){




}











function buscar_nombre_campo($id,$campo,$cnx){
	if ($campo=='id_'.$id){
		return 'ID';
	}else{
		$qn=" SELECT catalogos.campo_llave AS nt, catalogos.nombre_cat as nf FROM public.catalogos, public.form_cat 
		WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." AND catalogos.campo_llave = '".$campo."'
		union SELECT   form_campos.nombre_campo_t as nt, form_campos.nombre_campo_f as nf FROM public.form_campos 
		WHERE   form_campos.id_form = ".$id." AND  form_campos.nombre_campo_t = '".strtoupper($campo)."'";
		$rsn = bdd_pg_query($cnx, $qn);
		$regn = bdd_pg_fetch_row($rsn);
		return $regn[1];
	}
}


function editar_conexion($id){
	$cnx = bdd_conectar();
	$q = '	SELECT 
			conexion.id_conexion, 
			conexion.nombre_conexion, 
			conexion.id_motor, 
			conexion.puerto, 
			conexion.instancia,
			conexion.ip, 
			conexion.usuario, 
			conexion.clave, 
			conexion.nombre_base_datos, 
			conexion.comentario 
		FROM 
			public.conexion 
		where 
			conexion.id_conexion='.$id;
	$rs = bdd_pg_query($cnx, $q);
	if ($rs) { 
		$reg = bdd_pg_fetch_row($rs);
		?>
		<h3>Editar Una Conexion</h3>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardar" method="POST" id="edit" name="edit" >
			<fieldset ><legend> Editar Rechazo </legend>
				<table width="100%" align="center">
					<TR class="frm-fld-x-odd">
						<TD width="20%">Nombre de la conexi&oacute;n</TD>
						<TD width="80%">
							<input type='text' tabindex="1" name="nombre_conexion" id="nombre_conexion" value="<?php echo $reg[1];?>" maxlength="100" size="100" />
						</TD>
					</TR>
					<TR class="frm-fld-x">
						<TD width="20%">Motor de la Base de Datos</TD>
						<TD width="80%">
							<select name="id_motor" tabindex="2" size="1" id="id_motor">
								<option selected="selected"> [Seleccione Uno ..]</option>
								<?php general_fillCmb('motor_bd', 'id_motor', 'id_motor', 'nombre_motor',$reg[2], NULL, $cnx); ?>
							</select>
						</TD>
					</TR>
					<TR class="frm-fld-x-odd">
						<TD width="20%">Puerto</TD>
						<TD>
							<input type='text' tabindex="1" name="puerto" id="puerto" maxlength="5" value="<?php echo $reg[3];?>" size="5" onkeypress='validate(event)' />
						</TD>
					</TR>
					<TR class="frm-fld-x">
						<TD width="20%">Instancia</TD>
						<TD>
							<input type='text' tabindex="1" name="instancia" id="instancia" value="<?php echo $reg[4];?>" maxlength="50" size="50" />
						</TD>
					</TR>
					<TR class="frm-fld-x-odd">
						<TD width="20%">Direcci&oacute;n IP</TD>
						<TD width="80%">
							<input type='text' tabindex="3" name="ip" id="ip" value="<?php echo $reg[5];?>" size="15" maxlength="15" onBlur="validarip()"/>
						</TD>
					</TR>
					<TR class="frm-fld-x">
						<TD width="20%">Usuario</TD>
						<TD width="80%">
							<input type='text' tabindex="4" name="usuario" id="usuario" value="<?php echo $reg[6];?>" size="25" maxlength="25"/>
						</TD>
					</TR>
					<TR class="frm-fld-x-odd">
						<TD width="20%">Clave</TD>
						<TD width="80%">
							<input type="password" tabindex="5" name="clave" id="clave" value="<?php echo $reg[7];?>" onblur="compararps()" />
					</TR>
					<TR class="frm-fld-x">
						<TD width="20%" align="right">Confirmar Clave</TD>
						<TD width="80%">
							<div id="resultado1">
							<input type="password" disabled="true" tabindex="5" name="clave1" id="clave1" onblur="comparar1()"/>
							</div>
							<div id='resultado2'>
							</div>
						</TD>
					</TR>
					<TR class="frm-fld-x-odd">
						<TD width="20%">Nombre de la Base de Datos</TD>
						<TD width="80%">
							<input type='text' tabindex="6" name="nombre_base_datos" id="nombre_base_datos" value="<?php echo $reg[8];?>" size="50" maxlength="50" />
							<div id="resultado"></div>
							<input  tabindex="7" class="frm-btn-x" type="button" name="probar" title="Verifica si hace conexi&oacute;n con los datos antes escritos" id="Probar Conexi&oacute;n" value="Probar Conexi&oacute;n" onclick="probar_conexion()" />
						</TD>
					</TR>
					<TR class="frm-fld-x">
						<TD width="20%">Comentario</TD>
						<TD width="80%">
							<textarea tabindex="8" name="comentario" id="comentario" rows="7" cols="80"><?php echo $reg[9];?></textarea>
						</TD>			
					</TR>
				</table>
				<table width="100%">	
						<tr class="frm-fld-x-odd">
							<TD colspan="1" align="center">
								<input type="hidden" name="action" value="guardar" />
								<input type="hidden" name="id" value="<?php echo $reg[0];?>" />
								<label for="Add">&nbsp;</label>
								<input tabindex="4" class="frm-btn-x" type="submit" name="Edit" title="Button: Edit" id="Edit" value="Guardar" />
								<input tabindex="5" class="frm-btn-x" type="button" name="cancel" title="Button: Cancel" id="Cancel" value="Cancelar" onclick="javascript:window.location=('<?php echo $_SERVER['PHP_SELF'];?>');"/>
							</TD>
						</tr>
					</table>
			</fieldset>
		</form>
		<script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("edit"); 
			frmvalidator.addValidation("nombre_conexion","req","Nombre de la conexion es requerido"); 
			frmvalidator.addValidation("ip","req","La direccion IP es requerida"); 
			frmvalidator.addValidation("usuario","req","El nombre del usuario es requerido"); 
			frmvalidator.addValidation("clave","req","La clave es requerida"); 
			frmvalidator.addValidation("nombre_base_datos","req","El nombre de la base de datos es requerido"); 
			frmvalidator.addValidation("id_motor","dontselect=0","El nombre del motor de la base de datos es requerido"); 
		</script>
		<?php 
	} else { ?>
		<p class="error">Id not founded!</p>
		<?php 
	}
	bdd_cerrar($cnx);
}

function actualizar_conexion($id){
	$cnx = bdd_conectar();
$nombre_conexion =(isset($_POST['nombre_conexion'])) ? $_POST['nombre_conexion'] : "";
$id_motor =(isset($_POST['id_motor'])) ? $_POST['id_motor'] : "";
$puerto =(isset($_POST['puerto'])) ? $_POST['puerto'] : "";
$instancia = (isset($_POST['instancia'])) ? $_POST['instancia'] : "";
$ip =(isset($_POST['ip'])) ? $_POST['ip'] : "";
$usuario =(isset($_POST['usuario'])) ? $_POST['usuario'] : "";
$clave =(isset($_POST['clave'])) ? $_POST['clave'] : "";
$nombre_base_datos =(isset($_POST['nombre_base_datos'])) ? $_POST['nombre_base_datos'] : "";
$comentario =(isset($_POST['comentario'])) ? $_POST['comentario'] : "";

	$q = "UPDATE public.conexion SET
			nombre_conexion= 	'".$nombre_conexion."'
		,	id_motor= 		".$id_motor."
		,	puerto= 		'".$puerto."'
		,	instancia= 		'".$instancia."'
		,	ip= 			'".$ip."'
		,	usuario= 		'".$usuario."'
		,	clave= 			'".$clave."'
		,	nombre_base_datos= 	'".$nombre_base_datos."'
		,	comentario= 		'".$comentario."'
		where 
			id_conexion=".$id;
//print $q;

	$rs = bdd_pg_query($cnx, $q);
	$af = bdd_pg_affected_rows($rs);
	//echo pg_error($cnx);
	if ($af){ 
		?>
		<p class="ok">Registro modificado!</p>
		<p > Regresar a <a href="/indicadores/conexiones/conexion/">Formulario</a></p>
		<?php
	} else { 
		?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/conexion/">Formulario</a></p>
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