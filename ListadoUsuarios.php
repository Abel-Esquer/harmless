<?php
if(!isset($_SESSION)){
    session_start();
    //Protegemos el documento para que solamente los usuarios que HAN INICIADO sesion puedan visualizarlo
    if(!isset($_SESSION['userId'])) header('Location: login.php?auth=false');
}

//Incluimos la conexion a la base de datos
include("connections/conn_localhost.php");
    
if(isset($_GET['materiaId'])){
    $queryGetMiembros = sprintf("SELECT u.idUsuario, concat(u.nombre,' ',u.apellido) as usuario, u.correo, u.rol FROM alumno_clase 
    INNER JOIN usuario u ON idMiembros = u.idUsuario WHERE idMateria = {$_GET['materiaId']};");
}else{
    $queryGetMiembros = sprintf("SELECT idUsuario, concat(nombre,' ',apellido) as usuario, correo, rol FROM usuario;");
}

$resQueryGetMiembros = mysqli_query($conn_localhost, $queryGetMiembros) or trigger_error("Materia query failed");
$miembrosData = mysqli_fetch_assoc($resQueryGetMiembros);

if(isset($_POST['userDeleteSent'])) {

    // Preparamos la consulta para guardar el registro en la BD
    $queryDelete = sprintf("DELETE FROM usuario WHERE idUsuario = %d",
      mysqli_real_escape_string($conn_localhost, trim($_POST['userId']))
    );

    // Ejecutamos el query
    $resQueryUserDelete = mysqli_query($conn_localhost, $queryDelete) or trigger_error("El query de eliminar usuario falló");

    // Evaluamos el resultado de la ejecución del query
    if($resQueryUserDelete) {
      header("Location: materias.php?deletedUser=true");
    }
}

if(isset($_POST['userMateriaDeleteSent'])) {

    // Preparamos la consulta para guardar el registro en la BD
    $queryDelete = sprintf("DELETE FROM alumno_clase WHERE idMiembros = %d",
      mysqli_real_escape_string($conn_localhost, trim($_POST['userId']))
    );

    // Ejecutamos el query
    $resQueryUserDelete = mysqli_query($conn_localhost, $queryDelete) or trigger_error("El query de eliminar usuario falló");

    // Evaluamos el resultado de la ejecución del query
    if($resQueryUserDelete) {
      header("Location: materias.php?deletedUserMateria=true");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Alumnos en la clase</title>
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
        <div >

            <div class="content">
                <!-- Navbar Start -->

                <div class="container p-0">
                    <nav class="navbar navbar-expand-lg bg-secondary navbar-dark">
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            
                            <div class="navbar-nav m-2">
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Navbar End -->
                
                <!-- Carousel Start -->

                <!-- Carousel End -->
                
                
                <!-- Blog List Start -->
                
                <div class="container bg-white pt-5">
                    <div class="row blog-item px-3 pb-4">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-7">
                        <tbody>
                            <?php
                                do{
                                ?>        
                                   <td>
                                        <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold"><?php echo $miembrosData['usuario']; ?></h3>
                                        <div class="d-flex mb-3">
                                            <small class="mr-2 text-muted px-md-3 mb-2 py-2 "><i class="fa fa-envelope"></i> Correo: <?php echo $miembrosData['correo']; ?> </small>
                                            <!-- Si es admin -->
                                            <small class="mr-2 text-muted px-md-3 mb-2 py-2 "><i class="fa fa-envelope"></i> rol: <?php echo $miembrosData['rol']; ?> </small>
                                        </div>
                                        <div>
                                            <?php
                                            if($_SESSION['userRole'] == "admin"){
                                            ?>   
                                                <form action="ListadoUsuarios.php" method="post">
                                                    <input type="hidden" name="userId" value="<?php echo $miembrosData['idUsuario']; ?>">
                                                    <input type="submit" value="Eliminar" name="userDeleteSent">    
                                                </form> 
                                                <a href="registro.php?idUsuario=<?php echo $miembrosData['idUsuario']; ?>"><input type="submit" value="Editar" name="userUpdateSent"></a>
                                            <?php    
                                            }
                                            ?>

                                            <?php
                                            if($_SESSION['userRole'] == "profesor"){
                                            ?>
                                                <form action="ListadoUsuarios.php" method="post">
                                                    <input type="hidden" name="userId" value="<?php echo $miembrosData['idUsuario']; ?>">
                                                    <input type="submit" value="Expulsar" name="userMateriaDeleteSent">    
                                                </form> 
                                            <?php    
                                            }
                                            ?>
                                        </div>
                                    </td> 
                                <?php    
                                } while($miembrosData = mysqli_fetch_assoc($resQueryGetMiembros));    
                                ?>            
                        </tbody>
                        </div>
                    </div>
                </div>
                
                <!-- Blog List End -->
                <!-- Subscribe Start -->
                <!-- Subscribe End -->
                <!-- Blog List Start -->
                <div class="container bg-white pt-5">
                </div>
                <!-- Blog List End -->
                <!-- Footer Start -->
                 <?php include("includes/footer.php"); ?>
                <!-- Footer End -->
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
