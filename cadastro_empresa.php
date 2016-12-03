<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de empresas</title>
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
				$telefone = $_POST['telefone'];
					if($telefone == '' || !is_numeric($telefone) || strlen($telefone) < 10)
					{
						$valido = false;
					}

				if($valido)
				{
					try
					{
						$PDO->exec("INSERT INTO empresas (nome, telefone) VALUES ('$nome', '$telefone')");

						echo 'Cadastro salvo com sucesso.';
					}
					catch(Exception $e)
					{
						echo 'Não foi possível salvar o cadastro. Erro: '.$e->getMessage();
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
				$telefone = $_POST['telefone'];
					if($telefone == '' || !is_numeric($telefone) || strlen($telefone) < 10)
					{
						$valido = false;
					}

				if($valido)
				{
					try
					{
						$PDO->exec("UPDATE empresas SET nome = '$nome', telefone = '$telefone' WHERE idempresa = $codigo");

						echo 'Cadastro salvo com sucesso.';
					}
					catch(Exception $e)
					{
						echo 'Não foi possível salvar o cadastro. Erro: '.$e->getMessage();
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
						$PDO->exec("DELETE FROM empresas WHERE idcontato = $codigo");

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
						$sql = $PDO->query("SELECT * FROM empresas WHERE idempresa = $codigo");

						$dados = $sql->fetch(PDO::FETCH_ASSOC);
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
</head>
<body>

	<form method="post">
		<p>
			<label>
				Nome<br>
				<input type="text" name="nome" placeholder="Nome da empresa" maxlength="50" value="<?php echo (isset($dados))? $dados['nome'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<label>
				Telefone<br>
				<input type="tel" name="telefone" placeholder="Telefone principal com DDD" value="<?php echo (isset($dados))? $dados['telefone'] : ''; ?>" required>
			</label>
		</p>

		<p>
			<?php
			if(isset($dados))
			{
				?>
				<input type="hidden" name="codigo" value="<?php echo $dados['idempresa']; ?>">
				<input type="hidden" name="acao" value="atualizar">
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
			<?php

			$sql_empresas = $PDO->query("SELECT idempresa, nome FROM empresas ORDER BY nome");

			while($dados_empresa = $sql_empresas->fetch(PDO::FETCH_ASSOC))
			{
				echo '<option value="'.$dados_empresa['idempresa'].'">'.$dados_empresa['nome'].'</option>';
			}
			?>
		</select>

		<button name="acao" value="excluir">Excluir</button>
		<button name="acao" value="abrir">Editar</button>
	</form>

</body>
</html>