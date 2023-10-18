<?php 
    require 'config/database.php';
    require 'config/config.php';
    $db = new DataBase();
    $conn = $db->getConnection();

    $sql = $conn->prepare("SELECT ar.nombre as artista, al.nombre as album, pr.precio, al.id as idAlbum, pr.id as idProducto, 
                            al.imgFront FROM producto pr LEFT JOIN album al ON al.id = pr.id_album 
                            LEFT JOIN artista ar ON ar.id = al.id_artista");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC); 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Tobynilos</title>
</head>

<body>
    <header id="navigationBar">
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark" aria-label="Third navbar example">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Tobynilos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample03">
                    <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="stock.php">Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutUs.php">Acerca de</a>
                        </li>
                    </ul>
                    <form>
                        <input class="form-control" type="text" placeholder="Buscar" aria-label="Search">
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4 ms-0 me-0">
                <?php foreach($result as $row) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php 
                                $imagePath = $row['imgFront'];
                                if(!file_exists($imagePath) || substr($imagePath, -4) != '.jpg'){
                                    $imagePath = "images/no-photo.jpg";
                                }
                            ?>
                            <img class="img-thumbnail" src="<?php echo $imagePath ?>" alt="None">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['artista'] ?></h5>
                                <h6 class="card-title"><?php echo $row['album'] ?></h6>
                                <p class="card-text">$<?php echo number_format($row['precio'], 0, '.', '.'); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="details.php?id=<?php echo $row['idProducto']; ?>&token=<?php echo hash_hmac('sha1', $row['idProducto'], KEY_TOKEN); ?>" class="btn btn-primary">Detalle</a>
                                    </div>
                                    <small class="text-muted">Disponible</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>    
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>