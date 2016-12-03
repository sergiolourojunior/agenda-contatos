<!DOCTYPE html>
<html>
<head>
	<title>Agenda de contatos</title>
	<meta charset="utf-8">

	<?php

	include 'conexao.php';

	if(isset($_POST['acao']) && $_POST['acao'] != '')
	{
		$valido = true;

		switch($_POST['acao'])
		{
			case 'cadastrar':
				$nome = htmlspecialchars(trim($_POST['nome']));
					if($nome == '')
					{
						$valido = false;
					}
				$sobrenome = htmlspecialchars(trim($_POST['sobrenome']));
					if($sobrenome == '')
					{
						$valido = false;
					}
				$endereco = htmlspecialchars(trim($_POST['endereco']));
					if($endereco == '')
					{
						$valido = false;
					}
				$cep = $_POST['cep'];
					if($cep == '' || strlen($cep) < 8 || !is_numeric($cep))
					{
						$valido = false;
					}
				$bairro = htmlspecialchars(trim($_POST['bairro']));
					if($bairro == '')
					{
						$valido = false;
					}
				$cidade = htmlspecialchars(trim($_POST['cidade']));
					if($cidade == '')
					{
						$valido = false;
					}
				$empresa = $_POST['empresa'];
					if(!is_numeric($_POST['empresa']))
					{
						$valido = false;
					}

				foreach($_POST['email'] as $email)
				{
					if($email == '' || !ereg("^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+.([a-zA-Z]{2,4})$", $email))
					{
						$valido = false;
					}
				}

				$tipos_tel_aceitos = array('Residencial', 'Celular', 'Trabalho');

				foreach($_POST['telefone'] as $key=>$telefone)
				{
					$tipo = $_POST['tipo_telefone'][$key];

					if($telefone == '' || !is_numeric($telefone) || strlen($telefone) < 10 || !in_array($tipo, $tipos_tel_aceitos))
					{
						unset($_POST['telefone'][$key]);
						unset($_POST['tipo_telefone'][$key]);
					}
				}

				if($valido)
				{
					$data_atual = date("Y-m-d H:i:s");

					$PDO->beginTransaction();

					try
					{
						$PDO->exec("INSERT INTO contatos (nome, sobrenome, endereco, cep, bairro, cidade, dt_criacao, dt_alteracao) VALUES ('$nome', '$sobrenome', '$endereco', '$cep', '$bairro', '$cidade', '$data_atual', '$data_atual')");

						$codigo_contato = $PDO->lastInsertId();

						foreach($_POST['email'] as $email)
						{
							$PDO->exec("INSERT INTO emails (email, contato_id) VALUES ('$email', $codigo_contato)");
						}

						foreach($_POST['telefone'] as $key=>$telefone)
						{
							$tipo = $_POST['tipo_telefone'][$key];

							$PDO->exec("INSERT INTO telefones (numero, tipo, contato_id) VALUES ('$telefone', '$tipo', $codigo_contato)");
						}

						if($empresa != 0)
						{
							$PDO->exec("INSERT INTO contato_empresa VALUES ($codigo_contato, $empresa)");
						}

						$PDO->commit();

						echo 'Cadastro efetuado com sucesso.';
					}
					catch (Exception $e)
					{
						$PDO->rollback();

						echo 'Erro: '.$e->getMessage();
					}				
				}
				else
				{
					echo 'Preencha os dados corretamente.';
				}
			break;

			case 'atualizar':
				$codigo = $_POST['codigo'];
					if(!is_numeric($codigo))
					{
						$valido = false;
					}
				$nome = htmlspecialchars(trim($_POST['nome']));
					if($nome == '')
					{
						$valido = false;
					}
				$sobrenome = htmlspecialchars(trim($_POST['sobrenome']));
					if($sobrenome == '')
					{
						$valido = false;
					}
				$endereco = htmlspecialchars(trim($_POST['endereco']));
					if($endereco == '')
					{
						$valido = false;
					}
				$cep = $_POST['cep'];
					if($cep == '' || strlen($cep) < 8 || !is_numeric($cep))
					{
						$valido = false;
					}
				$bairro = htmlspecialchars(trim($_POST['bairro']));
					if($bairro == '')
					{
						$valido = false;
					}
				$cidade = htmlspecialchars(trim($_POST['cidade']));
					if($cidade == '')
					{
						$valido = false;
					}
				$empresa = $_POST['empresa'];
					if(!is_numeric($_POST['empresa']))
					{
						$valido = false;
					}

				foreach($_POST['email'] as $email)
				{
					if($email == '' || !ereg("^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+.([a-zA-Z]{2,4})$", $email))
					{
						$valido = false;
					}
				}

				$tipos_tel_aceitos = array('Residencial', 'Celular', 'Trabalho');

				foreach($_POST['telefone'] as $key=>$telefone)
				{
					$tipo = $_POST['tipo_telefone'][$key];

					if($telefone == '' || !is_numeric($telefone) || strlen($telefone) < 10 || !in_array($tipo, $tipos_tel_aceitos))
					{
						unset($_POST['telefone'][$key]);
						unset($_POST['tipo_telefone'][$key]);
					}
				}

				if($valido)
				{
					$data_atual = date("Y-m-d H:i:s");

					$PDO->beginTransaction();

					try
					{
						$PDO->exec("UPDATE contatos SET nome = '$nome', sobrenome = '$sobrenome', endereco = '$endereco', cep = $cep, bairro = '$bairro', cidade = '$cidade', dt_alteracao = '$data_atual' WHERE idcontato = $codigo");

						$PDO->exec("DELETE FROM emails WHERE contato_id = $codigo");
						$PDO->exec("DELETE FROM telefones WHERE contato_id = $codigo");
						$PDO->exec("DELETE FROM contato_empresa WHERE contato_id = $codigo");

						foreach($_POST['email'] as $email)
						{
							$PDO->exec("INSERT INTO emails (email, contato_id) VALUES ('$email', $codigo)");
						}

						foreach($_POST['telefone'] as $key=>$telefone)
						{
							$tipo = $_POST['tipo_telefone'][$key];

							$PDO->exec("INSERT INTO telefones (numero, tipo, contato_id) VALUES ('$telefone', '$tipo', $codigo)");
						}

						if($empresa != 0)
						{
							$PDO->exec("INSERT INTO contato_empresa VALUES ($codigo, $empresa)");
						}

						$PDO->commit();

						echo 'Cadastro atualizado com sucesso.';
					}
					catch (Exception $e)
					{
						$PDO->rollback();

						echo 'Erro: '.$e->getMessage();
					}				
				}
				else
				{
					echo 'Preencha os dados corretamente.';
				}
			break;

			case 'excluir':
				$codigo = $_POST['codigo'];
				if(!is_numeric($codigo))
				{
					echo 'Erro: Código inválido.';
				}
				else
				{
					try
					{
						$PDO->exec("DELETE FROM emails WHERE contato_id = $codigo");
						$PDO->exec("DELETE FROM telefones WHERE contato_id = $codigo");
						$PDO->exec("DELETE FROM contato_empresa WHERE contato_id = $codigo");
						$PDO->exec("DELETE FROM contatos WHERE idcontato = $codigo");

						echo 'Cadastro excluído com sucesso.';
					}
					catch (Exception $e)
					{
						echo 'Não foi possível executar a ação.<br>Erro: '.$e->getMessage();
					}
				}
			break;

			case 'abrir':
				$codigo = $_POST['codigo'];
				if(!is_numeric($codigo))
				{
					echo 'Erro: Código inválido.';
				}
				else
				{
					try
					{
						$sql = $PDO->query("SELECT * FROM contatos WHERE idcontato = $codigo");
						$dados = $sql->fetch(PDO::FETCH_ASSOC);
						$codigo_contato = $dados['idcontato'];

						
						$sql_emp = $PDO->query("SELECT idempresa FROM contato_empresa JOIN empresas on (idempresa = empresa_id) WHERE contato_id = $codigo_contato");

						$dados_emp = $sql_emp->fetch(PDO::FETCH_ASSOC);
					}
					catch (Exception $e)
					{
						echo 'Não foi possível executar a ação.<br>Erro: '.$e->getMessage();
					}
				}
			break;
		}
	}

	?>

	<script>
		function adcTel()
		{
			var inp = document.createElement('input');
			var label = document.createElement('label');
			var sel = document.createElement('select');
			var exc = document.createElement('span');

			inp.setAttribute('type', 'tel');
			inp.setAttribute('name', 'telefone[]');
			inp.setAttribute('placeholder', 'Insira o telefone com DDD');
			inp.setAttribute('required', '');

			sel.setAttribute('name', 'tipo_telefone[]');
			sel.setAttribute('required', '');

			var op1 = document.createElement('option');
			op1.appendChild(document.createTextNode('Celular'));
			var op2 = document.createElement('option');
			op2.appendChild(document.createTextNode('Residencial'));
			var op3 = document.createElement('option');
			op3.appendChild(document.createTextNode('Trabalho'));

			exc.setAttribute('onclick', 'excluirLinha(this)');
			exc.appendChild(document.createTextNode('Apagar'));

			sel.appendChild(op1);
			sel.appendChild(op2);
			sel.appendChild(op3);

			label.appendChild(document.createTextNode('Telefone '+(parseInt(document.getElementsByName('telefone[]').length)+1)));
			label.appendChild(document.createElement('br'));
			label.appendChild(sel);
			label.appendChild(inp);
			label.appendChild(exc);

			document.getElementById("inpTel").appendChild(label);
		}

		function adcEma()
		{
			var inp = document.createElement('input');
			var label = document.createElement('label');
			var exc = document.createElement('span');

			inp.setAttribute('type', 'email');
			inp.setAttribute('name', 'email[]');
			inp.setAttribute('placeholder', 'Insira o e-mail');
			inp.setAttribute('maxlength', 50);
			inp.setAttribute('required', '');

			exc.setAttribute('onclick', 'excluirLinha(this)');
			exc.appendChild(document.createTextNode('Apagar'));

			label.appendChild(document.createTextNode('E-mail '+(parseInt(document.getElementsByName('email[]').length)+1)));
			label.appendChild(document.createElement('br'));
			label.appendChild(inp);
			label.appendChild(exc);

			document.getElementById("inpEma").appendChild(label);
		}

		function excluirLinha(l)
		{
			l.parentNode.parentNode.removeChild(l.parentNode);
		}

		window.onload = function()
		{
			document.getElementById('btnTel').onclick = function()
			{
				adcTel();
			}

			document.getElementById('btnEma').onclick = function()
			{
				adcEma();
			}
		}
	</script>

	<style>
		#inpTel label, #inpEma label {
			display: block;
			margin: 5px 0;
		}
		#btnTel, #btnEma {
			cursor: pointer;
		}
	</style>
