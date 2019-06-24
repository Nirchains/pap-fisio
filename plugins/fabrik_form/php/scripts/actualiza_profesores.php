<?php
echo "<tr class='fabrik_row actualiza-profesores'><td><b>Actualizando profesores</b></td></tr>";


$sql_profesores = "select id, userid, nombre, usuario, prelacion, encargo, tfg, tfm, practicas, cont_idi,
practicum, capacidad, asignacion, diferencia, balance
from t_usuarios";
$stmt_profesores = $con->prepare($sql_profesores);
$stmt_profesores->execute();

$result_profesores = $stmt_profesores->get_result();

while ($row = $result_profesores->fetch_assoc()) {
	$userid = $row["id"];
	$nombre = $row["nombre"];
	$encargo = (float)$row["encargo"];
	$tfg = (float)$row["tfg"];
	$tfm = (float)$row["tfm"];
	$practicas = (float)$row["practicas"];
	$cont_idi = (float)$row["cont_idi"];
	$practicum = (float)$row["practicum"];

  	//recalculamos la capacidad
	$capacidad = $encargo -$tfg - $tfm - $practicas - $cont_idi - $practicum;

  	//Calculamos la asignacion
	$sql_asignacion = "select sum(s.sum) as sum from (
						select (sum(sg.creditos_asignados)) as sum, 'sg' as grupo
							from t_solicitudes  s
							inner join t_solicitudes_grupos sg on s.id = sg.parent_id 
							where
							s.validada = 1
							and s.usuario = ?
						union select (sum(ss.creditos_asignados)) as sum, 'ss' as grupo
							from t_solicitudes  s
							inner join t_solicitudes_seminarios ss on s.id = ss.parent_id
							where
							s.validada = 1
							and s.usuario = ?
						union select (sum(st.creditos_asignados)) as sum, 'st' as grupo
							from t_solicitudes  s
							inner join t_solicitudes_tutelas st on s.id = st.parent_id
							where
							s.validada = 1
							and s.usuario = ?
						) s";

	$stmt_asignacion = $con->prepare($sql_asignacion);
	$stmt_asignacion->bind_param('iii',$userid,$userid,$userid);
	$stmt_asignacion->execute();

	$result_asignacion = $stmt_asignacion->get_result();

	if ($result_asignacion->num_rows > 0) {
		$row = $result_asignacion->fetch_assoc();
    //multiplicamos por 10 para obtener el número de horas
		$asignacion =  round((float)($row["sum"]*10), 2);
	} else {
		$asignacion = 0;
	}
	$stmt_asignacion->close();

  	//recalculamos la diferencia
	$diferencia = round((float)($capacidad - $asignacion), 2) ;

  	//recalculamos el balance
	if ($diferencia>0) {
		$balance = "positivo";
	} else if ($diferencia<0) {
		$balance = "negativo";
	} else {
		$balance = "cero";
	} 

  	//Actualizamos la tabla de usuarios
	$query_update_usuarios = "UPDATE t_usuarios 
	SET capacidad = ?,
	asignacion = ?,
	diferencia = ?,
	balance = ?
	WHERE id = ?";

	$stmt_update_usuarios = $con->prepare($query_update_usuarios);
	$stmt_update_usuarios->bind_param('dddsi',$capacidad,$asignacion,$diferencia,$balance,$userid);
	$stmt_update_usuarios->execute();
	
  	//print_r($stmt_update_usuarios);
	if ($stmt_update_usuarios->affected_rows) {
		echo "<tr class='fabrik_row'><td>Profesor <a href='profesores/details/5/".$userid."'> ".$nombre."</a> actualizado. </td></tr>";	
	}

	$stmt_update_usuarios->close(); 
}
$stmt_profesores->close();
echo "<tr class='fabrik_row actualiza-profesores'><td><b>Profesores actualizados</b></td></tr>"
?>
