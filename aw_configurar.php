<h3><?php _e('Widget configuration','advanced-widgets');?></h3>
<h5><?php echo "Widget: ".$aw_widget_name;?></h5>
<hr/>
<form action="">
	<h4><?php _e('Options','advanced-widgets');?></h4>
	<p>
		<label><input type="radio" <?php if($aw_opcion=="aw_todos_sin_seleccionados")echo 'checked="checked"';?> value="aw_todos_sin_seleccionados" name="aw_opcion"/><?php _e('Show widget on all pages except on the listed filters','advanced-widgets');?></label><br/>
		<label><input type="radio" <?php if($aw_opcion=="aw_todos_seleccionados")echo 'checked="checked"';?> value="aw_todos_seleccionados" name="aw_opcion"/><?php _e('Show widget on listed filters','advanced-widgets');?></label>
	</p>
	<h4><?php _e('Filters','advanced-widgets');?></h4>
	<p>
		<?php _e('Enter one filter per line:','advanced-widgets');?> 
		<code style="font-size:11px;">
			<?php 
			foreach($filtros as $filtro => $desc){
				echo '<a href="#" data-filter="'.$filtro.'" class="add_filter" title="'.$desc.'">'.$filtro.'</a> ';
			}
			?>
		</code>
	</p>
	<p>
		<textarea name="aw_filtros" style="width:100%;height:100px;resize:none;"><?php echo $aw_filtros;?></textarea>
	</p>
	<hr/>
	<p>
		<button class="button-primary"><?php _e('Save','advanced-widgets');?></button>
		<button class="button cerrar"><?php _e('Cancel','advanced-widgets');?></button>
	</p>
</form>