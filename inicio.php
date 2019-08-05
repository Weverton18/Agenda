<?php
	session_start();
	include ("conexao.php");

	if($_SESSION['usuario'] == ''){
		header('location: index.php');
	}

	$usuario = $_SESSION['usuario'];
	$IDA = isset($_POST['IDA']) ? $_POST['IDA'] : '';
	$IDC = isset($_GET['IDC']) ? $_GET['IDC'] : '';
	$IC = isset($_GET['IC']) ? $_GET['IC'] : '';
	$Cliente = isset($_POST["Cliente"]) ? $_POST["Cliente"] : '';
	$Data = isset($_POST["Data"]) ? $_POST["Data"] : '';
	$Hora = isset($_POST["Hora"]) ? $_POST["Hora"] : '';
	$Data = str_replace("/", "-", $Data);
	$Data = date('Y-m-d', strtotime($Data));
	$DataHora = $Data. ' ' . $Hora;
	$Corte = isset($_POST["Corte"]) ? $_POST["Corte"] : '';
	$Action = isset($_GET['action']) ? $_GET['action'] : '';

	//SALVADNO HORARIO AGENDADO

	if($Action == 'salvar')
	{
		try {
		$sql = "insert into agenda ( Cliente_IDC,  Data, Corte_IC, usuario) values ( ?,?,?,?)";
		$stmt = $conn->prepare($sql);
		 $stmt->execute(array(-
		    $Cliente, $DataHora, $Corte, $usuario
		  ));
		header('location: inicio.php');

		 } catch(PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
	}

	//EDITANDO HORARIO AGENDADO

	else if($Action == 'editar')
	{
		$IDA = isset($_GET['IDA']) ? $_GET['IDA'] : '';
		$date ="select * from agenda inner join cliente on agenda.Cliente_IDC = cliente.IDC ";
		$date .= "inner join corte on agenda.Corte_IC = corte.IC ";
		$date .= "where IDA = $IDA";
		$row = $conn->query($date);
		foreach ($row as $agenda)
		{
			$Cliente = $agenda["Apelido"];
			$Corte = $agenda["Nome_Corte"];
			$data_result = $agenda["Data"];
    		$data_result = date('d/m/Y H:i',strtotime($data_result));
    		$arr = explode(' ',$data_result);
    		$Data = $arr[0];
    		$Hora = $arr[1];
		}

	}
	else if($Action == 'Editsave')
	{
		$update = "update agenda set Cliente_IDC = ? , Data = ?, Corte_IC = ? where IDA = $IDA";
		$stmt = $conn->prepare($update);
		$stmt->execute(array($Cliente, $DataHora, $Corte));
		header('location: inicio.php');
	}

	//DELETANDO HORARIO SALVO

	else if($Action == 'deletar')
	{
		$IDA = isset($_GET['IDA']) ? $_GET['IDA'] : '';
		$delete = "delete from agenda where IDA = $IDA";
		$stmt = $conn->prepare($delete);
		$stmt->execute();
		header('location: inicio.php');
	}
	else if($Action == 'truncate'){
		$truncate = "truncate agenda;";
		$stmt = $conn->prepare($truncate);
		$stmt->execute();
		header('location: inicio.php');
	}
	
?>
<!DOCTYPE>
<html>
<head>
	<title>Agenda</title>
	<meta name="Beta01" charset="UTF8">
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" type="text/css" href="css/Menu.css">
  	<link rel="stylesheet" href="css/padrao.css">
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
 	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
  	<link rel="stylesheet" media="(max-width: 1500px)" href="css/1350.css">
  	<link rel="stylesheet" media="(max-width: 1024px)" href="css/1024.css">
  	<link rel="stylesheet" media="(max-width: 780px)" href="css/780.css">
  	<link rel="stylesheet" media="(max-width: 480px)" href="css/480.css">
  	<script>
  		$( function() {
    	$( "#datepicker" ).datepicker({dateFormat: 'dd/mm/yy',
			language: 'pt-BR',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
			dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
		});
  		} );
  		$( function() {
    	$( "#calendario" ).datepicker({dateFormat: 'dd/mm/yy',
			language: 'pt-BR',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
			dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
		});
  		} );

  		$(document).ready(function(){
	    	$("#timepicker").timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 15,
		    minTime: '0',
		    maxTime: '23:45 pm',
		    defaultTime: '0',
		    startTime: '00:00',
		    dropdown: true,
	    	});

	    	 $("#Tabela2 tbody tr").hover(function(){
		    $(this).css({"background-color":"black"})

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

			$("#Tabela1 tbody input").hover(function(){

				$(this).css({"background-color":"#01579b","border-color":"#0277bd","color":"#e1f5fe"})
			},

			function()
			{
				$(this).css({"background-color":"white","border-color":"white","color":"Black"})
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

		var idHoraAuto = "#";
			$(".horaAuto").on("click",function(){
				idHoraAuto += $(this).attr("id");
				console.log(idHoraAuto);
			});

		function mostrarCampo(el)
		{
			var idBtn = $(el).attr("id");
			var idNumero = idBtn.substring(3);
			$(el).after(" <div id="+idNumero+"><input placeholder='Hora 00:00' type='text' id='salvar"+ idNumero +"' style='margin-top: 8px; padding-left: 5px;' size='7'><input type='submit' onclick='updateHora("+ idNumero +",event,this)' value='salvar'></div>");
		}

		function horaAuto(idCampo)
		{
		    	$("#salvar"+idCampo).timepicker({
		    	timeFormat: 'HH:mm',
		    	interval: 15,
			    minTime: '0',
			    maxTime: '23:45 pm',
			    defaultTime: '0',
			    startTime: '00:00',
			    dropdown: true,
	    		});
			
		}

		function updateHora(idCliente,e,el)
		{
			e.preventDefault();
			var horaNova = $("#salvar"+ idCliente).val();

			$.ajax({
		          url : "ajax.php",
		          type : 'post',
		          data : {
		               idCliente : idCliente,
		               horaNova : horaNova
		          },
		          beforeSend : function(){
		               $("#salvar").html("ENVIANDO...");
		          }
			     })
			     .done(function(msg){

			    	window.location.reload();
		     	 })
			     .fail(function(jqXHR, textStatus, msg){
				             alert(msg);
			});

		}
  	</script>
	<script type="text/javascript">
		function confirmar (teste)
		{
			var resposta = confirm("Esse Horario sera excluido, deseja continuar?!")
			if(resposta == true)
			{
				window.open(teste, "_self")
			}
		}
	</script>
</head>
<body background="Fundo2.jpg">
	<div class="div1">
		<button id="btn-menu"> <i class="fa fa-bars fa-lg"></i></button>
			<ul id="ul-menu">
				<li><a href="BetaCliente1.php">Cliente</a></li>
				<li><a href="BetaCorte1.php">Corte</a></li>
				<li><a href="logout.php">Sair</a></li>
			</ul>
	</div>
	<?php
		if($Action == "editar" or $Action == "Editsave")
		{
			?>
			<form name="Formulario" action="inicio.php?action=Editsave" method="post">
			<?php	
		}
		else
		{
			$Data = date("d/m/Y");
			?>
			<form name="Formulario" action="inicio.php?action=salvar" method="post">
			<?php
		}
	?>
		<table class="Tabela1" align="center">
			<thead>
				<tr>
					<td align="center" colspan="2">
						<font face="Arial" class="titulo">Agendar Horario</font>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<font face="Arial" color="white" class="font">Cliente:</font>
						<input type="hidden" id="IDA" name="IDA" value="<?php echo $IDA ?>">
						<select id="Cliente" name="Cliente" style="cursor: pointer;" required="" class="select">
							<option  value=""></option>
							<?php
							$data = $conn->query("select * from cliente where login = '$usuario' order by Apelido");
							foreach($data as $row) {
							?>
							<option  value="<?php echo $row["IDC"] ?>"
							<?php 
								if ($IDC == $row["IDC"])
							 		echo "selected";
							 	?>>
							<?php echo $row["Apelido"] ?>
							</option>
							<?php 
								} 
							?>
						</select>
					</td>
					<td>
						<font face="Arial" color="white" class="font">Corte:</font>
						<select id="Corte" name="Corte" style="cursor: pointer;" required="" class="select">
							<option value="<?php echo $Cliente ?>"></option>
							<?php echo $Corte ?>
							<?php
							$data = $conn->query("select * from corte where user = '$usuario' order by Nome_Corte");
							foreach($data as $row) {
							?>
							<option  value="<?php echo $row["IC"] ?>"
							<?php
								if($IC == $row["IC"])
									echo "selected";
							?>>
							<?php echo $row["Nome_Corte"] ?>
							</option>
							<?php 
							} 
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<font face="Arial" color="white" class="font">Data:</font>
						<input type="text" id="datepicker" Name="Data" value="<?php echo $Data?>" class="DH">
					</td>
					<td>
						<font face="Arial" color="white" class="font">Hora:</font>
						<input type="text" id="timepicker" Name="Hora" value="<?php echo $Hora?>" class="DH">
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td id="botão" align="center" colspan="2"> 
						<?php
							if ($IDA == "")
							{
								?>
									<Input type="Submit" id="salvar" value="Salvar Horario" class="input1">
								<?php
							}
							else if ($IDA !== "")
							{
								?>
									<Input type="Submit" id="Editar" value="Editar Horario" class="input1">	
								<?php
							}
						?>
					</td>
				</tr>
			</tfoot>
		</table>
		<br>
	</form>
	</div>
	<form  name="FormAgenda" method="post" action="inicio.php?action=truncate">
		<div id="div5" align="center">
			<table border id="Tabela2" style="border: 3px solid #37474f;" align="center">
				<thead>
					<tr style="height: 40px">
						<td align="center" colspan="5">
							<font face="Arial" class="titulo">Agenda</font>
						</td>
					</tr>
					<tr style="height: 40px">
						<td align="center" width="30%">
							<font face="Arial" color="white"class="font">Cliente</font>
						</td>
						<td align="center" width="30%">
							<font face="Arial" color="white" class="font">Data</font>
						</td>
						<td align="center" width="30%">
							<font face="Arial" color="white" class="font">Corte</font>	
						</td>
						<td align="center" width="30%">
							<font face="Arial" color="white" class="font">Valor</font>	
						</td>
						<td align="center" width="30%">
							<font face="Arial" color="white" class="font">Excluir</font>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
							$data = "select * from agenda inner join cliente on agenda.Cliente_IDC = cliente.IDC";
							$data .= " inner join corte on agenda.Corte_IC = corte.IC";
							$data .=" where usuario = '$usuario' order by Data;";
							$row = $conn->query($data);
							foreach ($row as $Abrir) {
								
						?>
					<tr>
						<td align="center">
							<font face="Arial" class="font">
							<div class="tooltip">
								<a href="inicio.php?action=editar&IDA=<?php echo $Abrir ['IDA'] ?>
									&IDC=<?php echo $Abrir ['IDC'] ?>
									&IC=<?php echo $Abrir ['IC'] ?>"
									id="link"
									style="color: #0277bd; text-decoration: none;">
										<?php echo $Abrir ['Apelido'] ?>
								</a>
								<span class="tooltiptext">Clique Para Atualizar</span>
							</div>
							</font>
						</td>
						<td id="teste<?php echo $Abrir['Cliente_IDC']; ?>" align="center">
							<font face="Arial" class="font" color="#A8A8A8">
								<?php
									$date = date('d-m-y H:i', strtotime($Abrir['Data']));
									echo $date;
								?>
							</font>
								<input type="button" id="btn<?php echo $Abrir['Cliente_IDC']; ?>" nome="BOTÃO" value="" class="BOTAO" onclick=" mostrarCampo(this); horaAuto(<?php echo $Abrir['Cliente_IDC']; ?>);">
						</td>
						<td align="center">
							<font face="Arial" class="font" color="#A8A8A8"><?php echo $Abrir ['Nome_Corte'] ?></font>
						</td>
						<td align="center">
							<font face="Arial" class="font" color="#A8A8A8"><?php $valor = number_format($Abrir['Valor_Corte'], 2, ',', '.');
							echo "R$ $valor"; ?></font>
						</td>
						<td align="center">
							<a href="#" onclick="confirmar('inicio.php?action=deletar&IDA=<?php echo $Abrir ['IDA'] ?>')">
								<img src="excluir1.png" class="excluir" width="8%">
							</a>	
						</td>
					</tr>
						<?php
							}
						?>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" align="center">
							<div class="tooltip">
								<Input type="Submit" id="del" value="Apagar Tudo" style="cursor: pointer; background-color:red; border-color: red; color: white">
								<span class="tooltiptext2">Todos os Horarios serão excluidos</span>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>
</body> 
</html>