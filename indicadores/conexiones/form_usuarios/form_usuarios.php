<?php
function agregar_formulario_usuario($id){
$cnx=bdd_conectar();
$titulo=titulo($id);
$tag='';
$qct="SELECT orden as orden, 1 as tipo, catalogos.nombre_cat as nombre , nombre_tabla as tabla,	campo_llave as llave,
	nombre_campo_descripcion as descripcion,  catalogos.codigo as codigo, 10 as tamano, 'tipoc' as tipoc, llave as valido
	FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." 
	union 
	SELECT orden_campo as orden, 2 as tipo, nombre_campo_f as nombre , 'tabla' as tabla,  nombre_campo_t as llave,	
	'llave' as descripcion, 'codigo' as codigo, tamano_campo as tamano, tipo_campo as tipoc, llave as valido
        FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsct = bdd_pg_query($cnx, $qct);
	?><form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardarnuevo&id=<?php echo $id;?>" method="POST" id="add" name="add">
	<h3 class="frm-title-x">Agregar <?php echo $titulo; ?></h3>
	<fieldset ><legend> Ingreso de <?php echo $titulo; ?> </legend>
         <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
	<table width="100%" align="center"><?php
	while ($regct = bdd_pg_fetch_row($rsct)){
		if ($regct[1]==2){
                    if ($regct[8]!=3){
                        if ($regct[8]==2){
                            $validar= "validate(event)";
                        }else{
                            $validar= "";
                        }
                        if ($regct[9]==1){
                            $validar1= "onblur='duplicado(". $regct[4].", ".$id.")'";
                        }else{
                            $validar1= '' ;
                        } ?>
			<TR class="frm-fld-x-odd">
				<TD width="20%"><?php echo $regct[2]; ?></TD>
				<TD width="80%">
					<input type='text' tabindex="1" name="<?php echo $regct[4]; ?>" id="<?php echo $regct[4]; ?>" 
                                               maxlength="<?php echo $regct[7];?>" size="<?php echo $regct[7];?>" onkeypress='<?php  echo $validar; ?>'
                                               <?php echo $validar1;?> />  <div id='<?php echo $regct[4].$id; ?>'></div>
                                </TD> 
			</TR> <?php
                  }else{    ?>
			<TR class="frm-fld-x-odd">
				<TD width="20%"><?php echo $regct[2]; ?></TD>
				<TD width="80%">
                               		<input tabindex="3" type="text" name="<?php echo $regct[4]; ?>" value="<?php echo date('d/m/Y'); ?>"
                                               title="Text input: fecha_rechazo" id="<?php echo $regct[4]; ?>" size="10" maxlength="20" />
                                        <input type="image" src="../../lib/date.png"  id="f_trigger_a"/>
                                        <script type="text/javascript">
                                        Calendar.setup({
                                        inputField     :    "<?php echo $regct[4]; ?>",    // id of the input field
                                        ifFormat       :    "%d/%m/%Y", 	//%p for pm       // format of the input field
                                        showsTime      :    false,            	// will display a time selector
                                        button         :    "f_trigger_a",   	// trigger for the calendar (button ID)
                                        singleClick    :    false,           	// double-click mode
                                        step           :    1                	// show all years in drop-down boxes (instead of every other year as default)
                                         });
                                        </script>
                                        <small>dd/mm/yyyy</small><br />
                                 </TD>
			</TR> <?php
                    }
		}else{ 	
		    if ($regct[9]==1){
                        $validar1= "onblur='duplicado(". $regct[4].", ".$id.")'";
                    }else{
                        $validar1= '' ;
                    } ?>
			<TR class="frm-fld-x-odd">
				<TD width="20%"><?php echo $regct[2]; ?></TD>
				<TD width="80%">
					<select name="<?php echo $regct[4]; ?>" tabindex="2" size="1" id="<?php echo $regct[4]; ?>" <?php echo $validar1;?> >
						<option selected="selected"> [Seleccione Uno ..]</option>
						<?php general_fillCmb($regct[3], $regct[4], $regct[4], $regct[5], NULL, NULL, $cnx); ?>
					</select> <div id='<?php echo $regct[4].$id; ?>'></div>
				</TD>
			</TR> <?php
		}
	}?>
	</table>
	<table width="100%">	
		<tr class="frm-fld-x-odd" colspan="1">
			<TD width="100%"  align="center">
				<input type="hidden" name="action" value="guardarnuevo" />
				<label for="Add">&nbsp;</label>
				<input tabindex="9" class="frm-btn-x" type="submit" name="add" title="Agregar Nuevo" id="Add" value="Adicionar" />
				<input tabindex="10" class="frm-btn-x" type="button" name="cancel" title="Cancelar" id="Cancel" value="Cancelar" onclick="javascript:window.location=('index.php?id=<?php echo $id; ?>');" />
				
			</TD>
		</tr>
	</table>
	</fieldset>
	</form>
    <script language="JavaScript" type="text/javascript">var frmvalidator = new Validator("add"); <?php
        $rsct1 = bdd_pg_query($cnx, $qct);
        while ($regct = bdd_pg_fetch_row($rsct1)){
           if ($regct[1]==1 ){ ?>
                frmvalidator.addValidation("<?php echo $regct[4]; ?>","dontselect=0","Es requerido: <?php echo $regct[2]; ?>"); <?php
           }else{ ?>
                frmvalidator.addValidation("<?php echo $regct[4]; ?>","req","Es requerido: <?php echo $regct[2]; ?>"); <?php
           }
        } ?>
    </script>
	<?php

	bdd_cerrar($cnx);
}

