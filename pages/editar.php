<?php
$id = (int)$_GET['ID'];
$sql = server::connect()->prepare("SELECT * FROM `tb_admin.imoveis` WHERE ID = ?");
$sql->execute(array($id));
if ($sql->rowCount() == 0) {
    echo '<div class="alert alert-danger" role="alert"  style = "text-align:center;">
        <i class="fa fa-triangle-circlex" style = "margin-right:10px;"></i>O imóvel que você quer atualizar não existe.</div>';
    die;
}

$infoImovel = $sql->fetch();

$catchImagens = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = $id");
$catchImagens->execute();
$catchImagens = $catchImagens->fetchAll();


?>

<div class="espacamento"></div>
<?php Painel::roleVerify($_SESSION['cargo']);?>
<div class="box-content">
    <?php
    if (isset($_GET['deletarImagem'])) {
        $idImagem = $_GET['deletarImagem'];
        @unlInk(BASE_DIR_PAINEL . 'uploads/' . $id);
        server::connect()->exec("DELETE FROM `tb_admin.estoque_imagens` WHERE arquivo = '$idImagem'");
        echo '<div class="alert alert-success" role="alert"  style = "text-align:center;">
        <i class="fa fa-check" style = "margin-right:10px;"></i>A imagem foi excluída com sucesso.</div>';
        $catchImagens = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = $id");
        $catchImagens->execute();
        $catchImagens = $catchImagens->fetchAll();
    }
    ?>
    <h2><i class="icon_principal fa-solid fa-pencil"></i> Imóveis cadastrados > Editando imóvel <?php echo $id; ?>:</h2>
    <h2><b style="font-size:40px; margin-top:10px; margin-left:55px; font-weight:700;"><?php echo $infoImovel['nome']; ?></b></h2>
    <div class="alert alert-dark">Imagens do imóvel:</div>
    <div class="boxes">

        <?php
        foreach ($catchImagens as $key => $value) {

        ?>
            <div class="box-single-wraper">
                <div class="box-single" style="margin-left:50px;">
                    <div class="body-box">
                        <div class="img-box" style="width:100%; display:inline-block;">
                            <img src="<?php echo INCLUDE_PATH_PAINEL ?>uploads/<?php echo $value['arquivo'] ?>">
                        </div>
                        <div class="group-btn">
                            <a class="btn btn-danger" href="<?php echo INCLUDE_PATH_PAINEL; ?>editar?ID=<?php echo $id; ?>&deletarImagem=<?php echo $value['arquivo']; ?>"><i class="fa-solid fa-trash-can"></i> Excluir</a>
                        </div>
                    </div>
                </div><?php } ?>
            </div>


            <div class="alert alert-dark">Informações do imóvel:</div>

            <?php if (isset($_POST['atualizar'])) {
                $nome = $_POST['nome'];
                $local = $_POST['local'];
                $preco = $_POST['preco'];
                $tipo = $_POST['tipo'];
                $descricao = $_POST['descricao'];
                $condicao = $_POST['condicao'];
                $data = date('Y-m-d H:i:s');

                $amountFiles = count($_FILES['imagem']['name']);
                $imagens = [];
                $sucesso = true;


                if ($_FILES['imagem']['name'][0] != '') {
                    //o usuário quer mais imagens
                    for ($i = 0; $i < $amountFiles; $i++) {
                        $imagemAtual = [
                            'type' => $_FILES['imagem']['type'][$i],
                            'size' => $_FILES['imagem']['size'][$i]
                        ];
                        if (Painel::imageValid($imagemAtual) == FALSE) {
                            $sucesso = false;
                            echo '<div class="alert alert-danger" role="alert" style = "text-align:center;">
                        <i class="fa fa-circle-x style = "margin-right:10px;""></i>
                        Uma das imagens selecionadas é inválida.
                      </div>';
                            break;
                        }
                    }
                }

                if ($sucesso) {
                    if ($amountFiles > 0) {
                        for ($i = 0; $i < $amountFiles; $i++) {
                            $imagemAtual = [
                                'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
                                'name' => $_FILES['imagem']['name'][$i]
                            ];
                            $imagens[] = Painel::uploadFile($imagemAtual);
                        }

                        foreach ($imagens as $key => $value) {
                            server::connect()->exec("INSERT INTO `tb_admin.estoque_imagens` VALUES (null,'$id','$value')");
                        }
                    }
                    $sql = server::connect()->prepare("UPDATE `tb_admin.imoveis` SET nome = ?, local = ?, preço = ?, tipo = ?, descricao = ?, condicao = ?, data = ? WHERE ID = ?");
                    $sql->execute(array($nome, $local, $preco, $tipo, $descricao, $condicao, $data, $id));

                    echo '<div class="alert alert-success" role="alert"  style = "text-align:center; width:100% !important;">
                <i class="fa fa-check" style = "margin-right:10px;"></i> Imóvel atualizado.</div>';

                    $sql = server::connect()->prepare("SELECT * FROM `tb_admin.imoveis` WHERE ID = ?");
                    $sql->execute(array($id));
                    $infoProduto = $sql->fetch();
                }
            }
            ?>


            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label><b>Nome</b> do imóvel:</label><br>
                    <input type="text" name="nome" class="nomes" value=" <?php echo $infoImovel['nome']; ?> ">
                </div>

                <div class="form-group">
                    <label><b>Local</b> do imóvel:</label><br>
                    <input type="text" name="local" class="nomes" value=" <?php echo $infoImovel['local']; ?> ">
                </div>

                <div class="form-group">
                    <label><b>Tipo</b> do imóvel</label><br>
                    <select name="tipo" id="cars" required>

                        <?php if ($infoImovel['tipo'] == 'Aluguel') {
                        ?>
                            <option value="Disponível" selected>Aluguel</option>
                            <option value="Indisponível">Venda</option>
                        <?php } else {
                        ?>
                            <option value="Disponível">Aluguel</option>
                            <option value="Indisponível" selected>Vendas</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><b>Preço</b> do imóvel:</label><br>
                    <input type="number" name="preco" class="nomes" step="0.01" value="<?php echo (double)$infoImovel['preço']; ?>">
                </div>

                <div class="form-group">
                    <label><b>Descrição</b> do imóvel:</label><br>
                    <textarea name="descricao"><?php echo $infoImovel['descricao']; ?></textarea>
                </div>

                <div class="form-group">
                    <label><b>Disponibilidade</b></label><br>
                    <select name="condicao" id="cars" required>

                        <?php if ($infoImovel['local'] == 'Disponível') {
                        ?>
                            <option value="Disponível" selected>Disponível</option>
                            <option value="Indisponível">Indisponível</option>
                        <?php } else {
                        ?>
                            <option value="Disponível">Disponível</option>
                            <option value="Indisponível" selected>Indisponível</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><b>Imagem</b> do produto: </label><br>
                    <input multiple type="file" name="imagem[]" accept="image/png, image/gif, image/jpeg">
                </div>

                <h5 style="font-size:12px;">Use apenas arquivos com até 7.2 MB</h5>

                <input type="submit" value="Atualizar" name="atualizar" class="btn btn-light">


            </form>
    </div>