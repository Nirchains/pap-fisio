<?php

// No direct access
defined('_JEXEC') or die('Restricted access');


//$user   = JFactory::getUser();
//$userid = $user->get('id');

$usuarios = $formModel->getElementData('t_solicitudes___usuario');
$userid = $usuarios[0];

/*echo "<br>Usuario:". $userid ."<br>";*/
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

//COGEMOS LA CAPACIDAD DEL USUARIO 
$sql_usuario = "select capacidad from t_usuarios where id = ". $userid;

$result = $con->query($sql_usuario);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $capacidad = (float) $row["capacidad"];
} else {
    $capacidad = 0.0;
}



//COGEMOS ASIGNATURAS Y CREDITOS DE LA SOLICITUD
$array_asignaturas = $formModel->getElementData('t_solicitudes_grupos___asignaturas_raw');
$array_creditos = $formModel->getElementData('t_solicitudes_grupos___creditos_asignados');

$total_1q=0.0;
$total_2q=0.0;

//SE SUMAN TODAS LAS ASIGNATURAS
for ($i=0; $i< count($array_asignaturas); $i++)
{
    $asignatura = $array_asignaturas[$i][0];
    $creditos = $array_creditos[$i];
    
    //BUSCAMOS EL CUATRIMESTRE DE LA ASGINATURA
    if($asignatura != "")
    {
        $sql_asignatura = "select cuatrimestre from t_asignaturas where id = ". $asignatura.";";
        $result = $con->query($sql_asignatura);
        $row = $result->fetch_assoc();
        $cuatrimestre = $row["cuatrimestre"];
        
        switch ($cuatrimestre)
        {
            case 1://primer cuatrimestre
                $total_1q=$total_1q + (float) $creditos * 10.0;
                break;
            case 2://segundo cuatrimestre
                $total_2q=$total_2q + (float) $creditos * 10.0;
                break;
            case 3: //primer y segundo cuatrimestre;
                //$total_1q=$total_1q + (float) $creditos * 10.0;
                //$total_2q=$total_2q + (float) $creditos * 10.0;
                break;
        }
        /*
        echo "<br>Asignatura:".$asignatura;
        echo "- Cuatrimestre:".$cuatrimestre;
        echo "- Creditos:". $creditos;
        */
    }

    
}
//COGEMOS PRACTICAS TUTELADAS
$array_practicas = $formModel->getElementData('t_solicitudes_seminarios___asignaturas_raw');
$array_creditos_pract = $formModel->getElementData('t_solicitudes_seminarios___creditos_asignados');

//SE SUMAN TODAS LAS PRACTICAS
for ($i=0; $i< count($array_practicas); $i++)
{
    $practica = $array_practicas[$i][0];
    $creditos = $array_creditos_pract[$i];
    
    //BUSCAMOS EL CUATRIMESTRE DE LA ASGINATURA
    if($practica != "")
    {
        $sql_practica = "select cuatrimestre from t_asignaturas where id = ". $practica.";";
        $result = $con->query($sql_practica);
        $row = $result->fetch_assoc();
        $cuatrimestre = $row["cuatrimestre"];
        
        switch ($cuatrimestre)
        {
            case 1://primer cuatrimestre
                $total_1q=$total_1q + (float) $creditos * 10.0;
                break;
            case 2://segundo cuatrimestre
                $total_2q=$total_2q + (float) $creditos * 10.0;
                break;
            case 3: //primer y segundo cuatrimestre;
               // $total_1q=$total_1q + (float) $creditos * 10.0;
                //$total_2q=$total_2q + (float) $creditos * 10.0;
                break;
        }
        
        /*
         echo "<br>Practica:".$practica;
         echo "- Cuatrimestre:".$cuatrimestre;
         echo "- Creditos:". $creditos;
         */
    }
}
//porcentaje máximo
$factormax = 0.7;
/*
echo "<br> 1Q:". $total_1q;
echo "<br> 2Q:". $total_2q;
echo "<h3>Capacidad:" . $capacidad . "</h3>";
*/
$capacidadmax=$capacidad*$factormax;

if($total_1q > $capacidadmax)
{
    $formModel->getForm()->error = "<b>El numero de horas del primer cuatrimestre supera el ".$factormax*100 ."% de sus ".$capacidad." horas de capacidad docente.</b> <br> Horas m&aacute;ximas permitidas por cuatrimestre: ".$capacidadmax." hrs.<br> Horas totales elegidas: ".$total_1q." hrs<br>Le sobran ".($total_1q-$capacidadmax) ." horas del primer cuatrimestre.";
    $formModel->errors['t_solicitudes_grupos___creditos_asignados'][] = 'Revise los créditos';
    //echo "El numero de horas del primer cuatrimestre supera el ".$factormax*100 ."% de la capacidad.";
    return false;

}
if($total_2q > $capacidadmax)
{
    $formModel->getForm()->error = "<b>El numero de horas del segundo cuatrimestre supera el ".$factormax*100 ."% de sus ".$capacidad." horas de capacidad docente.</b> <br> Horas m&aacute;ximas permitidas por cuatrimestre: ".$capacidadmax." hrs.<br> Horas totales elegidas: ".$total_2q." hrs<br>Le sobran ".($total_2q-$capacidadmax) ." horas del segundo cuatrimestre.";
    $formModel->errors['t_solicitudes_grupos___creditos_asignados'][] = 'Revise los créditos';
    //echo "El numero de horas del segundo cuatrimestre supera el ".$factormax*100 ."% de la capacidad.";
    return false;
    
}
//exit;