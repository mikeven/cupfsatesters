<?php

define( "SP", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );

/* ----------------------------------------------------------------------------------- */
function leerArchivo( $archivo, $narchivo ){
	//Lectura de archivo en formato xls / xlsx
	
	require_once( "PHPExcel.php" );
	require_once( "PHPExcel/Reader/Excel2007.php" );
	//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$objReader = new PHPExcel_Reader_Excel2007();
	$carga = 0; $archivo_cargado = false; $num_cliente_const = true;
	$items = array();
	$linea = 2;				// Línea inicial de lectura
	
	if( file_exists ( $archivo ) ){
		$objPHPExcel = $objReader->load( $archivo );
		$archivo_cargado = true;
	}else 
		$carga = -3;

	if( ( $archivo_cargado ) ){

		$valor = $ncliente = $objPHPExcel->getActiveSheet()->getCell( "A".$linea )->getValue();
		while( $valor != "" ){
			$reg["referencia"] 	= $objPHPExcel->getActiveSheet()->getCell( "B".$linea )->getValue();
			$reg["cantidad"] 	= $objPHPExcel->getActiveSheet()->getCell( "C".$linea )->getValue();
			$items[] 			= $reg;

			$linea++;
			$valor = $objPHPExcel->getActiveSheet()->getCell( "A".$linea )->getValue();
			if( $valor != "" ){
				if( $valor != $ncliente ) $num_cliente_const = false;
			}
		}
		
		$carga = 1;
	}
	$resultado["exito"] 	= $carga;
	$resultado["cliente"] 	= $ncliente;
	$resultado["nc_const"] 	= $num_cliente_const;
	$resultado["items"] 	= $items;

	return $resultado;
}
/* ----------------------------------------------------------------------------------- */
function leerArchivoInventario( $archivo, $narchivo ){
	//Lectura de archivo en formato xls / xlsx para carga de inventario
	
	require_once( "PHPExcel.php" );
	require_once( "PHPExcel/Reader/Excel2007.php" );
	//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$objReader = new PHPExcel_Reader_Excel2007();
	$carga = 0; $archivo_cargado = false;
	$items = array();
	$linea = 2;				// Línea inicial de lectura
	
	if( file_exists ( $archivo ) ){
		$objPHPExcel = $objReader->load( $archivo );
		$archivo_cargado = true;
	}else 
		$carga = -3;

	if( ( $archivo_cargado ) ){

		$valor = $objPHPExcel->getActiveSheet()->getCell( "A".$linea )->getValue();
		while( $valor != "" ){
			$reg["desc1"] 		= $objPHPExcel->getActiveSheet()->getCell( "A".$linea )->getValue();
			$reg["desc2"] 		= $objPHPExcel->getActiveSheet()->getCell( "B".$linea )->getValue();
			$reg["desc3"] 		= $objPHPExcel->getActiveSheet()->getCell( "C".$linea )->getValue();
			$reg["referencia"] 	= $objPHPExcel->getActiveSheet()->getCell( "D".$linea )->getValue();
			$reg["cantidad"] 	= $objPHPExcel->getActiveSheet()->getCell( "E".$linea )->getValue();
			$items[] 			= $reg;

			$linea++;
			$valor = $objPHPExcel->getActiveSheet()->getCell( "A".$linea )->getValue();
		}
		
		$carga = 1;
	}
	$resultado["exito"] 	= $carga;
	$resultado["items"] 	= $items;

	return $resultado;
}
?>