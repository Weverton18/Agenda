<?php
	session_start();
	include("conexao.php");

	if($_SESSION['usuario'] == ''){
		header('location: index.php');
	}

	$usuario = $_SESSION['usuario'];	
	$IC = isset($_POST["IC"]) ? $_POST["IC"] : '';
	$Corte = isset($_POST["Corte"]) ? $_POST["Corte"] : '';
	$Valor = isset($_POST["Valor"]) ? $_POST["Valor"] : '';
	$Tempo = isset($_POST["Tempo"]) ? $_POST["Tempo"] : '';
	$Action = isset($_GET['action']) ? $_GET['action'] : '';
	if($Action == 'salvar'){
	try{
		$sql = "insert into corte (user, Nome_Corte, Valor_Corte, Tempo_Estimado) values(?,?,?,?)";
		$stmt = $conn->prepare($sql);
		 	$stmt->execute(array(
		    	$usuario, $Corte, $Valor, $Tempo
		  	));
		header('location: BetaCorte1.php');
		}
		catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
	}
	if ($Action == 'editar')
	{
		$IC = isset($_GET["IC"]) ? $_GET["IC"] : '';
		$date = $conn->query("select IC, Nome_Corte, Valor_Corte, Tempo_Estimado from corte where IC = $IC");
		foreach ($date as $pass) {
			$Corte = $pass["Nome_Corte"];
			$Valor = $pass["Valor_Corte"];
			$Tempo = $pass["Tempo_Estimado"];
		}
	}
	else if($Action == "EditSalvar")
	{
		try{
		$sql = "update corte set Nome_Corte = ? , Valor_Corte = ?, Tempo_Estimado = ? where IC = $IC";
		$stmt = $conn->prepare($sql);
		 	$stmt->execute(array(
		    	$Corte, $Valor, $Tempo
		  	));
		header('location: BetaCorte1.php');
		}
		catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
	}
	else if($Action == "deletar")
	{
		$IC = isset($_GET["IC"]) ? $_GET["IC"] : '';
		$date = $conn->query("Select count(*) from agenda where Corte_IC = $IC");
		$delete = "delete from corte where IC = $IC";
		$stmt = $conn->prepare($delete);
		$stmt->execute();
		header('location: BetaCorte1.php');
	}	
?>
<!DOCTYPE>
<html>
<head>
	<title>Cadastro Corte</title>
	<meta name="BetaCliente01" charset="UTF8">
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  	<link rel="stylesheet" type="text/css" href="css/Menu.css">
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
			var resposta = confirm("Todos os horarios agendados com esse corte sera apagado, deseja continuar?!")
			if(resposta == true)
			{
				window.open(teste , "_self")
			}
		}
		
	</script>
</head>
<body background="Fundo2.jpg">
	<div class="div1">
		<button id="btn-menu"> <i class="fa fa-bars fa-lg"></i></button>
			<ul id="ul-menu">
				<li><a href="inicio.php">Agenda</a></li>
				<li><a href="BetaCliente1.php">Cliente</a></li>
				<li><a href="logout.php">Sair</a></li>
			</ul>
	</div>
	<?php
		if ($Action=="editar" or $Action=="EditSalvar")
		{
			?> 
			<form  name="Formulario" action="BetaCorte1.php?action=EditSalvar" method="post">
			<?php
				
		}
		else
		{
			?>
			<form  name="Formulario" action="BetaCorte1.php?action=salvar" method="post">
			<?php
		}
	?>	
		<table class="Tabela1" align="center">
			<tr>
				<td colspan="4" align="center">
					<font face="arial" class="titulo">Cadastrar Corte
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" id="IC" name="IC" value="<?php echo $IC ?>">
					<font face="Arial" color="white" class="font">Nome Corte:</font>
					<input type="text" id="Corte" name="Corte" value="<?php echo $Corte ?>" size="40" required="" class="input2"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<font face="Arial" color="white" class="font">Valor Corte:</font>
					<input type="text" id="Valor" name="Valor" value="<?php echo $Valor ?>" required="" placeholder='R$' class="input2"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<font face="Arial" color="white" class="font">Tempo De Corte:</font>
					<Input type="text" id="Tempo" Name="Tempo" value="<?php echo $Tempo ?>" size="15" required="" placeholder='00:00' class="input2"/>
				</td>
			</tr>
			<tr id="botão">
				<td align="center">
					<?php
						if ($IC !== "")
						{
							?>
								<Input type="Submit" id="Editar" value="Editar Corte" class="input1">
							<?php		
						}
						else 
						{
							?>
								<Input type="Submit" id="salvar" value="Salvar Corte" class="input1">
							<?php
						}
					?>
				</td>
			</tr>
		</table>
		</form>
	<div id="div5">
		<table border id="Tabela2" style="border: 3px solid #37474f;" align="center">
			<thead>
				<tr style="height: 40px">
					<td colspan="5" align="center">
						<font face="Arial" class="titulo">Cortes</font>
					</td>
				</tr>
				<tr style="height: 40px">
					<td align="center" width="25%">
						<font face="Arial" class="font" color="white">Nome</font>
					</td>
					<td align="center" width="25%">
						<font face="Arial" class="font" color="white">Valor</font>
					</td>
					<td align="center" width="25%">
						<font face="Arial" class="font" color="white">Tempo Corte</font>
					</td>
					<td align="center" width="25%">
						<font face="Arial" class="font" color="white">Excluir</font>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php
						$data = $conn->query("select IC, Nome_Corte, Valor_Corte, Tempo_Estimado from corte where user = '$usuario' order by Nome_Corte;");
						foreach ($data as $Abrir) {
					?>
				<tr>
					<td align="center">
						<font face="Arial" class="font">
							<div class="tooltip">
								<a href="BetaCorte1.php?action=editar&IC=<?php echo $Abrir ['IC'] ?>" style="color: #0277bd; text-decoration: none;">
									<?php echo $Abrir ['Nome_Corte'] ?>	
								</a>
								<span class="tooltiptext">Clique Para Atualizar</span>
							</div>
						</font>
					</td>
					<td align="center">
						<font face="Arial" class="font" color="#A8A8A8">
							<?php
								$valor = number_format($Abrir['Valor_Corte'], 2, ',', '.');
								echo "R$ $valor";
							?>
						</font>
					</td>
					<td align="center">
						<font face="Arial" class="font" color="#A8A8A8">
							<?php 
								$date = date('H:i', strtotime($Abrir['Tempo_Estimado']));
								echo $date;
							?>
						</font>
					</td>
					<td align="center">
						<a href="#" onclick="confirmar('BetaCorte1.php?action=deletar&IC=<?php echo $Abrir ['IC'] ?>')">
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