</head>
<body>

	<form method="post">
		<p>
			<label>
				Nome<br>
				<input type="text" name="nome" placeholder="Insira o nome" maxlength="50" value="<?php echo (isset($dados))? $dados['nome'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				Sobrenome<br>
				<input type="text" name="sobrenome" placeholder="Insira o sobrenome" maxlength="50" value="<?php echo (isset($dados))? $dados['sobrenome'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<div id="inpEma">
				<?php

				if(isset($dados))
				{
					$i = 1;
					$sql_ema = $PDO->query("SELECT email FROM emails WHERE contato_id = $codigo_contato");

					while($dados_ema = $sql_ema->fetch(PDO::FETCH_ASSOC))
					{
						?>

						<label>
							E-mail <?php echo $i; ?><br>
							<input type="email" name="email[]" placeholder="Insira o e-mail" maxlength="50" value="<?php echo $dados_ema['email']; ?>" required>
							<?php

							if($i > 1)
							{
								?>
								<span onclick="excluirLinha(this)">Apagar</span>
								<?php
							}

							?>
						</label>

						<?php
						$i++;
					}
				}
				else
				{
					?>

					<label>
						E-mail 1<br>
						<input type="email" name="email[]" placeholder="Insira o e-mail" maxlength="50" required>
					</label>

					<?php
				}

				?>
			</div>
			<small id="btnEma">adicionar</small>
		</p>

		<p>
			<div id="inpTel">
				<?php

				if(isset($dados))
				{
					$i = 1;
					$sql_tel = $PDO->query("SELECT numero, tipo FROM telefones WHERE contato_id = $codigo_contato");

					while($dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC))
					{
						?>

						<label>
							Telefone 1<br>
							<select name="tipo_telefone[]" required>
								<option value="Celular" <?php echo ($dados_tel['tipo'] == 'Celular')?'required':'' ?>>Celular</option>
								<option value="Residencial" <?php echo ($dados_tel['tipo'] == 'Residencial')?'required':'' ?>>Residencial</option>
								<option value="Trabalho" <?php echo ($dados_tel['tipo'] == 'Trabalho')?'required':'' ?>>Trabalho</option>
							</select>
							<input type="tel" name="telefone[]" placeholder="Insira o telefone com DDD" value="<?php echo $dados_tel['numero']; ?>" required>
							<?php

							if($i > 1)
							{
								?>
								<span onclick="excluirLinha(this)">Apagar</span>
								<?php
							}
							
							?>
						</label>

						<?php
						$i++;
					}
				}
				else
				{
					?>

					<label>
						Telefone 1<br>
						<select name="tipo_telefone[]" required>
							<option value="Celular">Celular</option>
							<option value="Residencial">Residencial</option>
							<option value="Trabalho">Trabalho</option>
						</select>
						<input type="tel" name="telefone[]" placeholder="Insira o telefone com DDD" required>
					</label>

					<?php
				}

				?>
			</div>
			<small id="btnTel">adicionar</small>
		</p>

		<p>
			<label>
				Endereço<br>
				<input type="text" name="endereco" placeholder="Insira o endereco" maxlength="100" value="<?php echo (isset($dados))? $dados['endereco'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				CEP<br>
				<input type="number" name="cep" placeholder="Insira o cep" value="<?php echo (isset($dados))? $dados['cep'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				Bairro<br>
				<input type="text" name="bairro" placeholder="Insira o bairro" maxlength="50" value="<?php echo (isset($dados))? $dados['bairro'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				Cidade<br>
				<input type="text" name="cidade" placeholder="Insira a cidade" maxlength="50" value="<?php echo (isset($dados))? $dados['cidade'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				Empresa<br>
				<select name="empresa">
					<option value="0">Selecione...</option>
					<?php

					$sql_empresas = $PDO->query("SELECT idempresa, nome FROM empresas ORDER BY nome");

					while($dados_empresa = $sql_empresas->fetch(PDO::FETCH_ASSOC))
					{
						echo '<option value="'.$dados_empresa['idempresa'].'" ';
						if(isset($dados_emp) and $dados_emp['idempresa'] == $dados_empresa['idempresa'])
						{
							echo 'selected';
						}
						echo '>'.$dados_empresa['nome'].'</option>';
					}
					?>
				</select>
				<a href="cadastro_empresa.php"><small>Cadastrar</small></a>
			</label>
		</p>

		<?php

		if(isset($dados))
		{
			echo '<p>';
			echo 'Criação do contato: '.$dados['dt_criacao'];
			echo '<br>';
			echo 'Última alteração: '.$dados['dt_alteracao'];
			echo '</p>';
		}

		?>

		<p>
			<?php
			if(isset($dados))
			{
				?>
				<input type="hidden" name="acao" value="atualizar">
				<input type="hidden" name="codigo" value="<?php echo $dados['idcontato']; ?>">
				<?php
			}
			else
			{
				?>
				<input type="hidden" name="acao" value="cadastrar">
				<?php
			}
			?>
			<button>Salvar</button>
		</p>
	</form>

	<form method="post">
		<select name="codigo">
			<option>Selecione...</option>
			<?php

			$sql_lista = $PDO->query("SELECT idcontato, nome FROM contatos ORDER BY nome");

			while($dados_lista = $sql_lista->fetch(PDO::FETCH_ASSOC))
			{
				echo '<option value="'.$dados_lista['idcontato'].'">'.$dados_lista['nome'].'</option>';
			}
			?>
		</select>

		<button name="acao" value="excluir">Excluir</button>
		<button name="acao" value="abrir">Editar</button>
	</form>

</body>
</html>