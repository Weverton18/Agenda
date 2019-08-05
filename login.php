<?php
	include ("conexao.php");

	$login = isset($_POST['usuario']) ? $_POST['usuario'] : '';
	$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	if(($login == '') or ($senha == '')){
		header('location: inicio.php');
		exit;
	}

	if($action == 'entar'){
		$select = $conn->query("select id_usuario, usuario from login where usuario = '$login' and senha= md5('$senha')");
		$select = $select->fetch(PDO::FETCH_ASSOC);
		print_r($select);
	}
?>