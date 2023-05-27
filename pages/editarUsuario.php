<?php

$id = (int)$_GET['id'];
$sql = server::connect()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE ID = ?");
$sql->execute(array($id));
if ($sql->rowCount() == 0) {
    echo '<div class="alert alert-danger" role="alert"  style = "text-align:center;">
        <i class="fa fa-triangle-circlex" style = "margin-right:10px;"></i>O usuário que você quer atualizar não existe.</div>';
    die;
}

$infoProduto = $sql->fetch();

?>

<div class="espacamento"></div>

<?php Painel::roleVerify($_SESSION['cargo']); ?>

<div class="box-content">

    <?php if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $usuario = $_POST['user'];
        $senha = $_POST['password'];

        $verify = server::connect()->prepare("SELECT user FROM `tb_admin.usuarios` WHERE user = '$usuario'");
        $verify->execute();


        $sql = server::connect()->prepare("UPDATE `tb_admin.usuarios` SET nome = ?, user = ?, password = ?  WHERE id = '$id'");
        $sql->execute(array($nome, $usuario, $senha));

        echo '<div class="alert alert-success" role="alert"  style = "text-align:center;">
            <i class="fa fa-check" style = "margin-right:10px;"></i><b>Usuário atualizado.</b></div>';
    }



    ?>
    <h2><i class="icon_principal fa-solid fa-pencil"></i><a href='<?php echo INCLUDE_PATH_PAINEL ?>usuarios '>Usuários</a> > Editando usuário <?php echo $id; ?>:</h2>


    <form method="post" enctype="multipart/form-data" style="margin-left:50px; width:500px !important;">

        <div class="form-group">
            <label><b>Nome</b> do fornecedor:</label><br>
            <input type="text" name="nome" class="nomes" value="<?php echo $infoProduto['nome']; ?>">
        </div>

        <div class="form-group">
            <label><b>Usuário</b></label><br>
            <input type="text" name="user" value="<?php echo $infoProduto['user']; ?>">
        </div>

        <div class="form-group">
            <label><b>Senha</b> do usuário:</label><br>
            <input type="text" name="password" value="<?php echo $infoProduto['password']; ?>">
        </div>

        <input type="submit" value="Atualizar" name="cadastrar" class="btn btn-light">


    </form>