<?php
/**
 * Fabrik List Template: Admin Row
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2016  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php
//Obtenemos la página actual
$active = JFactory::getApplication()->getMenu()->getActive();
if ($active->id == 147 || $active->id == 150) {
	$table = "t_grupos";
} elseif ($active->id == 140) {
	$table = "t_seminarios";
} else {
	$table = "t_tutelas";
}



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

$heading_id_asignatura = $table . "___asignatura_raw";
$id_asignatura_siguiente = (int)($this->_row->data->$heading_id_asignatura);

if ($GLOBALS['id_asignatura'] != $id_asignatura_siguiente && $id_asignatura_siguiente != 0) {
	$GLOBALS['id_asignatura'] = $id_asignatura_siguiente;

	$sql = "select ta.id as id_asignatura, ta.curso, ta.cuatrimestre, ta.denominacion as asignatura, tt.titulacion, tc.nombre as coordinador from " . $table . " tg 
			inner join t_asignaturas ta on tg.asignatura = ta.id
			inner join t_titulaciones tt on ta.titulacion = tt.id
			left join t_usuarios tc on ta.coordinador = tc.id
			where ta.id = " . (int)($GLOBALS['id_asignatura']);
	$stmt_grupo = $con->prepare($sql);
	$stmt_grupo->execute();

	$result_grupos = $stmt_grupo->get_result();

	if ($result_grupos->num_rows > 0) {
		$row = $result_grupos->fetch_assoc();
		$id_asignatura = $row["id_asignatura"];
		$titulacion = $row["titulacion"];
		$curso = "Curso " . $row["curso"] . "&ordm;";
		
		switch ($row["cuatrimestre"]) {
			case '1':
				$cuatrimestre = "1er Cuatrimestre";
				break;
			case '2':
				$cuatrimestre = "2&ordm; Cuatrimestre";
				break;
			case '3':
				$cuatrimestre = "1er y 2&ordm; Cuatrimestre";
			default:
				$cuatrimestre = "";
		}

		$coordinador = $row["coordinador"];
		
	}
	$stmt_grupo->close(); 


?>
<tr class="info-titulacion">
	<td colspan="<?php echo $this->colCount;?>">
		<?php
		$heading_asignatura = $table . "___asignatura";
		//$heading_titulacion = "t_titulaciones___titulacion";
		//$heading_curso = "t_asignaturas___curso";
		//$heading_cuatrimestre = "t_asignaturas___cuatrimestre";
		//$heading_coordinador = "t_asignaturas___coordinador";
		
		$asignatura = (string)($this->_row->data->$heading_asignatura);
		//$titulacion = (string)($this->_row->data->$heading_titulacion);
		//$curso = (string)($this->_row->data->$heading_curso);
		//$cuatrimestre = (string)($this->_row->data->$heading_cuatrimestre);
		//$coordinador = (string)($this->_row->data->$heading_coordinador);

		if (isset($_GET['group_by'])) {
		    echo $asignatura ." - ";
		} 
		echo $titulacion. " - " . $curso . " - " . $cuatrimestre ;
		
		?>
	</td>
</tr>

<?php 
}
?>

<?php
	if ($active->id == 147 || $active->id == 150) {
?>
<tr id="<?php echo $this->_row->id;?>" class="<?php echo $this->_row->class;?>">
	<?php 
	$heading_tipo_grupo = "t_grupos___tipo_grupo";
	foreach ($this->headings as $heading => $label) {
		$style = empty($this->cellClass[$heading]['style']) ? '' : 'style="'.$this->cellClass[$heading]['style'].'"';
		
		$tipo_grupo = strip_tags( (string)($this->_row->data->$heading_tipo_grupo));
		if ($tipo_grupo=="TEORIA" && (strpos($heading, "___grupo") || strpos($heading, "___tipo_grupo")) ) {
			$tdclass = "tdgrey ";
		} else {
			$tdclass = "";
		}
		?>
		<td class="<?php echo $tdclass;?> <?php echo $this->cellClass[$heading]['class']?>" <?php echo $style?>>
			<?php echo isset($this->_row->data) ? $this->_row->data->$heading : '';?>
		</td>
	<?php }?>
</tr>
<?php 
	} else {
?>

<tr id="<?php echo $this->_row->id;?>" class="<?php echo $this->_row->class;?>">
	<?php foreach ($this->headings as $heading => $label) {
		$style = empty($this->cellClass[$heading]['style']) ? '' : 'style="'.$this->cellClass[$heading]['style'].'"';
		?>
		<td class="<?php echo $this->cellClass[$heading]['class']?>" <?php echo $style?>>
			<?php echo isset($this->_row->data) ? $this->_row->data->$heading : '';?>
		</td>
	<?php }?>
</tr>


<?php
	}
?>