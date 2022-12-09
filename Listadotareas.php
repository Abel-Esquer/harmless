<?php
if(!isset($_SESSION)){
    session_start();
    //Protegemos el documento para que solamente los usuarios que HAN INICIADO sesion puedan visualizarlo
    if(!isset($_SESSION['userId'])) header('Location: login.php?auth=false');
}

//Incluimos la conexion a la base de datos
include("connections/conn_localhost.php");
include("includes/utils.php");

$queryGetTareas = sprintf("SELECT * FROM tarea WHERE idMateria = {$_GET['materiaId']};");

$resQueryGetTareas = mysqli_query($conn_localhost, $queryGetTareas) or trigger_error("Materia query failed");

$tareasData = mysqli_fetch_assoc($resQueryGetTareas);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Tareas</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Free Website Template" name="keywords">
        <meta content="Free Website Template" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:300;400;600;700;800&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="wrapper">

        <?php include("includes/sidebar.php"); ?>

            <div class="content">
                <!-- Navbar Start -->

                <div class="container p-0">
                    <nav class="navbar navbar-expand-lg bg-secondary navbar-dark">
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav m-4">
                                <?php
                                    if ($_SESSION['userRole'] == "profesor" || $_SESSION['userRole'] == "admin") {
                                ?>        
                                        <a href="CrearTarea.php?idMateria=<?php echo $_GET['materiaId'];?>" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Crear tarea </a>
                                <?php        
                                    }else{
                                ?>        
                                        <a href="" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold">Salir de la clase</a>
                                <?php        
                                    }
                                ?>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Navbar End -->
                
                <!-- Carousel Start -->

                <!-- Carousel End -->
                
                
                <!-- Blog List Start -->
                
                <div class="container bg-white pt-5">
                    <div class="row blog-item px-3 pb-5">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-7">
                            <?php if(isset($_GET['insertTarea'])) printMsg("La tarea se agrego correctamente"); ?>
                            <tbody>
                                <?php
                                do{
                                ?>
                                    <td>
                                        <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">Tarea: <?php echo $tareasData['titulo']; ?></h3>
                                        <div class="d-flex mb-3">
                                            <small class="mr-2 text-muted"><i class="fa fa-calendar-alt"></i> <?php echo $tareasData['fechaEntrega']; ?> (fecha de entrega)</small>
                                        </div>
                                        <p><?php echo $tareasData['descripcion']; ?></p>
                                        <a class="btn btn-link p-0" href="Entregas.php?usuarioId=<?php echo $_SESSION['userId']?>&tareaId=<?php echo $tareasData['idTarea']?>&usuarioRol=<?php echo $_SESSION['userRole']?>"> Ir a la tarea <i class="fa fa-angle-right"></i></a>
                                    </td>
                                <?php    
                                } while($tareasData = mysqli_fetch_assoc($resQueryGetTareas));    
                                ?>
                            <tbody>
                        </div>
                    </div>
                </div>
                <!-- Blog List End -->
                <!-- Subscribe Start -->
                <!-- Subscribe End -->
                <!-- Blog List Start -->
                <!-- Blog List End -->
                <!-- Footer Start -->
                <?php include("includes/footer.php"); ?>
                <!-- Footer End -->
                </div>
            </div>
        </div>
        
        
        <!-- Back to Top -->
        <a href="#" class="back-to-top"><i class="fa fa-angle-double-up"></i></a>
        
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>

        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>
</html>
