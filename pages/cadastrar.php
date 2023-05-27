<div class="espacamento"></div>
<?php Painel::roleVerify($_SESSION['cargo']);?>
<div class="box-content">

    <h2><i class="fa fa-plus icon_principal"></i> Cadastrar Imóvel</h2>

    <?php if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $local = $_POST['local'];
        $preco = $_POST['preco'];
        $tipo = $_POST['tipo'];
        $descricao = $_POST['descricao'];
        $condicao = $_POST['condicao'];
        $data = date('Y-m-d H:i:s');

        $idd = null;

        $imagems = array();
        $amountFiles = count($_FILES['imagem']['name']);

        $sucesso = true;

        if ($_FILES['imagem']['name'][0] != '') {

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
        } else {
            $sucesso = false;
            echo '<div class="alert alert-warning" role="alert"  style = "text-align:center;">
            <i class="fa fa-triangle-exclamation style = "margin-right:10px;"></i> Não se esqueça de selecionar uma imagem.
          </div>';
        }

        if ($sucesso) {
            for ($i = 0; $i < $amountFiles; $i++) {
                $imagemAtual = [
                    'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
                    'name' => $_FILES['imagem']['name'][$i]
                ];
                $imagens[] = Painel::uploadFile($imagemAtual);
            }

            $inserir = server::connect()->prepare("INSERT INTO `tb_admin.imoveis` VALUES (?,?,?,?,?,?,?,?)");
            $inserir->execute(array($idd, $nome, $local, $tipo, $preco, $descricao, $condicao, $data));
            $lastId = server::connect()->lastInsertId();



            foreach ($imagens as $key => $value) {
                server::connect()->exec("INSERT INTO `tb_admin.estoque_imagens` VALUES (null,'$lastId','$value')");
            }

            echo '<div class="alert alert-success" style="text-align:center;" role="alert">
             <i class="fa fa-check"></i>
             <b>Imóvel cadastrado!</b>
              </div>';
        }
    }

    ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label><b>Nome</b> do imóvel:</label><br>
            <input type="text" name="nome" class="nomes" required>
        </div>
        
        <div class="form-group">
            <label><b>Local</b> do imóvel:</label><br>
            <input type="text" name="local" class="nomes" required>
        </div>

        <div class="form-group">
            <label><b>Tipo</b> do imóvel</label><br>
            <select name="tipo" id="cars" required>4
                <option value="" selected disabled>Tipo</option>
                <option value="Venda">Venda</option>
                <option value="Aluguel">Aluguel</option>
            </select>
        </div>

        <div class="form-group">
            <label><b>Preço</b> do imóvel:</label><br>
            <input type="number" name="preco" class="nomes" min="0.01" step="any"  required>
        </div>

        <div class="form-group">
            <label><b>Descrição</b> do imóvel:</label><br>
            <textarea name="descricao"></textarea>
        </div>

        <div class="form-group">
            <label><b>Disponibilidade</b></label><br>
            <select name="condicao" id="cars" required>4
                <option value="Disponível" selected>Disponível</option>
                <option value="Indisponível">Indisponível</option>
            </select>
        </div>


        <div class="form-group">
            <label><b>Imagem</b> do produto: </label><br>
            <input multiple type="file" name="imagem[]">
        </div>
        <h5 style="font-size:12px;">As imagens são obrigatórias.</h5>
        <h5 style="font-size:12px;">Use apenas arquivos com até 7.2 MB</h5>

        <input type="submit" value="Cadastrar" name="cadastrar" class="btn btn-light">


    </form>

</div>