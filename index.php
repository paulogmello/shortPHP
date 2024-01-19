<?php include './shortPHP/config.php' ?>

<?php
$meuBd = new shortPHP('localhost', 'test', 'root', '');
if(count($_POST) > 0){
    $titulo = $_POST['titulo'];
    $valor = $_POST['valor'];
    $categ = $_POST['categ'];
    $desc = $_POST['desc'];
    $sql = "DELETE FROM `tabela` WHERE id = 1";
    $meuBd->excluirDados($sql);
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body class="container">
    <h1>ShortPHP</h1>
    <h5>Um simples framework para conexões de banco de dados + utilitários</h5>

    <form action="" method="post">
        <div class="col-6 mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" class="form-control" name="titulo" id="" aria-describedby="helpId" placeholder="" />
            
        </div>
        <div class="col-6 mb-3">
            <label for="" class="form-label">Valor</label>
            <input type="number" step="0.01" class="form-control" name="valor" id="" aria-describedby="helpId" placeholder="" />
            
        </div>
        <div class="col-6 mb-3">
            <label for="" class="form-label">Categoria</label>
            <input type="number" class="form-control" name="categ" id="" aria-describedby="helpId" placeholder="" />
            
        </div>
        <div class="col-6 mb-3">
            <label for="" class="form-label">Descricao</label>
            <input type="text" class="form-control" name="desc" id="" aria-describedby="helpId" placeholder="" />
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Enviar</button>
        </div>

    </form>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>