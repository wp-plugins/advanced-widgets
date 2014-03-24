<style>
.advanced-widgets input.ok{
	border-color: green;
}
.advanced-widgets input.error{
	border-color: red;
}
</style>
<div class="wrap advanced-widgets">
	<h2><?php _e("Hello!");?></h2>
	<?php
	if($aw_salvado){
		?>
		<div id="message" class="updated">
			<p><?php _e("Saved!");?></p>
		</div>
		<?php
	}
	?>
	<h3>Advanced Widgets</h3>
	<table>
		<tr>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="C6BWB77KBG664">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
			<td>
				<p>
					Send me the site url, the paypal donation id and your email to activate the plugin.<br/>
					Nicolás Guglielmi <a href="mailto:nicolas.guglielmi@gmail.com">nicolas.guglielmi@gmail.com</a>
				</p>
			</td>
		</tr>
	</table>
	

	<form action="" method="post">
		<input type="hidden" name="aw_action" value="save" />
		
		<p>
			<label>
				<?php _e('Activation code');?><br/>
				<input size="50" class="<?php echo ($aw_check_code)?"ok":"error";?>" type="text" name="aw_code" value="<?php echo $aw_code;?>" />
			</label>
		</p>
		<p>
			<?php _e('Site url');?><br/>
			<b><?php echo site_url();?></b>
		</p>
		<p>
			<button class="button-primary"><?php _e("Save");?></button>
		</p>
	</form>


</div>