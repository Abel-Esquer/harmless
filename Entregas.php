<?php
if(!isset($_SESSION)){
    session_start();
    //Protegemos el documento para que solamente los usuarios que HAN INICIADO sesion puedan visualizarlo
    if(!isset($_SESSION['userId'])) header('Location: login.php?auth=false');
}

//Incluimos la conexion a la base de datos
include("connections/conn_localhost.php");
    
if($_GET['usuarioRol'] == 'alumno'){
    $queryGetEntregas = sprintf("SELECT t.titulo as tTitulo, t.descripcion as tDescripcion, e.titulo as eTitulo, e.fecha as fechaSubido, e.contenido, concat(a.nombre,' ',a.apellido) as alumno FROM entrega e INNER JOIN tarea t ON e.idTarea = t.idTarea INNER JOIN usuario a ON idAlumno = a.idUsuario WHERE idAlumno = {$_GET['usuarioId']} && e.idTarea = {$_GET['tareaId']};");
}

if($_GET['usuarioRol'] == 'admin'){
    $queryGetEntregas = sprintf("SELECT t.titulo as tTitulo, t.descripcion as tDescripcion, e.titulo as eTitulo, e.fecha as fechaSubido, e.contenido, concat(a.nombre,' ',a.apellido) as alumno FROM entrega e INNER JOIN tarea t ON e.idTarea = t.idTarea INNER JOIN usuario a ON idAlumno = a.idUsuario WHERE e.idTarea = {$_GET['tareaId']}; ");
}

if($_GET['usuarioRol'] == 'profesor'){
    $queryGetEntregas = sprintf("SELECT t.titulo as tTitulo, t.descripcion as tDescripcion, e.titulo as eTitulo, e.fecha as fechaSubido, e.contenido, concat(a.nombre,' ',a.apellido) as alumno, m.idProfesor FROM entrega e INNER JOIN tarea t ON e.idTarea = t.idTarea INNER JOIN materia m ON t.idMateria = m.idMateria INNER JOIN usuario a ON e.idAlumno = a.idUsuario WHERE m.idProfesor = {$_GET['usuarioId']} && e.idTarea = {$_GET['tareaId']};");
}

$resQueryGetEntregas = mysqli_query($conn_localhost, $queryGetEntregas) or trigger_error("Materia query failed");

$entregasData = mysqli_fetch_assoc($resQueryGetEntregas);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Entregas de clase</title>
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
                            <?php
                                if($_SESSION['userRole'] == 'alumno'){
                            ?>
                                    <a href="ingresarEntrega.php" class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">Hacer una entrega</a>
                            <?php
                                }
                            ?>
                              <!-- Si es maestro -->
                            <div class="navbar-nav m-2">
                            </div>
                        </div>
                    </nav>
                </div>

                <!--  -->
                <!--  -->
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
                                <td>
                                    <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">Tarea: <?php echo $entregasData['tTitulo']; ?></h3>
                                    <div class="d-flex mb-3">
                                    </div>
                                    <div>
                                        <p><?php echo $entregasData['tDescripcion']; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">Entregas</h3>
                                </td>
                            </tbody>
                            <?php
                            do{
                            ?>        
                                <tbody>            
                                    <td>
                                        <div class="d-flex mb-3">
                                        </div>
                                        <div>
                                            <h5 href="" class="mt-md-4 px-md-3 mb-2 py-2"><?php echo $entregasData['eTitulo']; ?></h5>
                                            <small class="mr-2 text-muted"> Realizada por: <?php echo $entregasData['alumno']; ?> el dia <?php echo $entregasData['fechaSubido']; ?></small>
                                            <p href="" class="mt-md-4 px-md-3 mb-2 py-2"><?php echo $entregasData['contenido']; ?></p>
                                            <?php
                                                if($_SESSION['userRole'] == "alumno"){
                                                   echo '<a href="" class="mt-md-4 px-md-3 mb-2 py-2" ><i class="fa fa-wrench"></i> Editar entrega</a>';
                                                }
                                            ?>
                                            
                                            <a href="" class="mt-md-4 px-md-3 mb-2 py-2" ><i class="fa fa-times"></i> Eliminar entrega</a>
                                        </div>
                                    </td>
                                </tbody>
                            <?php    
                            } while($entregasData= mysqli_fetch_assoc($resQueryGetEntregas));    
                            ?>                        
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
