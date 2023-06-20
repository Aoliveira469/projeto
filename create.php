<?php
require 'banco.php';

// Acompanha os erros de validação
$nomeClienteErro = null;
$entregaErro = null;
$produtoErro = null;
$enderecoErro = null;
$pagamentoErro = null;


// Processar apenas quando houver uma chamada POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validacao = true;

    if (!empty($_POST['nome_cliente'])) {
        $nomeCliente = $_POST['nome_cliente'];
    } else {
        $nomeClienteErro = 'Por favor digite o nome do cliente!';
        $validacao = false;
    }

    if (!empty($_POST['entrega'])) {
        $entrega = $_POST['entrega'];
    } else {
        $entregaErro = 'Por favor selecione o tipo de entrega!';
        $validacao = false;
    }

    if (!empty($_POST['produto'])) {
        $produto = $_POST['produto'];

        // Buscar o preço do produto no banco de dados
        $pdo = Banco::conectar();
        $sql = 'SELECT preco FROM produto WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$produto]);
        $preco = $stmt->fetchColumn();

        if (!$preco) {
            $produtoErro = 'Produto não encontrado!';
            $validacao = false;
        }
    } else {
        $produtoErro = 'Por favor selecione o produto!';
        $validacao = false;
    }

   

    if (!empty($_POST['pagamento'])) {
        $pagamento = $_POST['pagamento'];
    } else {
        $pagamentoErro = 'Por favor selecione a forma de pagamento!';
        $validacao = false;
    }

    if ($validacao) {
        // Inserindo no Banco de Dados
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO projeto (nome_cliente, entrega, id_produto, endereco, telefone_cliente, pagamento) VALUES ( ?, ?, ?, ?, ?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute([$nomeCliente, $entrega, $produto, $endereco, $telefoneCliente, $pagamento]);
        Banco::desconectar();
        header("Location: index.php");
        exit();
    }
}

// Consultar a lista de produtos
$pdo = Banco::conectar();
$sql = 'SELECT * FROM produto';
$produtos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
Banco::desconectar();
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Adicionar Pedido</title>
</head>

<body>
    <div class="container">
        <div class="span10 offset1">
            <div class="card">
                <div class="card-header">
                    <h3 class="well">Adicionar Pedido</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="create.php" method="post">

                        <div>
                            <label for="entrega">Tipo de entrega</label>
                            <select id="entrega" name="entrega">
                                <option selected disabled value="">Selecione</option>
                                <option value="Entrega">Entrega a domicilio</option>
                                <option value="Retirada">Retirada</option>
                            </select>
                            <?php if (!empty($entregaErro)) : ?>
                                <span class="text-danger"><?php echo $entregaErro; ?></span>
                            <?php endif; ?>
                        </div>
                        <br>

                        <div class="control-group">
                            <label class="control-label" for="nome_cliente">Nome Cliente</label>
                            <div class="controls">
                                <input id="nome_cliente" size="50" class="form-control" name="nome_cliente" type="text" placeholder="Nome cliente" value="<?php echo !empty($nomeCliente) ? $nomeCliente : ''; ?>">
                                <?php if (!empty($nomeClienteErro)) : ?>
                                    <span class="text-danger"><?php echo $nomeClienteErro; ?></span>
                                <?php endif; ?>
                            </div>
                            <br>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="produto">Produto</label>
                            <div class="controls">
                                <select id="produto" name="produto" size="1">
                                    <option selected disabled value="">Selecione</option>
                                    <?php foreach ($produtos as $produto) : ?>
                                        <option value="<?php echo $produto['id']; ?>" data-preco="<?php echo $produto['preco']; ?>"><?php echo $produto['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($produtoErro)) : ?>
                                    <span class="text-danger"><?php echo $produtoErro; ?></span>
                                <?php endif; ?>
                            </div>
                            <br>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="telefone_cliente">Telefone</label>
                            <div class="controls">
                                <input id="telefone_cliente" size="40" class="form-control" name="telefone_cliente" type="text" placeholder="Telefone Cliente" value="<?php echo !empty($telefoneCliente) ? $telefoneCliente : ''; ?>">
                            </div>
                            <br>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="endereco">Endereço para entrega</label>
                            <div class="controls">
                                <input id="endereco" size="35" class="form-control" name="endereco" type="text" placeholder="Digite bairro e numero da casa" value="<?php echo !empty($endereco) ? $endereco : ''; ?>">
                            </div>
                        </div>
                        <br>

                        <label class="control-label">Forma de pagamento</label>
                        <div class="box-select">
                            <input type="radio" id="d" name="pagamento" value="Debito">
                            <label for="d">Débito</label>

                            <input type="radio" id="w" name="pagamento" value="Credito">
                            <label for="w">Crédito</label>

                            <input type="radio" id="o" name="pagamento" value="Dinheiro">
                            <label for="o">Dinheiro</label>

                            <input type="radio" id="j" name="pagamento" value="Pix">
                            <label for="j">Pix</label>
                        </div>

                        <div class="form-actions">
                            <br />
                            <button type="submit" class="btn btn-success">Adicionar</button>
                            <a href="index.php" type="btn" class="btn btn-default">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>



