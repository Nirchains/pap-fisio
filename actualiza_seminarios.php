<tr class="fabrik_row actualiza-seminarios"><td><b>Actualizando seminarios</b></td></tr>
<?php
//ACTUALIZAMOS LA TABLA DE SEMINARIOS
echo "<br><br><br>-------------ACTUALIZANDO TABLA DE SEMINARIOS..............<br>";
$sql_grupos = "select id as seminario from t_seminarios";
$stmt_grupos = $con->prepare($sql_grupos);
$stmt_grupos->execute();

$result_grupos = $stmt_grupos->get_result();

while ($row = $result_grupos->fetch_assoc()) {
	$grupo = $row["seminario"];

  	//Obtenemos la suma de creditos asignados, la diferencia y el balance
	$sql_creditos_asignados = "select sum(ss.creditos_asignados) as sum, g.creditos
	from t_solicitudes  s
	inner join t_solicitudes_seminarios ss on s.id = ss.parent_id
	inner join t_seminarios g on g.id = ss.seminarios
	where s.validada = 1 and ss.seminarios = ?";
	$stmt_creditos_asignados = $con->prepare($sql_creditos_asignados);
	$stmt_creditos_asignados->bind_param('i',$grupo);
	$stmt_creditos_asignados->execute();

	$result_creditos_asignados = $stmt_creditos_asignados->get_result();

	if ($result_creditos_asignados->num_rows > 0) {
		$row = $result_creditos_asignados->fetch_assoc();
		$creditos_asignados = (float)($row["sum"]);
		$diferencia = (float)($creditos_asignados - $row["creditos"]);
	} else {
		$creditos_asignados = 0;
		$diferencia = 0;
	}
	$stmt_creditos_asignados->close();

	if ($diferencia>0) {
		$balance = "g-positivo";
	} else if ($diferencia<0) {
		$balance = "g-negativo";
	} else {
		$balance = "g-cero";
	}

  	//actualiza la tabla de seminarios
	$update_query = "update t_seminarios 
	set creditos_asignados = ?,
	diferencia = ?,
	balance = ?
	where id = ?";
	$stmt_update_grupos = $con->prepare($update_query);
	$stmt_update_grupos->bind_param('ddsi',$creditos_asignados,$diferencia,$balance,$grupo);
	$stmt_update_grupos->execute();
  	//print_r($stmt_update_usuarios);
	if ($stmt_update_grupos->affected_rows) {
		echo "<tr class='fabrik_row'><td>Seminario ".$grupo." actualizado.</td></tr>";	
	}

	$stmt_update_grupos->close();

} 

$stmt_grupos->close();

?>
<tr class="fabrik_row actualiza-seminarios"><td><b>Seminarios actualizados</b></td></tr>