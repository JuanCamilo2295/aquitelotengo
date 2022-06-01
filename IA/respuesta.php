<html>

<head>
	<title>¡AQUI TE LO TENGO!</title>
	<link rel="stylesheet" type="text/css" href="css/estiloo.css">
	<link rel="stylesheet" type="text/css" href="css/estiloresultado.css">
	
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
</head>


<body>

<header>
	
	<h1 class='letras'>¡AQUI TE LO TENGO!</h1>


</header>


<main>




<?php
//CONECTAMOS CON LA BD
require "conexion.php";

//RECOGEMOS LA RESPUESTA
$respuesta = $_GET["r"];
$nodo = $_GET["n"];
$nombreAnterior = $_GET["p"];
$numPregunta = $_GET["np"];


//----------------------------------------------
function formularioRespuesta($n,$p){
	echo"<img src='imagenes/pajaro.png' b>";
	echo "<div class='contenedorPregunta'>";
	
	echo "<textarea id='nodo' name='nodo' form='formulario' placeholder='nombre' style='display:none;'>".$n."</textarea>";
	echo "<textarea id='nombreAnterior' name='nombreAnterior' form='formulario' placeholder='nombre' style='display:none;'>".$p."</textarea>";
			
	echo "<h2 id='txtcar'>¿Que otro libro te gustaría que te haya recomendado?</h2>";
	echo "<textarea id='nombre' name='nombre' form='formulario' placeholder='nombre'></textarea>";
	echo "<h2 id='txtcar'>¿Qué característica tiene este libro que no tenga ".$p."?</h2>";
	echo "<textarea id='caracteristicas' name='caracteristicas' form='formulario' placeholder='caracteristicas'></textarea>";
	echo"<input type='file' name='img' form='formulario' >";

	echo "<form action='crear.php' id='formulario' method='POST'  enctype='multipart/form-data'>";
	echo "<button type='submit' name='ENVIAR'>ENVIAR</button>";
	echo "</form>";
	
	echo "</div>";
	
}
//----------------------------------------------




//SI HA FALLADO
if($respuesta == 0){
	
	session_start();			//iniciamos la sesión
	$nodosRepuesto =array();	//creamos el array
	
	//COMPROBAMOS SI EXISTE LA VARIABLE DE SESIÓN (ES DECIR, SI HEMOS GUARDADO ALGÚN NODO EN EL QUE DUDÁSEMOS)
	if(isset($_SESSION['nodosRepuesto'])){
		$nodosRepuesto = $_SESSION['nodosRepuesto'];
		$tamano = count($nodosRepuesto);			//medimos la longitud del array
		
		
		if($tamano != 0){
			//SI HAY ELEMENTOS EN EL ARRAY QUE PODAMOS USAR
			
			$nodoRevisar = array_pop($nodosRepuesto);	//obtenemos el último elemento del nodo y lo desapilamos
			$_SESSION['nodosRepuesto']=$nodosRepuesto;  //actualizamos el array con los valores nuevos
		
			header("Location:index.php?n=".$nodoRevisar."&r=0&np=".$numPregunta."");	//volvemos automáticamente al nodo
		
		}
		
		else{
			//SI EL ARRAY CON NODOS DE REPUESTO ESTÁ VACÍO
			formularioRespuesta($nodo,$nombreAnterior);
		}
		
	}
	
	else{
		//SI NO HAY VARIABLE DE SESIÓN
		formularioRespuesta($nodo,$nombreAnterior);
	}

}

//SI HA ACERTADO
else{
	
	//--------------------------------------------------------
	//GUARDAMOS EL ACIERTO EN EL LOG DE LA BD (TABLA PARTIDA)
	
	$consulta = "INSERT INTO partida (personaje,acierto) VALUES('".$nombreAnterior."',TRUE);";
	mysqli_query($enlace, $consulta);
	
	
	//-----------------------------------------------------
	//BORRAMOS LA VARIABLE DE SESIÓN CON EL ARRAY
	session_start();		//iniciamos la sesión
	$arrayVacio =array();	
	
	if(isset($_SESSION['nodosRepuesto'])){
		$_SESSION['nodosRepuesto']=$arrayVacio;
	}
	//-----------------------------------------------------
	
	echo "<h2 >Te recomiendo este libro: $nombreAnterior</h2>";
	$consulta = "SELECT imagen FROM arbol WHERE nodo = ".$nodo.";";
	
if($resultado = mysqli_query($enlace, $consulta)){
	while ($fila = mysqli_fetch_row($resultado)) {
		$ipersonaje=base64_encode($fila[0]);
	}
	echo "<img src='data:image/png;base64, $ipersonaje'>";

}else{
	mysqli_free_result($resultado);
}

}


?>


</main>

<br>
<br>



<footer>

<?php
	echo "<a class='btn_probar' href='index.php?n=1&r=0'>Volver a probar</a>";
	echo "<br><br><a class='btn_datos' href='datos.php'>Revisar los datos</a>";
?>

</footer>



</body>
</html>

