<?php
	session_start();
	include ('conexao.php');
	$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
	$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
	$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	if ($action == 'cadastrar'){
		
		$select = $conn->query("select usuario from login where usuario = '$usuario' ");
		$select = $select->fetch(PDO::FETCH_ASSOC);

		if($select == true){
			$_SESSION['existe'] = false;
			header('location: cadlogin.php');
			exit;
		}
		else{
			$_SESSION['existe'] = true;
			try {
			$sql = "insert into login ( usuario,  nome, senha, data_cad) values ( ?,?,?,now())";
			$stmt = $conn->prepare($sql);
			 $stmt->execute(array(
			    $usuario, $nome, md5($senha)
			  ));

			 } catch(PDOException $e) {
					echo 'Error: ' . $e->getMessage();
				}
		}
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Novo Login</title>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
	<form class="box" action="cadlogin.php?action=cadastrar" method="post">
		<h1>Login</h1>
		<?php
			if(isset($_SESSION['existe'])):
		?>
		<div style="background: #263238; border-radius: 5px;">
			<p style="color: #757575">Usuario ja existente!</p>
		</div>
		<?php
			endif;
			unset($_SESSION['existe']);
		?>
		<input type="text" name="nome" placeholder="Nome" required="">
		<input type="text" name="usuario" placeholder="Usuario" required="">
		<input type="password" name="senha" placeholder="Senha" required="">
		<input type="submit" name="" value="Cadastrar">
	</form>
</body>
</html>