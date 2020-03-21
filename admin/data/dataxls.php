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
?>