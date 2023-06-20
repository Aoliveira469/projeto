<?php

require 'banco.php';

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: index.php");
    exit();
}

if (!empty($_POST)) {

    $nomeClienteErro = null;
    $entregaErro = null;
    $enderecoErro = null;
    $telefoneClienteErro = null;

    $nomeCliente = $_POST['nome_cliente'];
    $entrega = $_POST['entrega'];
    $endereco = $_POST['endereco'];
    $telefoneCliente = $_POST['telefone_cliente'];

    // Validação
    $validacao = true;

    if (empty($nomeCliente)) {
        $nomeClienteErro = 'Por favor digite o nome do cliente!';
        $validacao = false;
    }

    if (empty($entrega == "Entrega")) {
        $entregaErro = 'Por favor selecione o tipo de entrega!';
        $validacao = false;
    } elseif ($entrega == "Retirada") {
        $validacao = true;
    }

    if ( $entrega !== "Entrega") {
        $enderecoErro = 'Por favor digite o endereço para entrega!';
        $validacao = false;
    }

    if (empty($telefoneCliente)) {
        $telefoneClienteErro = 'Por favor preencha o campo de telefone!';
        $validacao = false;
    }


    // Atualizar os dados no banco de dados
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE projeto SET nome_cliente = ?, entrega = ?, endereco = ?, telefone_cliente = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($nomeCliente, $entrega, $endereco, $telefoneCliente, $id));
        Banco::desconectar();
        header("Location: index.php");
        exit();
    }
} else {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT p.id, p.nome_cliente, p.entrega, p.id_produto, p.endereco, p.telefone_cliente, m.nome AS nome_produto FROM projeto p LEFT JOIN produto m ON (p.id_produto = m.id) WHERE p.id = ? ORDER BY p.id ASC';
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    $nomeCliente = $data['nome_cliente'];
    $entrega = $data['entrega'];
    $produto = $data['id_produto'];
    $endereco = $data['endereco'];
    $telefoneCliente = $data['telefone_cliente'];
    Banco::desconectar();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- using new bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Atualizar Projeto</title>
</head>

<body>
<div class="container">

    <div class="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Atualizar Projeto </h3>
            </div>
            
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id; ?>" method="post">
                    <div class="form-group">
                        <label class="control-label">Tipo de entrega</label>
                        <select class="form-control" name="entrega">
                            <option disabled selected value="">Selecione</option>
                            <option value="Entrega" <?php if ($entrega == 'Entrega') echo 'selected'; ?>>Entrega a domicilio</option>
                            <option value="Retirada" <?php if ($entrega == 'Retirada') echo 'selected'; ?>>Retirada</option>
                        </select>
                        <?php if (!empty($entregaErro)): ?>
                            <span class="text-danger"><?php echo $entregaErro; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?php echo !empty($nomeClienteErro) ? 'has-error' : ''; ?>">
                        <label class="control-label">Nome Cliente</label>
                        <input class="form-control" name="nome_cliente" type="text" placeholder="Nome cliente"
                               value="<?php echo !empty($nomeCliente) ? $nomeCliente : ''; ?>">
                        <?php if (!empty($nomeClienteErro)): ?>
                            <span class="text-danger"><?php echo $nomeClienteErro; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Endereço para entrega</label>
                        <input class="form-control" name="endereco" type="text" placeholder="Digite bairro e número da casa"
                               value="<?php echo !empty($endereco) ? $endereco : ''; ?>">
                        <?php if (!empty($enderecoErro)): ?>
                            <span class="text-danger"><?php echo $enderecoErro; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?php echo !empty($telefoneClienteErro) ? 'has-error' : ''; ?>">
                        <label class="control-label">Telefone</label>
                        <input class="form-control" name="telefone_cliente" type="text" placeholder="Telefone cliente"
                               value="<?php echo !empty($telefoneCliente) ? $telefoneCliente : ''; ?>">
                        <?php if (!empty($telefoneClienteErro)): ?>
                            <span class="text-danger"><?php echo $telefoneClienteErro; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <br/>
                        <button type="submit" class="btn btn-success">Atualizar</button>
                        <a href="index.php" type="btn" class="btn btn-default">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
