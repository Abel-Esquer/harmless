<?php
if(!isset($_SESSION)){
    session_start();
    //Protegemos el documento para que solamente los usuarios que HAN INICIADO sesion puedan visualizarlo
    if(!isset($_SESSION['userId'])) header('Location: login.php?auth=false');
}

//Incluimos la conexion a la base de datos
include("connections/conn_localhost.php");
include("includes/utils.php");

if($_SESSION['userRole'] == "admin"){
    $queryGetMaterias = sprintf("SELECT m.idMateria, m.nombre as materia, m.codigo, m.descripcion, concat(p.nombre,' ',p.apellido) as profesor
    FROM materia m INNER JOIN usuario p ON m.idProfesor = p.idUsuario");
}

if($_SESSION['userRole'] == "profesor"){
    $queryGetMaterias = sprintf("SELECT m.idMateria, m.nombre as materia, m.codigo, m.descripcion, concat(p.nombre,' ',p.apellido) as profesor
        FROM materia m INNER JOIN usuario p ON m.idProfesor = p.idUsuario WHERE p.idUsuario = {$_SESSION['userId']}");
}

if($_SESSION['userRole'] == "alumno"){
    $queryGetMaterias = sprintf("SELECT m.idMateria, m.nombre as materia, m.codigo, m.descripcion, concat(p.nombre,' ',p.apellido) as profesor
        FROM alumno_clase al INNER JOIN materia m ON al.idMateria = m.idMateria INNER JOIN usuario p ON m.idProfesor = p.idUsuario
        WHERE idMiembros = {$_SESSION['userId']};");
}

// Ejecutamos el query
$resQueryGetMaterias = mysqli_query($conn_localhost, $queryGetMaterias) or trigger_error("Materia query failed");

//Obtenemos el numero de resultador del recordset
$totalMaterias = mysqli_num_rows($resQueryGetMaterias);

// Hacemos un fetch del recordset
$materiasData = mysqli_fetch_assoc($resQueryGetMaterias);

if(isset($_POST['materiaDeleteSent'])) {

    // Preparamos la consulta para guardar el registro en la BD
    $queryDelete = sprintf("DELETE FROM materia WHERE idMateria = %d",
      mysqli_real_escape_string($conn_localhost, trim($_POST['materiaId']))
    );

    // Ejecutamos el query
    $resQueryMateriaDelete = mysqli_query($conn_localhost, $queryDelete) or trigger_error("El query de eliminar materia falló");

    // Evaluamos el resultado de la ejecución del query
    if($resQueryMateriaDelete) {
      header("Location: materias.php?deletedMateria=true");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Materias</title>
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
                                <!-- Si es alumno pide codigo si es profe abre formulario de crear materia -->
                                
                                <?php
                                  // Estas opciones son solo para administradores, evaluamos el rol del usuario para determinar "si no es admin", en ese caso lo pateamos cordialmente
                                  if($_SESSION['userRole'] == "admin"){
                                    echo '<a href="ingresarMateria.php" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Agregar materia </a>';
                                    echo '<a href="registro.php" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Crear usuario </a>';
                                    echo '<a href="ListadoUsuarios.php" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Administrar usuarios </a>';
                                  }

                                  if($_SESSION['userRole'] == "alumno"){
                                    echo '<a href="ingresarMateria.php" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Ingresar materia </a>';
                                  }
                                ?>
                            </div>
                            <div class="navbar-nav m-2">
                                <?php if(isset($_SESSION['userId'])) {
                                    echo '<a href="?logOff=true" class="mt-md-1 px-md-3 mb-2 py-2 bg-white font-weight-bold"> Cerrar sesion </a>';
                                }
                                else {
                                  echo '<a href="login.php">Login</a>';
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
                    <div class="row blog-item px-3 pb-4">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-7">
                            <?php if(isset($_GET['insertMateria'])) printMsg("El curso se agrego correctamente"); ?>
                            <?php if(isset($_GET['insertUser'])) printMsg("Usuario creado exitosamente"); ?>
                            <?php if(isset($_GET['deletedUser'])) printMsg("Usuario eliminado exitosamente"); ?>
                            <?php if(isset($_GET['updateUser'])) printMsg("Usuario actualizado exitosamente"); ?>
                            <?php if(isset($_GET['deletedMateria'])) printMsg("Materia eliminada exitosamente"); ?>
                            <tbody>
                                <?php
                                do{
                                ?>
                                    <?php if($totalMaterias != 0){
                                    ?>
                                        <td>
                                            <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">Curso: <?php echo $materiasData['materia']; ?></h3>
                                            <div class="d-flex mb-3">
                                                <small class="mr-2 text-muted"><i class="fa fa-key"></i>Codigo: <?php echo $materiasData['codigo']; ?></small>
                                            </div>
                                            <div class="d-flex mb-3">
                                                <small class="mr-2 text-muted"><i class="fa fa-user"></i>Profesor: <?php echo $materiasData['profesor']; ?></small>
                                            </div>
                                            <p><?php echo $materiasData['descripcion']; ?></p>
                                            <p><a class="btn btn-link p-0" href="Listadotareas.php?materiaId=<?php echo $materiasData['idMateria'];?>"> Ir a la materia <i class="fa fa-angle-right"></i></a>
                                            </p>
                                            <div>
                                                <?php
                                                if($_SESSION['userRole'] == "admin" || $_SESSION['userRole'] == "profesor"){
                                                ?>  
                                                    <form action="materias.php" method="post">
                                                        <input type="hidden" name="materiaId" value="<?php echo $materiasData['idMateria']; ?>">
                                                        <input type="submit" value="Eliminar" name="materiaDeleteSent">    
                                                    </form>   
                                                    <a href="ingresarMateria.php?idMateria=<?php echo $materiasData['idMateria']; ?>"><input type="submit" value="Editar" name="materiaUpdateSent"></a>
                                                <?php      
                                                }
                                                ?>
                                                
                                            </div>
                                        </td>
                                    <?php  
                                    }
                                    ?>
                                <?php    
                                } while($materiasData = mysqli_fetch_assoc($resQueryGetMaterias));    
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
