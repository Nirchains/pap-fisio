<?php
/**
 * This is a sample email template. It will just print out all of the request data:
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.form.email
 * @copyright   Copyright (C) 2005-2016  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Alter these settings to limit what is shown in the email:

// Set this to show the raw element values.
$raw = false;

// Set this to true to show non element field values in the email e.g. "option: com_fabrik"
$info = false;


/**
 * Will attempt to get the element for the posted key
 *
 * @param   object  $formModel  Form model
 * @param   string  $key        POST key value
 *
 * @return  array(label, is the key a raw element, should we show the element)
 */
function tryForLabel($formModel, $key, $raw, $info)
{

	$elementModel = $formModel->getElement($key);
	$label = $key;
	$thisRaw = false;
	if ($elementModel)
	{
		$label = $elementModel->getElement()->label;
	}
	else
	{
		if (substr($key, -4) == '_raw')
		{
			$thisRaw = true;
			$key = substr($key, 0, strlen($key) - 4);
			$elementModel = $formModel->getElement($key);
			if ($elementModel)
			{
				$label = $elementModel->getElement()->label . ' (raw)';
			}
		}
	}
	$show = true;
	if (($thisRaw && !$raw) || (!$elementModel && !$info))
	{
		$show = false;
	}
	return array($label, $thisRaw, $show);
}

const styleeliminado = "style='background-color: #ffe2e7;padding-left:4px;padding-right:4px;'";
const stylemodificado = "style='background-color: #e2e4ff;padding-left:4px;padding-right:4px;'";
const stylenuevo = "style='background-color: #f0ffe2;padding-left:4px;padding-right:4px;'";

$origDataGruposArr = [];
$origDataGruposArrId = [];
$origDataSeminariosArr = [];
$origDataSeminariosArrId = [];

foreach ($origData as $key => $value) {
	//grupos
	if (!in_array($value->t_solicitudes_grupos___grupos_raw, $origDataGruposArrId)) {
		array_push($origDataGruposArrId, $value->t_solicitudes_grupos___grupos_raw);
		array_push($origDataGruposArr, $value);
	}
	//seminarios
	if (!in_array($value->t_solicitudes_seminarios___seminarios_raw, $origDataSeminariosArrId)) {
		array_push($origDataSeminariosArrId, $value->t_solicitudes_seminarios___seminarios_raw);
		array_push($origDataSeminariosArr, $value);
	}
}

$newDataGruposArr = [];
$newDataGruposArrId = [];
$newDataSeminariosArr = [];
$newDataSeminariosArrId = [];

foreach ($newData as $key => $value) {
	//grupos
	if (!in_array($value->t_solicitudes_grupos___grupos_raw, $newDataGruposArrId)) {
		array_push($newDataGruposArrId, $value->t_solicitudes_grupos___grupos_raw);
		array_push($newDataGruposArr, $value);
	}
	//seminarios
	if (!in_array($value->t_solicitudes_seminarios___seminarios_raw, $newDataSeminariosArrId)) {
		array_push($newDataSeminariosArrId, $value->t_solicitudes_seminarios___seminarios_raw);
		array_push($newDataSeminariosArr, $value);
	}
}

