<?php
	session_start();
	include ("conexao.php");
	$login = isset($_POST['usuario']) ? $_POST['usuario'] : '';
	$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	$select = $conn->query("select id, usuario from login where usuario = '$login' and senha= md5('$senha')");
	$select = $select->fetch(PDO::FETCH_ASSOC);

	if($action == 'entrar'){
		$select = $conn->query("select id, usuario from login where usuario = '$login' and senha= md5('$senha')");
		$select = $select->fetch(PDO::FETCH_ASSOC);
		print_r($select);
		if($select == true){
			$_SESSION['nao_autenticado'] = false;
			$_SESSION['usuario'] = $login;
			header('location: inicio.php');
		}
		else{
			$_SESSION['nao_autenticado'] = true;
			header('location: index.php');
			exit;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
	<form class="box" action="index.php?action=entrar" method="post">
		<h1>Login</h1>
		<?php
			if (isset($_SESSION['nao_autenticado'])):
		?>
		<div style="background: #263238; border-radius: 5px;">
			<p style="color: #757575">Erro: Usuário ou senha inválidos.</p>
		</div>
		<?php
			endif;
			unset($_SESSION['nao_autenticado']);
		?>
		<input type="text" name="usuario" placeholder="Usuario/Nome" required="">
		<input type="password" name="senha" placeholder="Senha" required="">
		<table>
			<tr>
				<td>
					<input type="submit" name="" value="Entrar">
				</td>
				<td style="padding: 30px">
					<input type="button" onclick="window.open('cadlogin.php','_self')" value="Novo Login">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>