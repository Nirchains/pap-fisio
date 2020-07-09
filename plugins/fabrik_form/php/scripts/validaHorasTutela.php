<?php

// No direct access
defined('_JEXEC') or die('Restricted access');


//Recogemos los datos de conexión a la BD
$app= JFactory::getApplication();
$host = $app->getCfg('host');
$userbd = $app->getCfg('user');
$passbd = $app->getCfg('password');
$fabrikdb =  $app->getCfg('db2');

$con=mysqli_connect($host,$userbd,$passbd,$fabrikdb);

if (mysqli_connect_errno()) {
    $formModel->getForm()->error = "Failed to connect to MySQL: " . mysqli_connect_error();
}


$total_horas_practicas=0.0;


//COGEMOS PRACTICAS TUTELADAS

$array_creditos_pract = $formModel->getElementData('t_solicitudes_seminarios___creditos_asignados');
$array_seminarios = $formModel->getElementData('t_solicitudes_seminarios___seminarios_raw');
//echo "Array:".print_r($array_creditos_pract);
//echo "Array:".print_r($array_seminarios);

//SE SUMAN TODAS LAS PRACTICAS
for ($i=0; $i< count($array_creditos_pract); $i++)
{
	//echo "<br>-". $array_seminarios[$i][0]. "-<br>";
	$id_seminario = $array_seminarios[$i][0];

	if (!empty($id_seminario)) {
		$sql_seminario = "select seminario from t_seminarios where id = ". $id_seminario.";";
	    $result = $con->query($sql_seminario);
	    $row = $result->fetch_assoc();
	    $seminario = $row["seminario"];
	    
		if (strpos($seminario, "TUTELA ACADEMICA")!==false) {
		    $creditos = $array_creditos_pract[$i];
		    
		    if($creditos != "")
		    {
		        //echo "<br>CR: ".$creditos."<br>";
		        $total_horas_practicas = $total_horas_practicas + (float) $creditos * 10.0;
		    }
		}
	}
}
//porcentaje máximo
$horasmax = 20;
//echo "<br>Horas totales:".$total_horas_practicas;
if($total_horas_practicas > $horasmax)
{
    $formModel->getForm()->error = "<b>Demasiadas horas de tutela acad&eacute;mica</b>.<br>Ha elegido ". $total_horas_practicas ." horas de un total m&aacute;ximo de ".$horasmax. " horas. Le sobran ". ($total_horas_practicas-$horasmax) ." horas.";
    $formModel->errors['t_solicitudes_grupos___creditos_asignados'][] = 'Revise los créditos';
    //echo "El numero de horas del primer cuatrimestre supera el ".$factormax*100 ."% de la capacidad.";
    $con->close();
    return false;
}

$con->close();