<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$total_horas_practicas=0.0;


//COGEMOS PRACTICAS TUTELADAS

$array_creditos_pract = $formModel->getElementData('t_solicitudes_seminarios___creditos_asignados');

//SE SUMAN TODAS LAS PRACTICAS
for ($i=0; $i< count($array_creditos_pract); $i++)
{
    $creditos = $array_creditos_pract[$i];
    
    if($creditos != "")
    {
        $total_horas_practicas = $total_horas_practicas + (float) $creditos * 10.0;
    }
}
//porcentaje máximo
$horasmax = 20;

if($horasmax > $total_horas_practicas)
{
    $formModel->getForm()->error = "<b>Demasiadas pr&aacute;cticas tuteladas.<br>Ha elegido ". $total_horas_practicas ." horas de un total m&aacute;ximo de ".$horasmax. " hrs. Le sobran ". ($total_horas_practicas-$horasmax) ." horas";
    $formModel->errors['t_solicitudes_grupos___creditos_asignados'][] = 'Revise los créditos';
    //echo "El numero de horas del primer cuatrimestre supera el ".$factormax*100 ."% de la capacidad.";
    return false;
}

