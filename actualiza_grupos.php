<tr class="fabrik_row actualiza-grupos"><td><b>Actualizando grupos</b></td></tr>
<?php

//borramos las solicitudes_grupo sin padre
$sql = "delete from t_solicitudes_grupos where parent_id not in (select id from t_solicitudes)";
$stmt_delete_grupos = $con->prepare($sql);
$stmt_delete_grupos->execute();
$stmt_delete_grupos->close();


$sql_grupos = "select id as grupo from t_grupos";
$stmt_grupos = $con->prepare($sql_grupos);
$stmt_grupos->execute();

$result_grupos = $stmt_grupos->get_result();

while ($row = $result_grupos->fetch_assoc()) {
	$grupo = $row["grupo"];

  	//Obtenemos la suma de creditos asignados, la diferencia y el balance
	$sql_creditos_asignados = "select sum(sg.creditos_asignados) as sum, g.creditos
	from t_solicitudes s 
	inner join t_solicitudes_grupos sg on s.id = sg.parent_id 
	inner join t_grupos g on g.id = sg.grupos
	where s.validada = 1 and sg.grupos = ?";

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

	$update_query = "update t_grupos 
	set creditos_asignados = ?,
	diferencia = ?,
	balance = ?
	where id = ?";
	$stmt_update_grupos = $con->prepare($update_query);
	$stmt_update_grupos->bind_param('ddsi',$creditos_asignados,$diferencia,$balance,$grupo);
	$stmt_update_grupos->execute();
  	//print_r($stmt_update_usuarios);
	if ($stmt_update_grupos->affected_rows) {
		echo "<tr class='fabrik_row'><td>Grupo ".$grupo." actualizado.</td></tr>";	
	
	}

	$stmt_update_grupos->close();

} 

$stmt_grupos->close();

?>
<tr class="fabrik_row actualiza-grupos"><td><b>Grupos actualizados</b></td></tr>