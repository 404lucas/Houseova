<?php

if ($_SESSION['cargo'] = '1' || $_SESSION['cargo'] = '2') {
    if (isset($_GET['pendentes']) == false) {
?>

        <div class="espacamento"></div>

        <?php Painel::roleVerify($_SESSION['cargo']); ?>

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


            if (isset($_POST['disponibilizar'])) {

                $produto_id = $_POST['id_produto'];
                $data = date('Y:m:d H:i:s');

                //Atualizando a quantidade
                server::connect()->exec("UPDATE `tb_admin.imoveis` SET condicao = 'Disponível' WHERE id = '$produto_id' ");
                server::connect()->exec("UPDATE `tb_admin.imoveis` SET data = '$data' WHERE id = '$produto_id' ");
                echo '<div class="alert alert-success" style="text-align:center; margin-top:10px;" role="alert">
                <i class="fa-solid fa-circle-check"></i><b> ' . $_POST['nomenome'] . '</b> agora está disponível.</div>';
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

                    $sql = server::connect()->prepare("SELECT * FROM `tb_admin.imoveis` WHERE condicao = 'Indisponível' $query ORDER BY nome ASC");

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


                                <div class="botoes">

                                    <div class="group-btn">
                                        <a class="btn btn-warning" href="<?php echo INCLUDE_PATH_PAINEL ?>editar?ID=<?php echo $value['ID']; ?>"><i class="fa fa-pencil"></i> Editar</a>

                                        <?php $idjs = $value['ID'];  ?>
                                        <div id="sure<?php echo $idjs; ?>">
                                            <button class="btn btn-danger" onclick="sure(<?php echo $idjs; ?>)"><i class="fa fa-trash"></i> Excluir</button>
                                        </div>
                                    </div>

                                    <div class="formContainer">
                                        <p class="alert alert-danger condicao">
                                            <?php echo $value['condicao']; ?>
                                        </p>
                                        <form method="post">
                                            <input type="hidden" name="id_produto" value="<?php echo $value['ID']; ?>">
                                            <input type="hidden" name="nomenome" value="<?php echo $value['nome']; ?>">
                                            <div class="btn-group" role="group">

                                                <input class="btn btn-secondary margin" type="submit" name="disponibilizar" value="Disponibilizar" id="atualizar" data-toggle="tooltip" data-placement="top" title="Tornar imóvel indisponível.">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <a class="btn btn-light btn-block" href="<?php echo INCLUDE_PATH_PAINEL ?>detalhes?ID=<?php echo $value['ID']; ?>"></i>Ver mais</a>
                            </div>
                        </div>

            <?php }
                }
            } ?>
                </div>
            </div>
        </div>
        </div>