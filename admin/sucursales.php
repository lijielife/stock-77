<?
include "library/conexion.php";
include "library/auxiliares.php";


$texto = cargar('texto', '');

if (empty($texto)) die ( json_encode("status": "fail") );
else die ( json_encode("status": "ok") );



?>