<?php

define( "SP", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );

/* ----------------------------------------------------------------------------------- */
function leerArchivo( $archivo, $narchivo ){
	//Lectura de archivo en formato xls / xlsx
	
	require_once( "PHPExcel.php" );
	require_once( "PHPExcel/Reader/Excel2007.php" );
	//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$objReader = new PHPExcel_Reader_Excel2007();
	$carga = 0; $archivo_cargado = false;
	$xceltab = 0;	//Pestaña de archivo a leer
	$linea = 2;
	
	if( file_exists ( $archivo ) ){
		$objPHPExcel = $objReader->load( $archivo );
		$ntabs = $objPHPExcel->getSheetCount();
		$archivo_cargado = true;
	}else 
		$carga = -3;
	$imp = "";

	if( ( $archivo_cargado ) ){

		$val_l = $objPHPExcel->getActiveSheet()->getCell( "B".$linea )->getValue();
		while( $val_l != "" ){
			$imp .= $val_l."<br>";
			$linea++;
			$val_l = $objPHPExcel->getActiveSheet()->getCell( "B".$linea )->getValue();
		}
		
		$carga = 1;
	}
	$resultado["exito"] = $carga;
	$resultado["imp"] = $imp;

	return $resultado;
}
/* ----------------------------------------------------------------------------------- */
?>