function grabar_nuevo_formulario_usuario($id){
	$cnx = bdd_conectar();
	$qf="SELECT orden as orden, campo_llave as llave, '1' as tipo  FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union 
	SELECT orden_campo as orden,nombre_campo_t as descripcion, tipo_campo as tipo FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsf = bdd_pg_query($cnx, $qf);
	$i=0;
	while($regct = bdd_pg_fetch_row($rsf)){
		$campo[$i]=$_POST[$regct[1]];
		$i++;
	}
	$num = bdd_pg_num_rows($rsf);
	$num=$num-1;
	$q = "INSERT INTO form_".$id." (";
	$i=0;
	$rsf = bdd_pg_query($cnx, $qf);
	while($regct = bdd_pg_fetch_row($rsf)){
		if ($i<$num){
			$q=$q." ".$regct[1]." , ";
		}else{
			$q=$q." ".$regct[1]." ) VALUES ( ";
		}
		$i++;
	}
	$i=0;
	$num = bdd_pg_num_rows($rsf);
	$num=$num-1;
	$rsf1 = bdd_pg_query($cnx, $qf);
	while($regct = bdd_pg_fetch_row($rsf1)){
		if ($i<$num){
			if($regct[2]=='2'){
				$q=$q." ".$campo[$i]." , ";
			}else{
				$q=$q." '".$campo[$i]."' , ";
			}
		}else{
			if($regct[2]=='2'){
				$q=$q." ".$campo[$i]." )  ";
			}else{
				$q=$q." '".$campo[$i]."' ) ";
			}
		}
		$i++;
	}
	$rs = @pg_query($cnx, $q);
        if ($rs){
            $af = @pg_affected_rows($rs);
            if ($af){ ?>
                    <p class="ok">Registro modificado!</p>
                    <p > Regresar a <a href="/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id;?>">Formulario <?php echo titulo($id);?></a></p>	<?php
            } else { ?>
                    <p class="error">El registro no ha sufrido modificacion<br />
                    <p > Regresar a <a href="/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id;?>">Formulario <?php echo titulo($id);?></a></p>
                    <?php //echo pg_error($cnx);?> <?php
            }
        }else{?>
                    <p class="error">El registro no ha sufrido modificacion DATO DUPLICADO<br />
                    <p > Regresar a <a href="/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id;?>">Formulario <?php echo titulo($id);?></a></p>
                    <?php //echo pg_error($cnx);?> <?php

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
	$qf="SELECT orden as orden, campo_llave as llave FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union 
	SELECT orden_campo as orden,nombre_campo_t as descripcion FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsf = bdd_pg_query($cnx, $qf);
	$actions = "Acciones";
	$aAdd = "Activar";
	$aEdit = "Bloquear";
	$aDelete = "Asignar";
	$q = 'SELECT id_'.$id;
	while($regct = bdd_pg_fetch_row($rsf)){
		$q=$q.' , '.$regct[1];
	}
	$q=$q.' FROM '.$table;
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
			
			while ($reg = bdd_pg_fetch_row($result)) {
			$i = 0;	?>	<tr>	<?php
				while ($i < $numf ) {
					?> <td> <?php 
					print campo($reg[$i],$i,$cnx,$id);
					?> </td> <?php
					$i++;
				}
				foreach($data as $value) {
					if ($value =='Borrar'){
						?>	<td><a href="#" onClick="disp_confirm('index.php?action=borrar&id=<?php echo $reg[0]; ?>&idf=<?php echo $id; ?>','no','&iquest; Esta seguro que quiere eliminar este registro ID:<?php echo $reg[0]?>?');"><?php echo $value; ?> </a></td>	<?php
					}else{ 
// 						?><td><a href="<?php  $_SERVER['PHP_SELF']?> index.php?action=<?php echo strtolower($value); ?>&amp;idf=<?php echo $reg[0]?>&id=<?php echo $id?>"> <?php echo $value; ?></a></td>	<?php 
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

function campo($reg,$i,$cnx,$id){
	$c=$i-1;
	$qct="SELECT orden as orden, 1 as tipo, nombre_tabla as tabla,campo_llave as llave, nombre_campo_descripcion as descripcion
		FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." and orden=".$c."
		union 
		SELECT orden_campo as orden, 2 as tipo, 'tabla' as tabla, 'llave' as llave, nombre_campo_t as descripcion
		FROM public.form_campos where form_campos.id_form=".$id." and orden_campo=".$c;
	$rsct = bdd_pg_query($cnx, $qct);
	$regct = bdd_pg_fetch_row($rsct);
	if($i==0){
		return $reg;
	}else{
		if($regct[1]==2){
			return $reg;
		}else{
			$datosmotor = general_sacarRegistroPorCondicion($regct[2], $regct[3].' = '.$reg, $cnx, $regct[4]);
			echo $datosmotor[0];
		}
	}
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

function editar_formulario($id, $idf){
$cnx=bdd_conectar();
$titulo=titulo($id);
$qct="SELECT orden as orden, 1 as tipo, catalogos.nombre_cat as nombre , nombre_tabla as tabla,	campo_llave as llave,
	nombre_campo_descripcion as descripcion,  catalogos.codigo as codigo, 10 as tamano, 'tipoc' as tipoc
	FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." 
	union 
	SELECT orden_campo as orden, 2 as tipo, nombre_campo_f as nombre , 'tabla' as tabla,  nombre_campo_t as llave,	
	'llave' as descripcion, 'codigo' as codigo, tamano_campo as tamano, tipo_campo as tipoc
	FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsct = bdd_pg_query($cnx, $qct);


$qf="SELECT orden as orden, campo_llave as llave FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union 
	SELECT orden_campo as orden,nombre_campo_t as descripcion FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsf = bdd_pg_query($cnx, $qf);
	$q = 'SELECT id_'.$id;
	while($regct = bdd_pg_fetch_row($rsf)){
		$q=$q.' , '.$regct[1];
	}
	$q=$q.' FROM form_'.$id.' where id_'.$id.'='.$idf;
	$rs = bdd_pg_query($cnx, $q);
	$rs = bdd_pg_query($cnx, $q);
	if ($rs) { 
		$reg = bdd_pg_fetch_row($rs);
		?>
		<h3>Editar Una <?php echo $titulo; ?></h3>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?action=guardar&id=<?php echo $id;?>" method="POST" id="edit" name="edit" >
			<fieldset ><legend> Editar <?php echo $titulo; ?> </legend>
			<table width="100%" align="center"><?php
                        $i=1;
			while ($regct = bdd_pg_fetch_row($rsct)){
				if ($regct[1]==2){
                                    if ($regct[8]!=3){?>
					<TR class="frm-fld-x-odd">
						<TD width="20%"><?php echo $regct[2]; ?></TD>
						<TD width="80%">
							<input type='text' tabindex="1" name="<?php echo $regct[4]; ?>" id="<?php echo $regct[4]; ?>" 
                                                               maxlength="<?php echo $regct[7];?>" size="<?php echo $regct[7];?>" value='<?php echo $reg[$i]; ?>'
                                                               class='<?php if ($regct[8]==2){ echo 'validate(event)';} ?>'/>
						</TD>
					</TR> <?php
                                     }else{?>
                                        <TR class="frm-fld-x-odd">
                                                <TD width="20%"><?php echo $regct[2]; ?></TD>
                                                <TD width="80%">
                                                        <input tabindex="3" type="text" name="<?php echo $regct[4]; ?>" value="<?php echo $reg[$i]; ?>"
                                                               title="Text input: fecha_rechazo" id="<?php echo $regct[4]; ?>" size="10" maxlength="20" />
                                                        <input type="image" src="../../lib/date.png"  id="f_trigger_a"/>
                                                        <script type="text/javascript">
                                                        Calendar.setup({
                                                        inputField     :    "<?php echo $regct[4]; ?>",    // id of the input field
                                                        ifFormat       :    "%d/%m/%Y", 	//%p for pm       // format of the input field
                                                        showsTime      :    false,            	// will display a time selector
                                                        button         :    "f_trigger_a",   	// trigger for the calendar (button ID)
                                                        singleClick    :    false,           	// double-click mode
                                                        step           :    1                	// show all years in drop-down boxes (instead of every other year as default)
                                                         });
                                                        </script>
                                                        <small>dd/mm/yyyy</small><br />
                                                 </TD>
                                        </TR> <?php
                                    }
				}else{ 	?>
					<TR class="frm-fld-x-odd">
						<TD width="20%"><?php echo $regct[2]; ?></TD>
						<TD width="80%">
							<select name="<?php echo $regct[4]; ?>" tabindex="2" size="1" id="<?php echo $regct[4]; ?>">
								<option selected="selected"> [Seleccione Uno ..]</option>
								<?php general_fillCmb($regct[3], $regct[4], $regct[4], $regct[5], $reg[$i], NULL, $cnx); ?>
							</select>
						</TD>
					</TR> <?php
				}
                               $i++;
                            }?>
                            </table>
                              <table width="100%">
                                   <tr class="frm-fld-x-odd">
                                        <TD colspan="1" align="center">
                                            <input type="hidden" name="action" value="guardar" />
                                            <input type="hidden" name="idf" value="<?php echo $reg[0];?>" />
                                            <label for="Add">&nbsp;</label>
                                            <input tabindex="4" class="frm-btn-x" type="submit" name="Edit" title="Button: Edit" id="Edit" value="Guardar" />
                                            <input tabindex="5" class="frm-btn-x" type="button" name="cancel" title="Button: Cancel" id="Cancel" value="Cancelar" onclick="javascript:window.location=('/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id;?>');"/>
                                         </TD>
                                    </tr>
                            </table>
                        </fieldset>
                    </form>
                <script language="JavaScript" type="text/javascript"> var frmvalidator = new Validator("edit");<?php
                    $rsct1 = bdd_pg_query($cnx, $qct);
                    while ($regct = bdd_pg_fetch_row($rsct1)){
                       if ($regct[1]==1 ){ ?>
                            frmvalidator.addValidation("<?php echo $regct[4]; ?>","dontselect=0","Es requerido: <?php echo $regct[2]; ?>"); <?php
                       }else{ ?>
                            frmvalidator.addValidation("<?php echo $regct[4]; ?>","req","Es requerido: <?php echo $regct[2]; ?>"); <?php
                       }
                    } ?>
		</script>
		<?php 
	} else { ?>
		<p class="error">Id not founded!</p>
		<?php 
	}
	bdd_cerrar($cnx);
}

function actualizar_formulario($id, $idf){
	$cnx = bdd_conectar();
	$qf="SELECT orden as orden, campo_llave as llave, '1' as tipo  FROM public.form_cat, public.catalogos WHERE form_cat.id_cat = catalogos.id_cat and form_cat.id_form=".$id." union 
	SELECT orden_campo as orden,nombre_campo_t as descripcion, tipo_campo as tipo FROM public.form_campos where form_campos.id_form=".$id." order by orden";
	$rsf = bdd_pg_query($cnx, $qf);
	$i=0;
	while($regct = bdd_pg_fetch_row($rsf)){
		$campo[$i]=$_POST[$regct[1]];
		$i++;
	}
	$num = bdd_pg_num_rows($rsf);
	$num=$num-1;
	$q = "UPDATE form_".$id." SET ";
	$i=0;
	$rsf = bdd_pg_query($cnx, $qf);
	while($regct = bdd_pg_fetch_row($rsf)){
		if ($i<$num){
			if($regct[2]=='1' or '3'){
				$q=$q.$regct[1]." = '".$campo[$i]."' , ";
			}else{
				$q=$q.$regct[1]." = ".$campo[$i]." , ";
			}
		}else{
			if($regct[2]=='1' or '3'){
				$q=$q.$regct[1]." = '".$campo[$i]."' WHERE id_".$id." = ".$idf;
			}else{
				$q=$q.$regct[1]." = ".$campo[$i]." WHERE id_".$id." = ".$idf;
			}
		}
		$i++;
	}
     //   print $q;
	$rs = bdd_pg_query($cnx, $q);
	$af = bdd_pg_affected_rows($rs);
	if ($af){ ?>
		<p class="ok">Registro modificado!</p>
		<p > Regresar a <a href="/indicadores/conexiones/form_usuarios/index.php?id=<?php echo $id;?>">Formulario</a></p>	<?php
	} else { ?>
		<p class="error">El registro no ha sufrido modificacion<br />
		<p > Regresar a <a href="/indicadores/conexiones/conexion/">Formulario</a></p>
		<?php echo pg_error($cnx);?> <?php
	}
	bdd_cerrar($cnx);
}

function borrar_form_usuarios($id,$idf){
        if ($id == NULL) {
		?>
		<p class="error"> Id no v&aacute;lido, intente nuevamente.</p>
		<?php
	} else {
		$cnx = bdd_conectar();
                $q = "DELETE FROM form_".$idf." WHERE id_".$idf."=".$id;
                $rs = @bdd_pg_query($cnx, $q);
                if ($rs){
                    $num=@bdd_pg_num_rows($rs);
                        ?>
                        <p class="ok"> Borrado exitosamente.</p>
                        <?php
                } else {
                        ?>
                        <p class="error">ha ocurrido un error, no eliminado, intente nuevamente.</p>
                         <?php
                }
	}
        bdd_cerrar($cnx);
}
?>