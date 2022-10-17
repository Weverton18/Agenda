<?php
	session_start();
	include ("conexao.php");

	if($_SESSION['usuario'] == ''){
		header('location: index.php');
		exit;
	}
	
	$usuario = $_SESSION['usuario'];
	$IDC = isset($_POST["IDC"]) ? $_POST["IDC"] : '';
	$nome = isset($_POST["Nome"]) ? $_POST["Nome"] : '';
	$apelido = isset($_POST["Apelido"]) ? $_POST["Apelido"] : '';
	$Tel = isset($_POST["Tel"]) ? $_POST["Tel"] : '';
	$Action = isset($_GET['action']) ? $_GET['action'] : '';
	if($Action == 'salvar')
	{	
		try {
			$sql = "INSERT INTO cliente (login, Nome, Apelido, Telefone) values ( ?,?,?,? )";
			$stmt = $conn->prepare($sql);
			$stmt->execute(array($usuario, $nome, $apelido, $Tel));
			header('location: BetaCliente1.php');
			exit;
		} catch(PDOException $e) {
			echo 'Error: ' . $e->getMessage();
		}
	}
	else
	if ($Action == 'editar')
	{
		$IDC = isset($_GET["IDC"]) ? $_GET["IDC"] : '';
		$date = $conn->query("select IDC, Nome, Apelido, Telefone from cliente where IDC = $IDC");
		foreach ($date as $cliente) {
			$nome = $cliente["Nome"];
			$apelido = $cliente["Apelido"];
			$Tel = $cliente["Telefone"];
		}
	}
	else if($Action == "EditSalvar")
	{
		$update = "update cliente set Nome = ? , Apelido = ?, Telefone = ? where IDC = $IDC";
		$stmt = $conn->prepare($update);
		$stmt->execute(array( $nome, $apelido, $Tel ));
		header('location: BetaCliente1.php');
		exit;
	}
	else if ($Action == "deletar")
	{
		$IDC = isset($_GET["IDC"]) ? $_GET["IDC"] : '';
		$delete = "delete from cliente where IDC = $IDC";
		$stmt = $conn->prepare($delete);
		$stmt->execute();
		header('location: BetaCliente1.php');
		exit;
	}
?>
<!DOCTYPE>
<html>
<head>
	<title>Cadastro Cliente</title>
	<meta name="BetaCliente01" charset="UTF8">
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  	<link rel="stylesheet"  href="css/Menu.css">
  	<link rel="stylesheet" href="css/padrao.css">
  	<link rel="stylesheet" media="(max-width: 1500px)" href="css/1350.css">
  	<link rel="stylesheet" media="(max-width: 1024px)" href="css/1024.css">
  	<link rel="stylesheet" media="(max-width: 780px)" href="css/780.css">
  	<link rel="stylesheet" media="(max-width: 480px)" href="css/480.css">
	<script>
		$(document).ready(function(){

		  $("#Tabela2 tbody tr").hover(function(){
		    $(this).css({"background-color":"Black"})

		  },
		  function()
		  {
		  	$(this).css({"background-color":"#263238"})
		  }
		  );

		  $("#Tabela2 tbody font").hover(function(){
				$(this).css({"color":"#0277bd"})
			
			},
			function()
			{
				$(this).css({"color":"#A8A8A8"})
			}
			);

		  $("#botão input").hover(function(){

				$(this).css({"background-color":"#01579b","border-color":"#0277bd","color":"#e1f5fe"})
			},

			function()
			{
				$(this).css({"background-color":"","border-color":"","color":""})
			}
			);

		  $('#btn-menu').click(function(){
				$('#ul-menu').toggleClass('active')
			})

		});
	</script>
	<script type="text/javascript">
		function confirmar(teste)
		{
			var resposta = confirm("Todos os horarios agendados com esse cliente sera apagado, deseja continuar?!")
			if(resposta == true)
			{
				window.open(teste , "_self")
			}
		}
	</script>
