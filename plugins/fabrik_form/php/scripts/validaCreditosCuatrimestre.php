<?php

// No direct access
defined('_JEXEC') or die('Restricted access');


$user   = JFactory::getUser();
$userid = $user->get('id');

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

echo "<h3>Capacidad:" . $capacidad . "</h3>";

//COGEMOS ASIGNATURAS Y CREDITOS DE LA SOLICITUD
$array_asignaturas = $formModel->getElementData('t_solicitudes_grupos___asignaturas_raw');
$array_creditos = $formModel->getElementData('t_solicitudes_grupos___creditos_asignados');

$total_1q=0.0;
$total_2q=0.0;


for ($i=0; $i< count($array_asignaturas); $i++)
{
    $asignatura = $array_asignaturas[$i][0];
    $creditos = $array_creditos[$i];
    
    //BUSCAMOS EL CUATRIMESTRE DE LA ASGINATURA
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
            $total_1q=$total_1q + (float) $creditos * 10.0;
            $total_2q=$total_2q + (float) $creditos * 10.0;
            break;
    }
    
    
    echo "<br>Asignatura:".$asignatura;
    echo "<br>Cuatrimestre:".$cuatrimestre;
    echo "<br> Creditos:". $creditos;

}
echo "<br> 1Q:". $total_1q;
echo "<br> 2Q:". $total_2q;
if($total_1q > ($capacidad/0.7))
{
    $formModel->getForm()->error = "El numero de horas del primer cuatrimestre supera el 70% de la capacidad.";
    //return "El numero de horas del primer cuatrimestre supera el 70% de la capacidad.";
    return false;
}
if($total_2q > ($capacidad/0.7))
{
   $formModel->getForm()->error = "El numero de horas del segundo cuatrimestre supera el 70% de la capacidad.";
   //return "El numero de horas del segundo cuatrimestre supera el 70% de la capacidad.";
    return false;
}
return false;