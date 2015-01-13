<style>
.advanced-widgets input.ok{
	border-color: green;
}
.advanced-widgets input.error{
	border-color: red;
}
</style>
<div class="wrap advanced-widgets">
	<h2><?php _e("Hello!",'advanced-widgets');?></h2>
	<h3>Advanced Widgets</h3>
	<p><?php _e('Add widgets in your sidebars and then choose where they will be shown! It has never been so easy to personalize the widgets section!','advanced-widgets');?></p>
	<hr>
	<h4><?php _e('How to use','advanced-widgets');?></h4>
	<ol>
		<li><?php _e('Install the plugin','advanced-widgets');?></li>
		<li><?php _e('Add Widgets on the sidebars in the Appearance->widgets section','advanced-widgets');?></li>
		<li><?php _e('Configurate the Widget by pressing on the bar of the title and then on the Configuration button','advanced-widgets');?></li>
		<li><?php _e('Configurate the options and add filters to show these widgets in a personalized way','advanced-widgets');?></li>
	</ol>
	<hr>
	<h4><?php _e('Add new filters','advanced-widgets');?></h4>
	
	<b>aw_add_filter($name,$description,$function)</b><br/>

	<b>String $name:</b> <?php _e("filter name. Ex: [category]","advanced-widgets");?><br/>
	<b>String $description:</b> <?php _e('Short description',"advanced-widgets");?><br/>
	<b>String $function:</b> <?php _e("Function to call","advanced-widgets");?><br/><br/>

	<b>Function to call</b><br/>
	<b>Boolean $r:</b> <?php _e("return of the function","advanced-widgets");?><br/>
	<b>String $filter:</b> <?php _e("The filter name","advanced-widgets");?><br/>
	<b>Return Boolean:</b> <?php _e("True or false","advanced-widgets");?><br/><br/>

	Ex:<br/>
	<style>
		code{
			display: block;
		}
	</style>
	<code>
	<pre>
//Add new filter
add_action("aw_init","register_my_filter");
function register_my_filter(){
	aw_add_filter('[post-name=SLUG]','Show only in the post by SLUG','function_my_filter');
}
//Function to call the new filter
function function_my_filter($r,$filter){
	global $post;
	
	//Get the filter value if necessary. (In the "[category]" example this is not necessary)
	$post_slug = preg_replace("#\[post-name=(.*)\]#","$1",$filter);

	//Check if the filter is true or false
	if((is_single() || is_page()) && $post_slug == $post->post_name && !is_front_page() && !is_home())
		$r = $r || true;

	//Return true or false.
	return $r;
}</pre>
	</code>
	<hr>
	<h4><?php _e('Help!','advanced-widgets');?></h4>
	<p><?php _e('For any requirement, contact me to:','advanced-widgets');?> Nicolás Guglielmi <a href="mailto:nicolas.guglielmi@gmail.com">nicolas.guglielmi@gmail.com</a></p>
	<hr>
	<h4><?php _e('Donate','advanced-widgets');?></h4>
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
					Nicolás Guglielmi <a href="mailto:nicolas.guglielmi@gmail.com">nicolas.guglielmi@gmail.com</a>
				</p>
			</td>
		</tr>
	</table>
	

</div>