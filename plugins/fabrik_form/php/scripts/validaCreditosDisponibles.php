<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

//Recogemos los datos de conexión a la BD
$app= JFactory::getApplication();
$host = $app->getCfg('host');
$userbd = $app->getCfg('user');
$passbd = $app->getCfg('password');
$fabrikdb =  $app->getCfg('db2');

$ok = true;

$con=mysqli_connect($host,$userbd,$passbd,$fabrikdb);

if (mysqli_connect_errno()) {
    $formModel->getForm()->error = "Failed to connect to MySQL: " . mysqli_connect_error();
}

//COGEMOS ASIGNATURAS Y CREDITOS DE LA SOLICITUD
$array_grupos = $formModel->getElementData('t_solicitudes_grupos___grupos_raw');
$array_creditos = $formModel->getElementData('t_solicitudes_grupos___creditos_asignados');

//Recorremos los grupos
for ($i=0; $i < count($array_grupos); $i++)
{
    $grupo = $array_grupos[$i][0];
    $creditos = $array_creditos[$i];
    
    if ($creditos>0) {
        //Elegimos los créditos disponibles
        $sql_diferencia = "select diferencia from t_grupos where id = ". $grupo;

        $result = $con->query($sql_diferencia);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $diferencia = -(float) $row["diferencia"];
        } else {
            $diferencia = 0.0;
        }

        //por rendimiento, hacemos una primera comprobación
        if ($creditos > $diferencia) {
            $validada = $formModel->_origData[0]->t_solicitudes___validada;
            //Si está validada, restamos a la diferencia los créditos de la solicitud ya validada, para saber los que están disponibles realmente
            if ($validada==1) {
                $solicitud = $formModel->getElementData('t_solicitudes_grupos___parent_id')[0];
                $sql_creditos_solicitud_anterior = "select creditos_asignados from t_solicitudes_grupos where parent_id = " . $solicitud . " and  grupos = " . $grupo;
                $result = $con->query($sql_creditos_solicitud_anterior);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $creditos_solicitud_anterior = (float) $row["creditos_asignados"];
                    $diferencia += $creditos_solicitud_anterior;
                }
            }
        }
        //echo "Validada: " .$validada;
        //echo "<br>Creditos: " .$creditos;
        //echo "<br>Diferencia: " .$diferencia;
        //echo "<br>Creditos sol-anterior: " .$sql_creditos_solicitud_anterior;
        //exit;

        if ($creditos > $diferencia) {
            $formModel->getForm()->error = "<b>Error en las asignaturas: Ha elegido más créditos de los disponibles para el/los grupo/s marcado/s con <i data-isicon='true' class='icon-warning '></i></b>";
            $formModel->errors['t_solicitudes_grupos___creditos_asignados'][$i] = 'Revise los créditos';
            $ok = false;
        }
    }

//REPETIMOS LO MISMO PERO CON LAS TUTELAS
//COGEMOS ASIGNATURAS Y CREDITOS DE LA SOLICITUD
$array_grupos = $formModel->getElementData('t_solicitudes_seminarios___seminarios_raw');
$array_creditos = $formModel->getElementData('t_solicitudes_seminarios___creditos_asignados');

//Recorremos los grupos
for ($i=0; $i < count($array_grupos); $i++)
{
    $grupo = $array_grupos[$i][0];
    $creditos = $array_creditos[$i];
    
    if ($creditos>0) {
        //Elegimos los créditos disponibles
        $sql_diferencia = "select diferencia from t_seminarios where id = ". $grupo;

        $result = $con->query($sql_diferencia);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $diferencia = -(float) $row["diferencia"];
        } else {
            $diferencia = 0.0;
        }

        //por rendimiento, hacemos una primera comprobación
        if ($creditos > $diferencia) {
            $validada = $formModel->_origData[0]->t_solicitudes___validada;
            //Si está validada, restamos a la diferencia los créditos de la solicitud ya validada, para saber los que están disponibles realmente
            if ($validada==1) {
                $solicitud = $formModel->getElementData('t_solicitudes_seminarios___parent_id')[0];
                $sql_creditos_solicitud_anterior = "select creditos_asignados from t_solicitudes_seminarios where parent_id = " . $solicitud . " and  seminarios = " . $grupo;
                $result = $con->query($sql_creditos_solicitud_anterior);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $creditos_solicitud_anterior = (float) $row["creditos_asignados"];
                    $diferencia += $creditos_solicitud_anterior;
                }
            }
        }
        //echo "Validada: " .$validada;
        //echo "<br>Creditos: " .$creditos;
        //echo "<br>Diferencia: " .$diferencia;
        //echo "<br>Creditos sol-anterior: " .$sql_creditos_solicitud_anterior;
        //exit;

        if ($creditos > $diferencia) {
            $formModel->getForm()->error = "<b>Error en las prácticas tuteladas: Ha elegido más créditos de los disponibles para el/los grupo/s marcado/s con <i data-isicon='true' class='icon-warning '></i></b>";
            $formModel->errors['t_solicitudes_seminarios___creditos_asignados'][$i] = 'Revise los créditos';
            $ok = false;
        }
    }
}


    $con->close();
    return $ok;
}

