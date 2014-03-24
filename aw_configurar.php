<?php if(!$aw_donado){?>
	<div id="message" class="error">
		<?php
		if($numberDays > 0){
			echo '<p>';
				printf(_('After %d days expire plugin.'),$numberDays);
			echo '</p>';
		}
		?>
		<p><?php printf(_('Have a delay of 1 second per day used. To remove the delay, press <a href="%s" target="_blank">here</a> and donate!'),admin_url()."/options-general.php?page=advanced-widgets"); ?></p>
	</div>
<?php }?>
<h3><?php _e('Widget configuration');?></h3>
<h5><?php echo "Widget: ".$aw_widget_name;?></h5>
<hr/>
<form action="">
	<h4><?php _e('Options');?></h4>
	<p>
		<label><input type="radio" <?php if($aw_opcion=="aw_todos_sin_seleccionados")echo 'checked="checked"';?> value="aw_todos_sin_seleccionados" name="aw_opcion"/><?php _e('Show widget on all pages except on the listed filters');?></label><br/>
		<label><input type="radio" <?php if($aw_opcion=="aw_todos_seleccionados")echo 'checked="checked"';?> value="aw_todos_seleccionados" name="aw_opcion"/><?php _e('Show widget on listed filters');?></label>
	</p>
	<h4><?php _e('Filters');?></h4>
	<p>
		<?php _e('Enter one filter per line:');?> 
		<code style="font-size:11px;">
			<?php 
			foreach($filtros as $filtro => $desc){
				echo '<a href="javascript:return false;" onclick="add_filter(\''.$filtro.'\');" title="'.$desc.'">'.$filtro.'</a> ';
			}
			?>
		</code>
	</p>
	<p>
		<textarea name="aw_filtros" style="width:100%;height:100px;resize:none;"><?php echo $aw_filtros;?></textarea>
	</p>
	<hr/>
	<p>
		<button class="button-primary"><?php _e('Save');?></button>
		<button class="button cerrar"><?php _e('Cancel');?></button>
	</p>
</form>