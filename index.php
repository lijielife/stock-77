<? 
include "oop/init.php";
$seguridad = new Seguridad($config, 2);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$config->title?></title>
<link rel="stylesheet/less" type="text/css" href="styles.css">
<script src="library/less-1.1.5.min.js" type="text/javascript"></script>
</head>

<body>

<div id="container">
    	
        <div id="top">
			<div class='content'>
            	<img src="img/logo.png" onclick="window.location = index.php" />
            	<h1>Administrador Libo</h1>
            </div>
        </div>
        
        <div class="content" id="index">
        	<p><a class='boton' href="in.php?accion=articulo&que=producto"><?=$traducir[agregarproducto]?></a>
             <a class='boton' href="in.php?accion=administrador&que=producto">Administrar productos</a>
             <a class='boton' href="in.php?accion=administrador&que=materia">Materias Primas</a>
             <a class='boton' href="in.php?accion=faltantes">Faltantes</a>
             </p>
             <p>
             <a class='boton' href="in.php?accion=administrador&que=proveedor">Proveedores</a>
             <a class='boton' href="in.php?accion=administrador&que=cliente">Clientes</a>
             <a class='boton' href="in.php?accion=categorias">Categorías</a>
            </p>
            <!--<p>
            <a class='boton' href="in.php?accion=administrador"><?=$traducir[adminarticulos]?></a>
            <a class='boton' href="in.php?accion=categorias"><?=$traducir[admincategorias]?></a>
            <a class='boton' href="in.php?accion=imagenes"><?=$traducir[agregarimagenes]?></a>
            </p>
            <p><a class='boton' href="in.php?accion=compras&estado=pendiente">Administrar compras</a></p>-->
            
            
             <div id="notificaciones">
        	<?
				$DB->select('datos_producto', 'COUNT(*) as cuantos', 'stock < stock_minimo AND stock_minimo > 0 AND stock_minimo IS NOT NULL AND stock IS NOT NULL');
				$artsFaltantes = $DB->results[0][cuantos];
				
				$DB->select('materias_primas', 'COUNT(*) as cuantos', 'stock < stock_minimo AND stock_minimo > 0 AND stock_minimo IS NOT NULL AND stock IS NOT NULL');
				$matsFaltantes = $DB->results[0][cuantos];
				
				if ($artsFaltantes) echo "<p>Hay <a href='in.php?accion=faltantes'>$artsFaltantes artículos </a>con stock por bajo del mínimo.</p>";
				if ($matsFaltantes) echo "<p>Hay <a href='in.php?accion=faltantes'>$matsFaltantes materias </a>con stock por bajo del mínimo.</p>";
				
			?>
        </div>
            
            
        </div>

        <a class='boton' href="login.php?logout" style="position:absolute; bottom:30px; right:30px;"> <?=$traducir[salirsesionde] ." ".$_SESSION[nombre]?>.</a>
        
        
       
    </div>   
</body>
</html>