</head>
<body class="pag" background="Fundo2.jpg">
	<div class="div1">
		<button id="btn-menu"> <i class="fa fa-bars fa-lg"></i></button>
			<ul id="ul-menu">
				<li><a href="inicio.php">Agenda</a></li>
				<li><a href="BetaCorte1.php">Corte</a></li>
				<li><a href="logout.php">Sair</a></li>
			</ul>
	</div>
	<?php
		if ($Action=="editar" or $Action=="EditSalvar")
		{
			?> 
			<form  name="Formulario" action="BetaCliente1.php?action=EditSalvar" method="post">
			<?php
				
		}
		else
		{
			?>
			<form  name="Formulario" action="BetaCliente1.php?action=salvar" method="post">
			<?php
		}
	?>
		<table align="center" class="Tabela1">
			<thead>
			<tr>
				<td colspan="5" align="center">
					<Font face="arial" class="titulo">Cadastrar Cliente</Font>
				</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="3" id="campo1">
					<font face="Arial" color="white" class="font">Nome:</font>
					<input type="hidden" id="IDC" name="IDC" value="<?php echo $IDC ?>">
					<Input Type="text" id="Nome" Name="Noalme" vue="<?php echo $nome ?>" size="50" maxlength="55" required="" class="input2"/>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font face="Arial" color="white" class="font">Apelido:</font>
					<Input type="text" id="Apelido" Name="Apelido" value="<?php echo $apelido ?>" maxlength="55" size="50" required="" class="input2"/>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font face="Arial" color="white" class="font">Tel:</font>
					<Input type="text" id="Tel" Name="Tel" size="50" value="<?php echo $Tel ?>" maxlength="10" placeholder="*****-***" required="" class="input2"/>
				</td>
			</tr>
			</tbody>
			<tfoot>
			<tr id="botão">
				<td align="center"> 
					<?php
						if ($IDC !== "")
						{
							?>
								<Input type="Submit" id="Editar" value="Editar Cadastro" class="input1">
							<?php		
						}
						else 
						{
							?>
								<Input type="Submit" id="salvar" value="Salvar Cadastro" class="input1">
							<?php
						}
					?>
				</td>
			</tr>
			</tfoot>
		</table>
	</form>
	<div id="div5">
		<table border id="Tabela2" style="border: 3px solid #37474f;" align="center">
			<thead>
				<tr style="height: 40px">
					<td colspan="6" align="center">
						<font face="Arial" class="titulo">Cliente</font>
					</td>
				</tr>
				<tr style="height: 40px">
					<td align="center" width="25%">
						<font face="Arial"  color="white" class="font">Nome</font>
					</td>
					<td align="center" width="25%">
						<font face="Arial"  color="white" class="font">Apelido</font>
					</td>
					<td align="center" width="25%">
						<font face="Arial"  color="white" class="font">Tel</font>
					</td>
					
					<td align="center" width="25%">
						<font face="Arial"  color="white" class="font">Excluir</font>	
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php
					$data = $conn -> query("select IDC, Nome, Apelido,Telefone from cliente where login = '$usuario' order by nome;");
					foreach ($data as $Abrir) {
						?>
				<tr>
					<td align="center">
						<font face="Arial" class="font">
							<div class="tooltip">
								<a href="BetaCliente1.php?action=editar&IDC=<?php echo $Abrir ['IDC'] ?>" style="color: #0277bd; text-decoration: none;">
								<?php echo $Abrir ['Nome'] ?>	
								</a>
								<span class="tooltiptext">Clique Para Atualizar</span>
							</div>
						</font>
					</td>
					<td align="center">
						<font face="Arial" class="font" color="#A8A8A8"><?php echo $Abrir ['Apelido'] ?></font>
					</td>
					<td align="center">
						<font face="Arial" class="font" color="#A8A8A8"><?php $tel= $Abrir['Telefone'];
						echo "(31) $tel" ?></font>
					</td>
					<td align="center">
						<a href="#" onclick="confirmar('BetaCliente1.php?action=deletar&IDC=<?php echo $Abrir ['IDC'] ?>')">
							<img src="excluir1.png" class="excluir" width="8%">
						</a>
					</td>
				</tr>
					<?php
						}
					?>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>