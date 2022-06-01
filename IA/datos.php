<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/estiloresultado.css">

</head>


<body>

<h1>RECOMENDADOR DE LIBROS</h1>
<hr>
<br>



<?php

//CONECTAMOS CON LA BD
require "conexion.php";



//HACEMOS LA CONSULTA A LA BD
$consulta = "SELECT COUNT(*) FROM arbol WHERE pregunta = 0";

$numero = '0';
 
if ($resultado = mysqli_query($enlace, $consulta)) {
 
		while ($fila = mysqli_fetch_row($resultado)) {
			$numero 	  = $fila[0];
		}

    mysqli_free_result($resultado);
}


echo "<h3>Libros registrados: ".$numero."</h3>";
echo "<hr><br>";



//-------------------------------------------------------------------
//CONSULTA PARA VER ÚLTIMOS LIBROS RECOMENDADOS
$consulta = "SELECT personaje FROM partida ORDER BY id DESC LIMIT 10";

$nombre = '';

echo "<h3>ÚLTIMOS LIBROS RECOMENDADOS</h3>";
 
if ($resultado = mysqli_query($enlace, $consulta)) {
 
		while ($fila = mysqli_fetch_row($resultado)) {
			$nombre 	  = $fila[0];
			echo $nombre ."<br>";
		}

    mysqli_free_result($resultado);
}


?>

<br>
<br>


<?php
	echo "<hr>";
	echo "<br><br><a class='btn_probar' href='index.php?n=1&r=0'>Volver a probar</a>";
	echo "<br>";
?>

</body>
</html>