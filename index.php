<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Página Inicial</title>
</head>

<body>
    <div class="container">

        <br>
        <div class="row">
            <h2><img src="logo.jpg" width="1150px"></h2>
            <p>
                <a href="create.php" class="btn btn-success">Novo pedido</a>
                <a href="figuras.php" class="btn btn-success">Sabores</a>
            </p>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nº do pedido</th>
                        <th scope="col">Data e hora do pedido</th>
                        <th scope="col">Nome do cliente</th>
                        <th scope="col">Produto</th>
                        <th scope="col">Preço R$</th>
                        <th scope="col">Forma de pagamento</th>
                        <th scope="col">Tipo de entrega</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    include 'banco.php';
                    $pdo = Banco::conectar();
                    $sql = 'SELECT p.id, p.dathora, p.pagamento, p.nome_cliente, p.entrega, p.id_produto, p.telefone_cliente, m.nome AS nome_produto, m.preco AS preco_produto FROM projeto p LEFT JOIN produto m ON(p.id_produto = m.id) ORDER BY p.id ASC';

                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td scope="row">' . $row['id'] . '</td>';
                        echo '<td>' . date('d/m/Y H:i:s', strtotime($row['dathora'])) . '</td>';
                        echo '<td>' . $row['nome_cliente'] . '</td>';
                        echo '<td>' . $row['nome_produto'] . '</td>';
                        echo '<td>' . $row['preco_produto'] . '</td>';
                        echo '<td>' . $row['pagamento'] . '</td>';
                        echo '<td>' . $row['entrega'] . '</td>';
                        

                        echo '<td width=250>';
                        echo '<a class="btn btn-primary" href="read.php?id=' . $row['id'] . '">Info</a>';
                        echo ' ';
                        echo '<a class="btn btn-warning" href="update.php?id=' . $row['id'] . '">Editar</a>';
                        echo ' ';
                        echo '<a class="btn btn-danger" href="delete.php?id=' . $row['id'] . '">Excluir</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    Banco::desconectar();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>

