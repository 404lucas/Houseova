<?php

$id = $_SESSION['id'];
$sql = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE ID = $id");
$sql->execute();
$infoUser = $sql->fetch();

?>

<div class="espacamento"></div>

<?php Painel::roleVerify($_SESSION['cargo']); ?>

<div class="box-content">

    <?php
    if (isset($_GET['deletarImagem'])) {
        $idImagem = $_GET['deletarImagem'];
        @unlInk(BASE_DIR_PAINEL . 'uploads/' . $idImagem);
        server::connect()->exec("DELETE FROM `tb_admin.estoque_imagens` WHERE arquivo = '$idImagem'");
        echo '<div class="alert alert-success" role="alert"  style = "text-align:center;">
        <i class="fa fa-check" style = "margin-right:10px;"></i>A imagem foi excluída com sucesso.</div>';
        server::connect()->exec("UPDATE `tb_admin.usuarios`SET img = '' WHERE id = $id");
    }


    if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $usuario = $_POST['user'];
        $senha = $_POST['password'];

        $sucesso = true;

        if ($_FILES['imagem']['size'] != 0) {
            $imagemAtual = [
                'type' => $_FILES['imagem']['type'],
                'size' => $_FILES['imagem']['size']
            ];

            if (Painel::imageValid($imagemAtual) == FALSE) {
                $sucesso = false;
                echo '<div class="alert alert-danger" role="alert" style = "text-align:center;">
                <i class="fa fa-circle-x style = "margin-right:10px;""></i>
                <i class="fa fa-times"></i><b>Uma das imagens selecionadas é inválida. Será que é grande demais?</b>
              </div>';
            }
        }

        if ($sucesso == true) {
            $imagem = Painel::uploadFile($_FILES['imagem']);
            server::connect()->exec("INSERT INTO `tb_admin.estoque_imagens` VALUES (null,-1,'$imagem')");


            $sql = server::connect()->prepare("UPDATE `tb_admin.usuarios` SET user = ?, nome = ?, password = ?, img = '$imagem' WHERE ID = $id");
            $sql->execute(array($usuario, $nome, $senha));

            echo '<div class="alert alert-success" role="alert"  style = "text-align:center;">
            <i class="fa fa-check" style = "margin-right:10px;"></i><b>Perfil atualizado!.</b></div>';
        }
    }



    ?>
    <h2><i class="icon_principal fa-solid fa-gear"></i><a href='<?php echo INCLUDE_PATH_PAINEL ?>usuarios '></a>Editando perfil</h2>


    <?php
    if ($infoUser['img'] == '') {
    ?>
        <div class="avatar-usuario" style="position: relative; left: 30px; margin: 30px;">
            <i class="fa-solid fa-user-alt"></i>
        </div>

    <?php } else { ?>
        <div class="img-box" style="position: relative; left: 30px; margin: 30px;">
            <img src="<?php echo INCLUDE_PATH_PAINEL ?>uploads/<?php echo $infoUser['img']; ?>">
        </div>

        <div class="group-btn" style="position: relative; left: 30px; margin: 30px;">
            <a class="btn btn-danger" href="<?php echo INCLUDE_PATH_PAINEL; ?>editarPerfil?ID=<2&deletarImagem=<?php echo $infoUser['img']; ?>"><i class="fa-solid fa-trash-can"></i> Excluir imagem</a>
        </div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data" style="margin-left:50px; width:500px !important;">

        <div class="form-group">
            <label>Meu <b>Nome</b></label><br>
            <input type="text" name="nome" class="nomes" value="<?php echo $infoUser['nome']; ?>">
        </div>

        <div class="form-group">
            <label><b>Usuário</b></label><br>
            <input type="text" name="user" value="<?php echo $infoUser['user']; ?>">
        </div>

        <div class="form-group">
            <label><b>Senha</b> do usuário:</label><br>
            <input type="text" name="password" value="<?php echo $infoUser['password']; ?>">
        </div>


        <div class="form-group">
            <label><b>Ícone</b></label><br>
            <input type="file" name="imagem" accept="image/png, image/gif, image/jpeg">
        </div>

        <h5 style="font-size:12px;">Use apenas arquivos com até 7.2 MB</h5>

        <input type="submit" value="Atualizar" name="cadastrar" class="btn btn-light">


    </form>