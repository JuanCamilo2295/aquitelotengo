<!DOCTYPE html>
<html>

<head>
	<title>RECOMENDADOR DE LIBROS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/estiloo.css">
</head>
	


<body>


	<main>

		<?php

		//CONECTAMOS CON LA BD
		require "conexion.php";

		//OBTENEMOS EL NÚMERO DEL NODO DENTRO DEL ÁRBOL (PARA SABER QUÉ CAMINO HEMOS TOMADO)

		$nodo = 1;
		$nodoRepuesto = 0;
		$numPregunta = 1;
		$proxPregunta = 2;

		if(isset($_GET['n'])) {
			$nodo = $_GET["n"];
		}

		if(isset($_GET['r'])) {
			$nodoRepuesto = $_GET["r"];
		}

		if(isset($_GET['np'])) {
			$numPregunta = $_GET["np"];
			$proxPregunta = $numPregunta+1;
		}



		//------------------------------------------------------
		//SI HAY UN NODO DE REPUEO SE AÑADE A LA LISTA (ARRAY)
		if($nodoRepuesto!=0){

			session_start();	//iniciamos la sesión
			$nodosRepuesto =array();	//creamos el array
		 

			//COMPROBAMOS SI EXISTE LA VARIABLE DE SESIÓN (ES DECIR, SI HEMOS GUARDADO ALGÚN NODO EN EL QUE DUDÁSEMOS)
			if(isset($_SESSION['nodosRepuesto'])){
				
				$nodosRepuesto = $_SESSION['nodosRepuesto'];	//Guardamos el array de la sesión en el array vacío
				array_push($nodosRepuesto,$nodoRepuesto);		//añadimos el nodo a la lista
				$_SESSION['nodosRepuesto']=$nodosRepuesto;		//Volvemos a guardar el array de la sesión, actualizado
				
			}
			
			
			else{
				array_push($nodosRepuesto,$nodoRepuesto);		//añadimos el nodo a la lista
				$_SESSION['nodosRepuesto']=$nodosRepuesto;
			}
			
			
		}


		//------------------------------------------------------
		//CALCULAMOS LO SIGUIENTES PASOS A SEGUIR

		$nodoSi = $nodo * 2;
		$nodoNo = $nodo * 2 + 1;

		$nodoProbablementeSi = $nodoSi;
		$nodoProbablementeNo = $nodoNo;

		//-----------------------------------------------------
		//OBTENEMOS UN NÚMERO AL AZAR ENTRE CERO Y UNO
		//lo hacemos para evitar que tenga una tendencia a recorrer siempre el mismo camino

		$aleatrio = rand(0,1);

		$nodoAleatorio 	  = 0;	//EL QUE ELEGIMOS
		$nodoAleatorioAlt = 0;	//EL CONTRARIO

		if($aleatrio==0){
			$nodoAleatorio = $nodoNo;
			$nodoAleatorioAlt = $nodoSi;
		}

		else{
			$nodoAleatorio = $nodoSi;
			$nodoAleatorioAlt = $nodoNo;
		}
		//-----------------------------------------------------


		//HACEMOS LA CONSULTA A LA BD
		$consulta = "SELECT texto,pregunta FROM arbol WHERE nodo = ".$nodo.";";

		$texto = '';
		$pregunta = true;
		 
		if ($resultado = mysqli_query($enlace, $consulta)) {
		 
			if($resultado->num_rows === 0)
		    {
		        echo 'No existe el nodo';
		    }

			else{
				while ($fila = mysqli_fetch_row($resultado)) {
					$texto 	  = $fila[0];
					$pregunta = $fila[1];
				}
				?>
				<div class = "row">		
					<?php
					//SI NO ES UNA PREGUNTA ES UN RESULTADO FINAL (DA UNA RESPUESTA)
					echo "<div class='carta'>";
					echo "<h2 class='numPregunta'>".$numPregunta."</h2>";
					echo "</div>";
					?>
					<img src="imagenes/malphass.png">
				

				<?php if($pregunta == 0){
					
					echo "<div class='contenedorPregunta'>";
					echo "<h2 class='pregunta'>¿Te gustaria un libro de ". $texto . "?</h2>";
					echo "</div>";?>

				</div>
				<?php	
					echo "<div class='cartas'>";
					echo "<div class='boton1'>";
					echo "<a href='respuesta.php?r=1&n=".$nodo."&p=".$texto."&np=".$proxPregunta."'>SÍ</a>";
					echo "</div>";
					echo "<div class='boton1'>";
					echo "<a href='respuesta.php?r=0&n=".$nodo."&p=".$texto."&np=".$proxPregunta."'>NO</a>";
					echo "</div>";
				
				}
				//SI ES UNA PREGUNTA, PREGUNTAMOS (SI DUDAMOS, EN EL PARÁMETRO "R" GUARDAMOS LA RAMA ALTERNATIVA, SINO VALE CERO)
				else{
					echo "<div class='contenedorPregunta'>";
					echo "<h2 class='pregunta'>¿Te gustaria un libro ". $texto . "?</h2>";/// cambio de nodo
					echo "</div>";?>
				</div>

					<?php
					echo "<div class='cartas'>";
					echo "<div class='boton1'>";
					echo "<a  href='index.php?n=".$nodoSi."&r=0&np=".$proxPregunta."'>SÍ</a>";
					echo "</div>";
					echo "<div class='boton1'>";
					echo "<a  href='index.php?n=".$nodoNo."&r=0&np=".$proxPregunta."'>NO</a>";
					echo "</div>";
					/*echo "<div class='boton1'>";
					echo "<a  href='index.php?n=".$nodoProbablementeSi."&r=".$nodoProbablementeNo."&np=".$proxPregunta."'>PROBABLEMENTE</a>";
					echo "</div>";
					echo "<div class='boton1' >";
					echo "<a href='index.php?n=".$nodoProbablementeNo."&r=".$nodoProbablementeSi."&np=".$proxPregunta."'>PROBABLEMENTE NO</a>";
					echo "</div>";
					echo "<div class='boton1'>";
					echo "<a  href='index.php?n=".$nodoAleatorio."&r=".$nodoAleatorioAlt."&np=".$proxPregunta."'>NO LO SÉ</a>";
					echo "</div>";
					echo "<div class='limpiar'></div>";
				
					echo "</div>";*/
				}
				
			}

		    mysqli_free_result($resultado);
		}

		?>

	</main>

	<footer>

	<?php
		echo "<a class='btn_probar'href='index.php?n=1&r=0'>Volver a probar</a>";
		echo "<br><br><a class='btn_datos' href='datos.php'>Datos del recomendador</a>";
	?>

	</footer>



</body>
</html>