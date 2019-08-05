<?php
include ("conexao.php");
/*
	Select pra tazer a data
	Separa data da hora, e atribuir hora nova
	update
	imprimir resposta do ajax nova hora
*/
$cliente = isset($_POST["idCliente"]) ? intval($_POST["idCliente"]) : '';
$novaHora = isset($_POST["horaNova"]) ? $_POST["horaNova"] : '';
$date = $conn->query("select Data from agenda where Cliente_IDC = $cliente");
$data = $date->fetch(PDO::FETCH_ASSOC);

$data = date('Y-m-d', strtotime($data['Data']));
$newDate = $data. ' ' . $novaHora;

$update = "update agenda set Data = ? where Cliente_IDC = $cliente";
$stmt = $conn->prepare($update);
$stmt->execute(array($newDate));

$newDate = date('d-m-y H:i', strtotime($newDate));
echo json_encode($newDate);
?>