?>
<fieldset><legend>Leyenda</legend>
<p <?php echo styleeliminado?>>Eliminado</p>
<p <?php echo stylemodificado?>>Modificado</p>
<p <?php echo stylenuevo?>>Nuevo</p>
</fieldset>
<table border="1">
<?php
	$arrshow = ["t_solicitudes___abierta", "t_solicitudes___validada", "t_solicitudes___date_time", "t_solicitudes___usuario", "t_solicitudes___total_creditos_solicitud", "t_solicitudes___notas"];

	foreach ($this->emailData as $key => $val)
	{
		if (in_array($key, $arrshow)) {
			// Lets see if we can get the element name:
			list($label, $thisRaw, $show) = tryForLabel($formModel, $key, $raw, $info);

			if (!$show)
			{
				continue;
			}

			if (strpos($key, "t_solicitudes_grupos___") || strpos($key, "t_solicitudes_seminarios___") ||strpos($key, "t_solicitudes_tutelas___tutelas")):
			else:
				if (is_array($val)):
				else:
					echo  "<tr><td style='background-color: #eeeeee;'>" . $label . "</td><td>";
					if ($origData[0]->$key!=$newData[0]->$key) {
						echo $origData[0]->$key . " -> <span " . stylemodificado . ">". $newData[0]->$key . "</span>";
					} else {
						echo $val;
					}
				endif;
			endif;

			echo "</td></tr>";
		}
	}

	//GRUPOS
	echo "<tr><td style='background-color: #eeeeee;'>Grupos</td><td>";
	echo "<table border='1' width='100%'><tbody>";
	echo "<tr><th colspan='3' style='background-color: #eeeeee;'><b>DATOS ORIGINALES</b></th></tr>";
	echo "<tr><th>ASIGNATURA</th><th>GRUPO</th><th>CRÉDITOS</th></tr>";

	foreach ($origDataGruposArr as $value) {
		//echo "<br><b>Valor: </b>" . json_encode($value) . "<br><br>";
		//echo json_encode($newDataGruposArr);
		$ret = estadoAsignatura($value, $newDataGruposArr);

		if ($ret == -1) {
			echo "<tr " . styleeliminado . ">";
		} elseif ($ret == 1) {
			echo "<tr " . stylemodificado . ">";
		} else {
			echo "<tr>";
		}

		echo "<td>" . $value->t_solicitudes_grupos___asignaturas . "</td>";
		echo "<td>" . $value->t_solicitudes_grupos___grupos . "</td>";
		echo "<td>" . $value->t_solicitudes_grupos___creditos_asignados . "</td></tr>";
	}

	echo "<tr><th colspan='3' style='background-color: #eeeeee;'><b>DATOS NUEVOS</b></th></tr>";
	echo "<tr><th>ASIGNATURA</th><th>GRUPO</th><th>CRÉDITOS</th></tr>";
	
	foreach ($newDataGruposArr as $value) {
		$ret = estadoAsignatura($value, $origDataGruposArr);

		if ($ret == -1) {
			echo "<tr " . stylenuevo . ">";
		} elseif ($ret == 1) {
			echo "<tr " . stylemodificado . ">";
		} else {
			echo "<tr>";
		}

		echo "<td>" . $value->t_solicitudes_grupos___asignaturas . "</td>";
		echo "<td>" . $value->t_solicitudes_grupos___grupos . "</td>";
		echo "<td>" . $value->t_solicitudes_grupos___creditos_asignados . "</td></tr>";
	}

	echo "</tbody></table></td></tr>";

	//SEMINARIOS
	echo "<tr><td style='background-color: #eeeeee;'>Prácticas tuteladas</td><td>";
	echo "<table border='1' width='100%'><tbody>";
	echo "<tr><th colspan='3' style='background-color: #eeeeee;'><b>DATOS ORIGINALES</b></th></tr>";
	echo "<tr><th>ASIGNATURA</th><th>GRUPO</th><th>CRÉDITOS</th></tr>";
	foreach ($origDataSeminariosArr as $value) {
		$ret = estadoSeminario($value, $newDataSeminariosArr);

		if ($ret == -1) {
			echo "<tr " . styleeliminado . ">";
		} elseif ($ret == 1) {
			echo "<tr " . stylemodificado . ">";
		} else {
			echo "<tr>";
		}

		echo "<td>" . $value->t_solicitudes_seminarios___asignaturas . "</td>";
		echo "<td>" . $value->t_solicitudes_seminarios___seminarios . "</td>";
		echo "<td>" . $value->t_solicitudes_seminarios___creditos_asignados . "</td></tr>";
	}

	echo "<tr><th colspan='3' style='background-color: #eeeeee;'><b>DATOS NUEVOS</b></th></tr>";
	echo "<tr><th>ASIGNATURA</th><th>GRUPO</th><th>CRÉDITOS</th></tr>";
	
	foreach ($newDataSeminariosArr as $value) {
		$ret = estadoSeminario($value, $origDataSeminariosArr);

		if ($ret == -1) {
			echo "<tr " . stylenuevo . ">";
		} elseif ($ret == 1) {
			echo "<tr " . stylemodificado . ">";
		} else {
			echo "<tr>";
		}

		echo "<td>" . $value->t_solicitudes_seminarios___asignaturas . "</td>";
		echo "<td>" . $value->t_solicitudes_seminarios___seminarios . "</td>";
		echo "<td>" . $value->t_solicitudes_seminarios___creditos_asignados . "</td></tr>";
	}

	echo "</tbody></table></td></tr>";

	
?>
</table> 
<?php

function estadoAsignatura($asignatura, $arrbuscar) {
	foreach ($arrbuscar as $key => $value) {
		if ($value->t_solicitudes_grupos___grupos_raw == $asignatura->t_solicitudes_grupos___grupos_raw) { 
			if ($value->t_solicitudes_grupos___creditos_asignados != $asignatura->t_solicitudes_grupos___creditos_asignados) {
				//datos modificados
				return 1;
			} else {
				return 0;
			}
		}
	}
	return -1;
}

function estadoSeminario($asignatura, $arrbuscar) {
	foreach ($arrbuscar as $key => $value) {
		if ($value->t_solicitudes_seminarios___seminarios_raw == $asignatura->t_solicitudes_seminarios___seminarios_raw) {
			if ($value->t_solicitudes_seminarios___creditos_asignados != $asignatura->t_solicitudes_seminarios___creditos_asignados) {
				//datos modificados
				return 1;
			} else {
				return 0;
			}
		}
	}
	return -1;
}


//exit;
?>

