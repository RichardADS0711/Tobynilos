<?php 
    require 'config/database.php';
    require 'config/config.php';

    $db = new DataBase();
    $conn = $db->getConnection();

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';

    if($id == '' || $token == ''){
        echo 'Error al procesar la peticion';
        exit;
    }else{
        $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

        if($token_tmp == $token){
            $sql = $conn->prepare("SELECT count(id) FROM producto WHERE id=? and stock>0 LIMIT 1");
            $sql->execute([$id]);
            if($sql->fetchColumn()>0){
                $sql = $conn->prepare("SELECT pr.stock, pr.precio, pr.descuento, pr.stock, al.nombre, al.descripcion, al.id_spotify,
                                        al.imgFront, al.imgBack FROM producto pr 
                                        LEFT JOIN album al ON al.id=pr.id_album WHERE pr.id=? and pr.stock>0");
                $sql->execute([$id]);
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                $precio = $row['precio'];
                $stock = $row['stock'];
                $nombre = $row['nombre'];
                $descripcion = $row['descripcion'];
                $descuento = $row['descuento'];
                $precio_desc = $precio - (($precio * $descuento)/100); 
                $id_spotify = $row['id_spotify'];
                $imgFront = $row['imgFront'];
                $imgBack = $row['imgBack'];
                $images = array();

                if(!file_exists($imgFront) || substr($imgFront, -4) != '.jpg'){
                    $imgFront = 'images/no-photo.jpg';
                }
                if(!file_exists($imgBack) || substr($imgBack, -4) != '.jpg'){
                    $imgBack = 'images/no-photo.jpg';
                } 
                $images[1] = $imgBack;
                $images[0] = $imgFront;
            } 
        }else{
            echo 'Error al procesar la peticion';
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

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
            <div class="row ms-0 me-0">
                <div class="col-md-6 order-md-1">
                    <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselImages" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselImages" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-fluid d-block w-100" src="<?php echo $imgFront; ?>" alt="None">
                            </div>
                            <div class="carousel-item">
                                <img class="img-fluid d-block w-100" src="<?php echo $imgBack; ?>" alt="None">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                    
                </div>
                <div class="col-md-6 order-md-2 about text-justify">
                    <h2><?php echo $nombre; ?></h2>
                    <?php if($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 0, '.', '.'); ?></del></p>    
                        <h2>
                            <?php echo MONEDA . number_format($precio_desc, 0, '.', '.'); ?>
                            <small class="text-success"><?php echo $descuento ?>% Descuento</small>
                        </h2>
                    <?php }else{ ?>    
                        <h2><?php echo MONEDA . number_format($precio, 0, '.', '.'); ?></h2>
                    <?php } ?>
                    <p class="lead about text-justify">
                        <?php echo $descripcion; ?>
                    </p>
                    <div class="d-grid gap-3 col-10 mx-auto mt-5">
                        <button class="btn btn-primary" type="button">Contactar al Vendedor</button>
                        <button class="btn btn-outline-primary" type="button" onclick="returnToStock()">Volver a Stock</button>
                    </div>
                </div>
            </div>
            <div class="row ms-0 me-0 mt-5">
                <div class="col-md-12">
                    <iframe class="d-block w-100" src="https://open.spotify.com/embed?uri=spotify:album:<?php echo $id_spotify; ?>" 
                        height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        function returnToStock(){
            location.href = "https://tobynilos.store/stock.php";
        }
    </script>
</body>

</html>