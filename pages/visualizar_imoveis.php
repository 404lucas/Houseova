<div class="espacamento"></div>

<div class="box-content" id="apres">
    <h2>Bem-vindo(a), <br>
        <?php echo '<b>' .
            strtok($_SESSION['nome'], " ") . '!</b>'; ?>
    </h2>
</div>

<div class="box-content">

    <h2><i class="fa-solid fa-building icon_principal"></i></i>Imóveis disponíveis</h2>
    <div class="busca">
        <h4><i class="fas fa-magnifying-glass"></i> Realizar busca</h4>
        <form method="post">
            <input placeholder="Nome ou local do imóvel" type="text" name="busca">
            <input type="submit" name="acao" value="Buscar">
        </form>
    </div>


    <br>
    <?php
    if (isset($_GET['excluir'])) {

        $iddel = (int)$_GET['excluir'];
        $imagens = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE ID = $iddel");
        $imagens->execute();
        $imagens = $imagens->fetchAll();
        foreach ($imagens as $key => $value) {
            @unlink(BASE_DIR_PAINEL . 'uploads/' . $value['arquivo']);
        }
        server::connect()->exec("DELETE FROM `tb_admin.estoque_imagens` WHERE produto_id = $iddel");
        server::connect()->exec("DELETE FROM `tb_admin.imoveis` WHERE ID = $iddel");

        echo '<div class="alert alert-light" style="text-align:center; margin-top:10px;" role="alert">
                <i class="fa-solid fa-trash-can"></i>
                Você excluiu o produto com sucesso.</div>';
    }


    if (isset($_POST['indisponibilizar'])) {

        $produto_id = $_POST['id_produto'];
        $data = date('Y:m:d H:i:s');

        //Atualizando a quantidade
        server::connect()->exec("UPDATE `tb_admin.imoveis` SET condicao = 'Indisponível' WHERE id = '$produto_id' ");
        server::connect()->exec("UPDATE `tb_admin.imoveis` SET data = '$data' WHERE id = '$produto_id' ");
        echo '<div class="alert alert-success" style="text-align:center; margin-top:10px;" role="alert">
                <i class="fa-solid fa-circle-check"></i><b> ' . $_POST['nomenome'] . '</b>, agora está indisponível.</div>';
    }
    ?>
    <div class="boxes">

        <div class="box-single-wraper">

            <?php
            $query = "";
            if (isset($_POST['acao']) && $_POST['acao'] == 'Buscar') {
                $nome = $_POST['busca'];
                $query = "AND (nome LIKE '%$nome%' OR local LIKE '%$nome%')";
            }

            $sql = server::connect()->prepare("SELECT * FROM `tb_admin.imoveis` WHERE condicao = 'Disponível' $query ORDER BY nome ASC");

            $sql->execute();
            $produtos = $sql->fetchAll();
            if ($query != "") {
                echo '<div class="busca-result"><p>Foram encontrados <b>' . count($produtos) . '</b> resultado(s)</p>';
            }

            foreach ($produtos as $key => $value) {
                $imagemSingle = server::connect()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = '$value[ID]' LIMIT 1");
                $imagemSingle->execute();
                @$imagemSingle = $imagemSingle->fetch()['arquivo'];
            ?>

                <div class="box-single">
                    <div class="body-box">
                        <div class="img-box">
                            <?php
                            if ($imagemSingle == '') {
                            ?>
                                <h1><i class="fa-solid fa-building" aria-hidden="true"></i></h1>

                            <?php } else { ?>
                                <img src="<?php echo INCLUDE_PATH_PAINEL ?>uploads/<?php echo $imagemSingle; ?>">
                            <?php } ?>
                        </div>
                        <div class="textContainer">
                            <div class="mainContainer">
                                <p class="titulo">
                                    <?php echo $value['nome']; ?>
                                </p>

                                <label>
                                    <?php echo $value['local']; ?>
                                </label>

                            </div>

                            <div class="extraContainer">
                                <p>

                                    <?php function limit_text($text, $limit)
                                    {
                                        if (str_word_count($text, 0) > $limit) {
                                            $words = str_word_count($text, 2);
                                            $pos   = array_keys($words);
                                            $text  = substr($text, 0, $pos[$limit]) . '...';
                                        }
                                        return $text;
                                    }

                                    $description = $value['descricao'];

                                    echo limit_text($description, 45); ?>

                                </p>
                                <p class="preço">R$<b>
                                        <?php echo $value['preço']; ?>
                                    </b></p>
                                <p>
                                    <?php echo $value['tipo']; ?>
                                </p>
                            </div>

                            <p><b>Última atualização:</b>
                                <?php echo date('<b>d/m/Y </b>H:i:s', strtotime($value['data'])); ?>
                            </p>
                        </div>
                        <hr>
                        <a class="btn btn-light btn-block" href="<?php echo INCLUDE_PATH_PAINEL ?>detalhes?ID=<?php echo $value['ID']; ?>"></i>Ver mais</a>

                    </div>
                </div>

            <?php }

            ?>
        </div>
    </div>
</div>
</div>