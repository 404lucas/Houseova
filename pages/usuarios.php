<div class="espacamento"></div>

<?php Painel::roleVerify($_SESSION['cargo']); ?>

<div class="box-content">

    <h2><i class="fa fa-user icon_principal"></i> Cadastrar usuários</h2>

    <?php if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        $idd = null;;



        $verify = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE user = '$usuario'");
        $verify->execute();

        if ($verify->rowCount() == 1) {
            echo '<div class="alert alert-danger" style="text-align:center;" role="alert">
             <i class="fa fa-times"></i>
            <b>Este usuário já existe.</b>
              </div>';
        } else {

            $inserir = server::connect()->prepare("INSERT INTO `tb_admin.usuarios` VALUES (?,?,?,?,?,?)");
            $inserir->execute(array($idd, $usuario, $nome, $senha, 2, ""));
            $lastId = server::connect()->lastInsertId();

            echo '<div class="alert alert-success" style="text-align:center;" role="alert">
             <i class="fa fa-check"></i>
             Usuário cadastrado!
              </div>';
        }
    }

    ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label><b>Nome</b> do usuário:</label><br>
            <input type="text" name="nome" class="nomes" required>
        </div>

        <div class="form-group">
            <label><b>Usuário</b></label><br>
            <input type="text" name="usuario" class="nomes" required>

        </div>

        <div class="form-group">
            <label><b>Senha</b> do usuário:</label><br>
            <input type="text" name="senha" required>
        </div>

        <input type="submit" value="Cadastrar" class="btn btn-light" name="cadastrar">


    </form>

</div>

<div class="box-content">

    <h2><i class="fa fa-user icon_principal"></i> Usuários cadastrados</h2>

    <div class="busca">
        <h4><i class="fas fa-magnifying-glass"></i> Realizar busca</h4>
        <form method="post">
            <input placeholder="Nome ou usuário" type="text" name="busca">
            <input type="submit" name="acao" value="Buscar">
        </form>
    </div>

    <?php

    if (isset($_GET['excluir'])) {
        $iddel = (int)$_GET['excluir'];
        server::connect()->exec("DELETE FROM `tb_admin.usuarios` WHERE ID = $iddel");

        echo '<div class="alert alert-dark" style="text-align:center; margin-top:10px;" role="alert">
                    <i class="fa-solid fa-trash-can"></i>
                    <b>Usuário excluído.</b></div>';
    } ?>

    <div class="boxes" style="display: inline-block !important; width:100%;">

        <div class="box-single-wraper" style="display: block !important;">

            <?php
            $query = "";
            if (isset($_POST['acao']) && $_POST['acao'] == 'Buscar') {
                $nome = $_POST['busca'];
                $query = "AND (nome LIKE '%$nome%' OR user LIKE '%$nome%')";
            }

            $sql = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE cargo != 1 $query ORDER BY nome ASC");
            $sql->execute();
            $forn = $sql->fetchAll();
            if ($query != "") {
                echo '<div class="busca-result"><p>Foram encontrados <b>' . count($forn) . '</b> resultado(s)</p>';
            }
            foreach ($forn as $key => $value) {

            ?>

                <div class="box-single" style="display: inline-block;">
                    <div class="body-box" style="display: block; padding:15px;">

                        <p class="titulo"><?php echo $value['nome']; ?></p>

                        <p><b><i class="fa-solid fa-list-user"></i> Usuário: </b><?php echo $value['user']; ?></p>
                        <p><b><i class="fa-solid fa-keylock"></i> Senha: <b><?php echo $value['password']; ?></p>
                        <br>

                        <a class="btn btn-warning" href="<?php echo INCLUDE_PATH_PAINEL ?>editarUsuario?id=<?php echo $value['ID']; ?>"><i class="fa fa-pencil"></i> Editar</a>
                        <a class="btn btn-danger" href="<?php echo INCLUDE_PATH_PAINEL ?>usuarios?excluir=<?php echo $value['ID']; ?>"><i class="fa-solid fa-trash-can"></i> Excluir</a>


                    </div>
                </div>
            <?php } ?>
        </div>
    </div>