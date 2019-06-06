<tr class="fabrik_row actualiza-tutelas"><td><b>Actualizando tutelas</b></td></tr>
<?php
//ACTUALIZAMOS LA TABLA DE GRUPOS

$sql_grupos = "select id as tutela from t_tutelas";
$stmt_grupos = $con->prepare($sql_grupos);
$stmt_grupos->execute();


$result_grupos = $stmt_grupos->get_result();

while ($row = $result_grupos->fetch_assoc()) {

	$grupo = $row["tutela"];

  	//Obtenemos la suma de creditos asignados, la diferencia y el balance
	$sql_creditos_asignados = "select sum(st.creditos_asignados) as sum, g.creditos
	from t_solicitudes  s
	inner join t_solicitudes_tutelas st on s.id = st.parent_id
	inner join t_tutelas g on g.id = st.tutelas
	where s.validada = 1 and st.tutelas = ?";
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


  	//actualiza la tabla de tutelas
	$update_query = "update t_tutelas
	set creditos_asignados = ?,
	diferencia = ?,
	balance = ?
	where id = ?";
	$stmt_update_grupos = $con->prepare($update_query);
	$stmt_update_grupos->bind_param('ddsi',$creditos_asignados,$diferencia,$balance,$grupo);
	$stmt_update_grupos->execute();
  	//print_r($stmt_update_usuarios);
	if ($stmt_update_grupos->affected_rows) {
	    echo "<tr class='fabrik_row'><td>Tutela ".$grupo." actualizada.</td></tr>";	
	}

	$stmt_update_grupos->close();

} 


$stmt_grupos->close();

?>
<tr class="fabrik_row actualiza-tutelas"><td><b>Tutelas actualizadas</b></td></tr>