<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Sistema - Entrar</title>
	<meta charset="utf-8">
	<meta name="viewport" content="widht=device-widht, initial-scale=1.0">
	<link rel="stylesheet" href="<?php INCLUDE_PATH_PAINEL ?>css/aa.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<link rel="icon" type="image/png" href="/images/log.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
	<div class="fundo-login">
		<div class="box-login">

			<img src="imgs/log.png">
			<h3 id="mainText">Bem-vindo(a) de volta!</h3>
			<form method="post">

				<h5 class="log">Usuário</h5><br>
				<input type="text" name="user" id="userInput" required onchange="onchangeLogin()"><br>
				<h5 class="log">Senha</h5><br>
				<input type="password" name="password" minlenghth="4" required><br>

				<script>
					function onchangeLogin() {

						var mainText = document.getElementById('mainText');
						var userInput = document.getElementById('userInput');
						mainText.innerHTML = "Bem-vindo(a) de volta, " + userInput.value + "!";
					}
				</script>


				<?php

				if (isset($_POST['acao'])) {
					$user = $_POST['user'];
					$password = $_POST['password'];
					$sql = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE user = ? AND password = ?");
					$sql->execute(array($user, $password));

					if ($sql->rowCount() == 1) {
						$info = $sql->fetch();
						//Logado
						$_SESSION['login'] = true;
						$_SESSION['id'] = $info['ID'];
						$_SESSION['user'] = $user;
						$_SESSION['password'] = $password;
						$_SESSION['cargo'] = $info['cargo'];
						$_SESSION['nome'] = $info['nome'];
						$_SESSION['img'] = $info['img'];

						if ($_SESSION['cargo'] == 1) {
							header('Location: ' . INCLUDE_PATH_PAINEL);
						} else {
							header('Location: ' . INCLUDE_PATH_PAINEL . '/visualizar_imoveis');
						}
					} else {
						//Falhou
						echo '<div class="alert alert-danger" style="text-align:center;" role="alert">
                 		<i class="fa fa-times"></i>
              			<b>Usuário ou senha incorretos.</b>
                  		</div>';
					}
				}
				?>



				<input type="submit" name="acao" value="Entrar">
				<i>
			</form>



		</div>
	</div>
</body>

</html>