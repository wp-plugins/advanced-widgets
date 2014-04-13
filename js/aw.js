jQuery(function(){
	jQuery("body").append("<div id='aw_configurar' class='aw_configurar'><div class='aw_configurar_inner'></div></div>");
	jQuery(".widget .widget-control-actions .alignleft").append(" | <a href='#' onclick='aw_configurar(jQuery(this));'>"+label.configurar+"</a>");
	jQuery("body").on("click",".aw_configurar_inner .add_filter",function(e){
		add_filter(jQuery(this).attr("data-filter"));
		console.log(jQuery(this).attr("data-filter"));
		e.preventDefault();
		e.stopPropagation();
	})
})
function aw_configurar_cerrar(){
	jQuery(".aw_configurar").toggleClass("activo");
	jQuery(".aw_configurar_inner").html("");
}
function add_filter(filtro){
	console.log(jQuery(".aw_configurar_inner textarea").val());
	jQuery(".aw_configurar_inner textarea").val(jQuery(".aw_configurar_inner textarea").val()+"\n"+filtro);
}
function aw_configurar(widget){
	elemento = widget.parent().parent().parent();
	widgetID = elemento.find(".widget-id").val();
	baseID = elemento.find(".id_base").val();
	jQuery(".aw_configurar").toggleClass("activo");
	jQuery(".aw_configurar_inner").html("<div style='text-align:center;'><div id='time'></div><img src='"+admin_url+"/images/spinner.gif' /></div>");
	jQuery.ajax({
		url:ajaxurl,
		type:"post",
		data:{action:"aw_load",aw_widget_id:widgetID,aw_widget:baseID},
		success:function(data){
			jQuery(".aw_configurar_inner").html("");
			jQuery(".aw_configurar_inner").html(data);
			jQuery(".aw_configurar_inner form").append("<input type='hidden' name='action' value='aw_save' /><input type='hidden' name='aw_widget' value='"+baseID+"'/><input type='hidden' name='aw_widget_id' value='"+widgetID+"'/>");
			jQuery(".aw_configurar_inner button.cerrar").click(function(){
				aw_configurar_cerrar();
				return false;
			})
			jQuery(".aw_configurar_inner form").submit(function(){
				jQuery.ajax({
					url:ajaxurl,
					type:"post",
					data:jQuery(this).serialize(),
					success:function(msj){
						aw_configurar_cerrar();
					}
				})
				return false;
			})
		}
	})
	return false;
}