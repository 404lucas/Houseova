<?php
$id = (int)$_GET['ID'];
$sql = server::connect()->prepare("SELECT * FROM `tb_admin.imoveis` WHERE ID = ?");
$sql->execute(array($id));
if ($sql->rowCount() == 0) {
    echo '<div class="alert alert-danger" role="alert"  style = "text-align:center;">
        <i class="fa fa-triangle-circlex" style = "margin-right:10px;"></i>O imóvel que você quer visualizar não existe.</div>';
    die;
}

$infoImovel = $sql->fetch();

if ($infoImovel['condicao'] == "Indisponível") {
    Painel::roleVerify($_SESSION['cargo']);
}

$catchImagens = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = $id");
$catchImagens->execute();
$catchImagens = $catchImagens->fetchAll();


?>

<div class="espacamento"></div>

<div class="box-content" style="padding: 0;">
    <?php

    $catchImagens = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = $id");
    $catchImagens->execute();
    $catchImagens = $catchImagens->fetchAll();

    ?>



    <div id="carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img class="d-block w-100" src="<?php echo INCLUDE_PATH_PAINEL ?>uploads/carouselBackground.png">
            </div>

            <?php foreach ($catchImagens as $key => $value) { ?>

                <div class="carousel-item">
                    <img class="d-block w-100" src="<?php echo INCLUDE_PATH_PAINEL ?>uploads/<?php echo $value['arquivo'] ?>">
                </div>

            <?php } ?>
            <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="textContainer">

        <div class="title">
            <div class="subText">
                <h6 class="detailTitle"><i class="fa fa-building icon_principal"></i><?php echo $infoImovel['nome']; ?> </h6>
                <h6 class="detailLocal"><?php echo $infoImovel['local']; ?></h6>
            </div>
            <div class="subText">
                <h6 class="detailType"><?php echo $infoImovel['tipo']; ?></h6>

                <?php if ($infoImovel['condicao'] == "Disponível") { ?>
                    <h6 class="detailCondition alert alert-success"><?php echo $infoImovel['condicao']; ?></h6>
                <?php } else { ?>
                    <h6 class="detailCondition alert alert-danger"><?php echo $infoImovel['condicao']; ?></h6>
                <?php } ?>
            </div>
        </div>
        <hr>

        <h6 class="detailPrice">R$ <?php echo $infoImovel['preço']; ?></h6>
        <h6 class="detailDescription"><?php echo $infoImovel['descricao']; ?></h6>


    </div>