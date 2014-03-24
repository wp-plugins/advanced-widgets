<?php
/*
 Plugin name: Advanced Widgets
 Author: Andrico - Nicolás Guglielmi
 Version:1.0.2
 Description: Agrega widgets en tus sidebars y luego elije donde se van a mostrar! Nunca fue más fácil personalizar la sección de widgets!
 Tags: Widgets, custom widgets, custom sidebars, multiple sidebars, advanced widgets, select widgets, configure widgets
 */


define(AW_URL,plugin_dir_url(__FILE__));
define(AW_JS,AW_URL."/js");
define(AW_IMG,AW_URL."/img");
define(AW_CSS,AW_URL."/css");
define(AW_DEM,60);

class AdvancedWidgets{
	private $numberDays = -30;
	private $aw_dem = true;
	private $filters = array(
		'[front]'=>'Front page' ,
		'[page]'=>'Only in pages' ,
		'[single]'=>'Only in posts' ,
		'[search]'=>'Only in search page' ,
		'[taxonomy]'=>'Only in taxonomy page' ,
		'[category]'=>'Only in category page' ,
		'[archive]'=>'Only in archive page' ,
		'[page-parent=ID]'=>'Only in the children pages' ,
		'[post-name=NAME]'=>'Only in the page by NAME' ,
		'[post-id=ID]'=>'Only in the page by ID' ,
		'[taxonomy-id=ID]'=>'Only in the taxonomy by ID' ,
		'[custom-post-type=SLUG]'=>'Only in custom post type by SLUG'
	);
	function AdvancedWidgets(){

		add_action("init",array(&$this,"aw_init"));
		add_action('admin_enqueue_scripts', array(&$this,'aw_add_js'));

		add_action( 'wp_ajax_aw_save', array(&$this, 'aw_save') );
		add_action( 'wp_ajax_aw_load', array(&$this, 'aw_load') );
		//add_action( 'plugins_loaded', array(&$this, 'aw_text_domain'));
		add_action( 'admin_menu' , array(&$this, 'aw_menu'));

		add_filter( 'aw_filtros', array(&$this, "aw_filtros_simples"),10,2);
		add_filter( 'aw_filtros', array(&$this, "aw_filtros_complejos"),11,2);

		add_filter("sidebars_widgets",array(&$this,"aw_aplicar_filtros"),10,3);
		register_activation_hook(__FILE__, array(&$this,"aw_activate"));
		register_deactivation_hook(__FILE__, array(&$this,"aw_deactivate"));

	}
	function aw_text_domain(){
		
	}
	function aw_menu(){
		add_options_page(_('Advanced Widgets'), _('Advanced Widgets'), 'manage_options', 'advanced-widgets', array(&$this, "aw_pagina_configuracion"));
	}
	function aw_pagina_configuracion(){
		if(isset($_POST['aw_action']) && $_POST['aw_action'] == "save"){
			update_option("_aw_code",($_POST['aw_code'])?$_POST['aw_code']:"");
			$aw_salvado = true;
		}
		$aw_code = (get_option("_aw_code"))?get_option("_aw_code"):"AW-ACT-XXXXXX";
		$this->aw_check_code();
		$aw_check_code = !$this->aw_dem;
		include("aw_ajustes.php");
	}
	function aw_activate(){
		if(!get_option("_aw_fecha_activacion")){
			add_option("_aw_fecha_activacion",date("Y/m/d"));
			add_option("_aw_fecha_cad",strtotime("+".AW_DEM." days ".date("Y/m/d")));
		}
	}
	function aw_init(){
		$this -> aw_check_code();
		if($this->aw_dem){
			$startTimeStamp = strtotime(date("Y/m/d"));
			$endTimeStamp = get_option("_aw_fecha_cad");
			$timeDiff = ($endTimeStamp - $startTimeStamp);
			$this->numberDays = $timeDiff/86400;
		}
	}
	function aw_check_code(){
		$aw_cod = get_option("_aw_code");
		if($aw_cod == "AW-ACT-".sha1(md5(site_url())))
			$this->aw_dem = false;
		else
			$this->aw_dem = true;
	}
	function aw_load_textdomain() {
		load_plugin_textdomain( 'advanced-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' ); 
	}
	function aw_aplicar_filtros($sidebars){
		if(is_admin())
			return $sidebars;
		foreach($sidebars as $sidebar => $aw_widgets){
			if($sidebar == "wp_inactive_widgets") continue;
			foreach($aw_widgets as $key => $aw_widget){
				$aw_opcion = get_option("aw_opcion_".$aw_widget);
				if($aw_opcion == "aw_todos_sin_seleccionados"){
					$aw_opcion = true;
				}else if($aw_opcion == "aw_todos_seleccionados"){
					$aw_opcion = false;
				}else{
					$aw_opcion = null;
				}
				$aw_filtros = get_option("aw_filtros_".$aw_widget);
				if($aw_filtros && !empty($aw_filtros)){
					$aw_filtros = trim($aw_filtros);
					$aw_filtros = str_replace("\r\n","\n",$aw_filtros);
					$aw_filtros = explode("\n",$aw_filtros);
					$aw_filtros = array_filter($aw_filtros, 'trim');
				}else{
					$aw_filtros = array("[none]");
				}

				$exist = isset($sidebars[$sidebar][$key]);
				if($exist && $aw_opcion !== null){
					if($aw_filtros[0] == "[none]" && !$aw_opcion){
						unset($sidebars[$sidebar][$key]);
					}else{
						if($this -> aw_analizar_widgets($aw_filtros)){
							if($aw_opcion)
								unset($sidebars[$sidebar][$key]);
						}else{
							if(!$aw_opcion)
								unset($sidebars[$sidebar][$key]);
						}
					}
				}
			}
		}
		return $sidebars;
	}
	function aw_analizar_widgets($aw_filtros){
		$ret = false;
		foreach($aw_filtros as $aw_filtro){
			if($aw_filtro=="" || empty($aw_filtro))continue;
			$ret = apply_filters("aw_filtros",$ret,$aw_filtro);
		}
		return $ret;
	}
	function aw_filtros_simples($ret, $aw_filtro){
		global $post;
		switch(true){
			case $aw_filtro == "[front]":
				$ret = $ret || is_front_page() || is_home()?true:false;
				break;
			case $aw_filtro == "[archive]":
				$ret = $ret || is_archive()?true:false;
				break;
			case $aw_filtro == "[taxonomy]":
				$ret = $ret || is_taxonomy()?true:false;
				break;
			case $aw_filtro == "[category]":
				$ret = $ret || is_category()?true:false;
				break;
			case $aw_filtro == "[post]":
				$ret = $ret || is_single()?true:false;
				break;
			case $aw_filtro == "[page]":
				$ret = $ret || is_page()?true:false;
				break;
			case $aw_filtro == "[search]":
				$ret = $ret || is_search()?true:false;
				break;
		}
		return $ret;
	}
	function aw_filtros_complejos($ret, $aw_filtro){
		global $post;
		switch(true){
			case preg_match("#\[custom-post-type=(.*)\]#",$aw_filtro):
				$type_post = preg_replace("#\[custom-post-type=(.*)\]#","$1",$aw_filtro);
				if($type_post == get_post_type(get_the_ID()))
					$ret = $ret || true;
				break;
			case preg_match("#\[post-id=(.*)\]#",$aw_filtro):
				$post_id = preg_replace("#\[post-id=(.*)\]#","$1",$aw_filtro);
				if($post_id == $post->ID && !is_front_page() && !is_home() && (is_single() || is_page()))
					$ret = $ret || true;
				break;
			case preg_match("#\[post-name=(.*)\]#",$aw_filtro):
				$post_slug = preg_replace("#\[post-name=(.*)\]#","$1",$aw_filtro);
				if($post_slug == $post->post_name && !is_front_page() && !is_home() && (is_single() || is_page()))
					$ret = $ret || true;
				break;
			case preg_match("#\[page-parent=(.*)\]#",$aw_filtro):
				$post_id = preg_replace("#\[page-parent=(.*)\]#","$1",$aw_filtro);
				if($post->post_parent == $post_id)
					$ret = $ret || true;
				break;
		}
		return $ret;
	}
	function aw_add_js($hook){
		if ($hook != 'widgets.php')
			return false;

		wp_enqueue_script("aw_script",AW_JS."/aw.js");
		wp_localize_script('aw_script', 'aw_url', AW_URL);
		wp_localize_script('aw_script', 'admin_url', get_bloginfo("url")."/wp-admin");
		wp_localize_script('aw_script', 'label', array("configurar"=>_("Settings")));
		if($this->numberDays <= 0 && $this->aw_dem){
			wp_localize_script('aw_script', 'demora', (-$this->numberDays*1000));
		}else{
			wp_localize_script('aw_script', 'demora', "0");
		}

		wp_enqueue_style("aw_style",AW_CSS."/aw.css");
	}
	function aw_save(){
		if(!isset($_POST["aw_opcion"]) || !isset($_POST["aw_filtros"]) || !isset($_POST["aw_widget"]) || !isset($_POST["aw_widget_id"])){
			return false;
		}
		$aw_opcion = $_POST["aw_opcion"];
		$aw_filtros = $_POST["aw_filtros"];
		$aw_widget_id = $_POST["aw_widget_id"];
		$aw_widget = $_POST["aw_widget"];

		update_option("aw_opcion_".$aw_widget_id,$_POST["aw_opcion"]);
		update_option("aw_filtros_".$aw_widget_id,$_POST["aw_filtros"]);
		echo "Se grabarón correctamente";
		die();
	}
	function aw_load(){
		global $wp_registered_widgets;

		
		if(!isset($_POST["aw_widget"]) || !isset($_POST["aw_widget_id"])){
			echo "No se pudo cargar la configuración";
			die();
		}
		$aw_widget_id = $_POST["aw_widget_id"];
		$aw_widget = $_POST["aw_widget"];
		$aw_opcion = get_option("aw_opcion_".$aw_widget_id);
		$aw_filtros = get_option("aw_filtros_".$aw_widget_id);
		$aw_widget_name = "";
		$aw_donado = !$this->aw_dem;
		$filtros = $this->filters;
		$numberDays = $this->numberDays;

		if ( isset($wp_registered_widgets[$aw_widget_id]['name']) )
	    	$aw_widget_name = esc_html( $wp_registered_widgets[$aw_widget_id]['name'] );
		require_once("aw_configurar.php");
		die();
	}
	function aw_deactivate(){
		// delete_option("_aw_fecha_activacion");
		// delete_option("_aw_fecha_cad");
	}
	function aw_add_filter($name, $description, $function){
		if(!array_key_exists($name,$this->filters)){
			add_filter( 'aw_filtros', $function,10,2);
			$this->filters[$name] = $description;
		}
	}
}
$AW = new AdvancedWidgets();

function aw_add_filter($name,$description,$function){
	global $AW;
	if(function_exists($function)){
		$AW->aw_add_filter($name,$description,$function);
	}
}
?>