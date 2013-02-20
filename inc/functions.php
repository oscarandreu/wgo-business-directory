<?php

function wgo_get_find_function() {
	?>
<script type="text/javascript">
        <!--
        function buscar(clean, param) {            
            //filtro por categorias
            var select = document.getElementById("wgo_listing_cat_id");
            var category = select.value;
            var catIndex = select.options[select.selectedIndex];
            var categoryText = "Empresas";
            if(catIndex != 0)
                categoryText = select.options[select.selectedIndex].text;

            //filtro por municipios
            var city = document.getElementById("wgo_listing_cities_id").value;

            //variables para el paginado
            var i = 0;
            if(!clean && document.getElementById("paginas") != null)
                i = document.getElementById("paginas").value;

            if(typeof showMap == 'function')
                showMap('', city, category);
            
            var directoryTitle = document.getElementById("directoryTitle");
            if(directoryTitle != null)
            	directoryTitle.innerHTML = "<h1 id=\"directoryTitle\" class=\"entry_title\">Directorio de "+categoryText+"</h1>";
            document.getElementById("panel_empresas").innerHTML = "<p>&nbsp;</p><center><img src=\"<?php echo content_url('plugins/wgo-business-directory/images/') ?>wait.gif\"></center><br>";

            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    document.getElementById("panel_empresas").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "<?php echo content_url('plugins/wgo-business-directory/inc/') ?>inc_search_page.php?cat="+category+"&p="+param+"&city="+city+"&i="+i, true);
            xmlhttp.send();            
        }
        -->
    </script>
<?php
}

function wgo_map_function() {	
	echo "
		<script type='text/javascript'>
			<!--     
			function showMap(id, city, category) {       
				var j$ = jQuery.noConflict();
				var frameId = 'view_map_main';
				if(id != '')
					var frameId = 'view_map_'+id;
				
				var frame = document.getElementById(frameId);
				if(id != '')
				{
				  var container = document.getElementById('map_container_'+id);
				  j$(container).slideDown('slow');
				  var tab = document.getElementById('tab_view_map_'+id);
				  j$(tab).attr('class', 'tab_view_active');
				}
				j$(frame).attr('src', '". content_url('plugins/wgo-business-directory/inc/')."inc_display_map.php?id='+id+'&city='+city+'&category='+category);
			}   
			function hideMap(id) {       
				var j$ = jQuery.noConflict();
				var tab = document.getElementById('tab_view_map_'+id);
				j$(tab).attr('class', 'tab_view');
				var frame = document.getElementById('map_container_'+id);
				j$(frame).slideUp('slow');
			}
			-->
		</script>
	";
}

function wgo_show_function() {
	?>
<script type="text/javascript">
        <!--
        function show_div(elementname) {
            var j$ = jQuery.noConflict();
            var div = document.getElementById(elementname);
            j$(div).slideDown('slow');       
        }
        function show_tab(elementname) {
            var j$ = jQuery.noConflict();
            var tab = document.getElementById(elementname);
            j$(tab).attr('class', 'tab_view_active');
        }
        function show(name,id) {
            show_div('view_'+name+'_'+id);
            show_tab('tab_view_'+name+'_'+id);
        }
        -->
    </script>
<?php
}

function wgo_hide_function() {
	?>
<script type="text/javascript">
        <!--
        function hide_div(elementname) {
            var j$ = jQuery.noConflict();
            var div = document.getElementById(elementname);
            j$(div).slideUp('slow');
        }
        function hide_tab(elementname) {
            var j$ = jQuery.noConflict();
            var tab = document.getElementById(elementname);
            j$(tab).attr('class', 'tab_view');
        }
        function hide(name,id) {  
            hide_tab('tab_view_'+name+'_'+id);
            hide_div('view_'+name+'_'+id);
        }
        function hide_all(id) {
            hideMap(id);
            hide('contact',id);
            hide('share',id);
        }
        -->
    </script>
<?php
}

function getPanelPaginacion($registroInicial, $num_resultados, $nombreEntidad, $paramFuncion) {
	$elementos_pagina = 20;
	$num_paginas = ceil($num_resultados / $elementos_pagina);

	if ($num_paginas > 1) {
		$selectPaginado = "Mostrar:  <select name='paginas' class=select' id='paginas' onChange='buscar(false,\"$paramFuncion\")'>";
		for ($i = 0; $i < $num_paginas; $i++) {
			$pagina = $i * $elementos_pagina;
			if ($pagina == $registroInicial)
				$selected = 'selected="selected"';
			else
				$selected = '';
			$selectPaginado = $selectPaginado.'<OPTION '.$selected.' VALUE="'. $pagina .'">'.($i * $elementos_pagina + 1).'-'.($i * $elementos_pagina + $elementos_pagina);
		}

		$selectPaginado = $selectPaginado .'</select>';
	}
	$panel = '
	<div class="num_empresas_encontradas">
	Nº de ' . $nombreEntidad . ' encontradas: <strong>'.$num_resultados.'</strong>
	</div>
	<div class="select_paginacion">
	' . $selectPaginado . '
	</div>';

	return $panel;
}

function pp_image_option_radios($imgurl) {
	if (get_option('wgo_buttimg') == $imgurl) {
		$status = "checked";
	}
	$option = "<tr><td><input type=\"radio\" name=\"wgo_buttimg\" value=\"$imgurl\" $status /></td><td style=\"padding:10px; background-color: #CCCCCC; text-align: center;\"><img src=\"$imgurl\" align=\"middle\" /></td></tr>";
	return $option;
}

?>
