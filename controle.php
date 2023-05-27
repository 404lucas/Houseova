<?php
if (isset($_GET['loggout'])) {
	Painel::loggout();
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Sistema</title>
	<meta name="viewport" content="widht=device-widht, initial-scale=1.0">
	<link rel="stylesheet" href="css/aa.css">
	<script src="https://kit.fontawesome.com/9e0a3da5bd.js" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

	<link rel="icon" type="image/png" href="imgs/favicon.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


</head>

<body style="background-color:#1f1f1f;">

	<?php

	$id = $_SESSION['id'];
	$sql = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE ID = $id");
	$sql->execute();
	$sql = $sql->fetch(); ?>

	<aside>

		<div class="menu-wrapper">

			<div class="box-usuario">
				<?php
				if ($sql['img'] == '') {
				?>
					<div class="avatar-usuario">
						<i class="fa-solid fa-user-alt"></i>
					</div>

				<?php } else { ?>
					<div class="imagem-usuario">
						<img src="<?php

									echo INCLUDE_PATH_PAINEL ?>uploads/<?php echo $sql['img']; ?>">
					</div>
				<?php } ?>
				<div class="nome-usuario">
					<p class="user"><?php echo $sql['nome']; ?></p>
					<p class="cargo"><?php echo catchRole($sql['cargo']); ?></p>
				</div>
			</div>

			<div class="itens-menu">

			<?php if($_SESSION['cargo'] == 1){ ?>

				<a class="navPrincipal" href="<?php echo INCLUDE_PATH_PAINEL ?>home"><i class="fa-solid fa-home"></i>Home</a>

				<h2 onclick="navOpen()"><i class="fa-solid fa-building"></i>Imóveis<i id="chevron" class="fa-solid fa-chevron-right chevron"></i></h2>
				<div id="innerNav">
				</div>

				<a class="navPrincipal" href="<?php echo INCLUDE_PATH_PAINEL ?>cadastrar"><i class="fa-solid fa-plus"></i>Cadastro</a>

				<a class="navPrincipal" href="<?php echo INCLUDE_PATH_PAINEL ?>usuarios"><i class="fa-solid fa-user"></i>Usuários cadastrados</a>
				<a class="navPrincipal" href="<?php echo INCLUDE_PATH_PAINEL ?>editarPerfil"><i class="fa-solid fa-gear"></i>Meu perfil</a>

			<?php }else{ ?>

				<a class="navPrincipal" href="<?php echo INCLUDE_PATH_PAINEL ?>visualizar_imoveis"><i class="fa-solid fa-building"></i>Imóveis disponíveis</a>
				
				<?php } ?>
				<hr>
			</div>
		</div>
	</aside>


	<header>

		<div class="btn-home">
			<img src="imgs/log.png">
			<i class="fa-solid fa-bars menu-btn" style="color: #fff;"></i>
			<!-- img src="imgs/log.png" -->

			<a href="<?php echo INCLUDE_PATH_PAINEL ?>?loggout" id="loggout"><i class="fas fa-sign-out-alt"></i></a>
		</div>

	</header>


	<div class="content">

		<?php Painel::loadPage(); ?>

		<div class="clear"></div>

	</div>

	<div class="clear"></div>

	<script src="<?php echo INCLUDE_PATH_PAINEL ?>js/jquery.min.js"></script>
	<script src="<?php echo INCLUDE_PATH_PAINEL ?>js/main.js"></script>

</body>

</html>