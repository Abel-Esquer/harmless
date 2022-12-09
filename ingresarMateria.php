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
  $queryGetMateria = sprintf("SELECT * FROM materia WHERE idMateria = {$_GET['idMateria']};");
  $resQueryGetMateria = mysqli_query($conn_localhost, $queryGetMateria) or trigger_error("Materia query failed");
  $materiaData = mysqli_fetch_assoc($resQueryGetMateria);
  
  $queryGetMiembros = sprintf("SELECT idUsuario, concat(nombre,' ',apellido) as profesor FROM usuario WHERE rol = 'profesor';");
  $resQueryGetMiembros = mysqli_query($conn_localhost, $queryGetMiembros) or trigger_error("Profesor query failed");
  $miembrosData = mysqli_fetch_assoc($resQueryGetMiembros);

  if(isset($_POST['materiaAddSent'])) {

    $queryGetCodigo = sprintf("SELECT * FROM materia WHERE codigo = '%s'",
      mysqli_real_escape_string($conn_localhost, trim($_POST['codigo'])));
    $resQueryGetCodigo = mysqli_query($conn_localhost, $queryGetCodigo) or trigger_error("Materia query failed");
    $codigoData = mysqli_fetch_assoc($resQueryGetCodigo);

    foreach($_POST as $calzon => $caca) {
      if($caca == '') $error[] = "The $calzon field is required";
    }

    if(!isset($_GET['idMateria'])){
      if(!isset($error)) {
        // Preparamos el query de insercion
        $queryUpdateMateria = sprintf("UPDATE materia SET nombre = '%s', descripcion = '%s', idProfesor = %d WHERE idMateria = {$_GET['idMateria']};",
            mysqli_real_escape_string($conn_localhost, trim($_POST['nombre'])),
            mysqli_real_escape_string($conn_localhost, trim($_POST['descripcion'])),
            mysqli_real_escape_string($conn_localhost, trim($_POST['idProfesor']))
          );

        // Ejecutamos el query
        mysqli_query($conn_localhost, $queryUpdatetMateria) or trigger_error("El query de inserción de usuarios falló");

        header("Location: materias.php?updateMateria=true");
      }
    }else if(!isset($error)) {
      $queryInsertMateria = sprintf("INSERT INTO materia (nombre, codigo, descripcion, idProfesor) VALUES ('%s', '%s', '%s', %d)",
          mysqli_real_escape_string($conn_localhost, trim($_POST['nombre'])),
          mysqli_real_escape_string($conn_localhost, trim($_POST['codigo'])),
          mysqli_real_escape_string($conn_localhost, trim($_POST['descripcion'])),
          mysqli_real_escape_string($conn_localhost, trim($_POST['idProfesor']))
      );

       mysqli_query($conn_localhost, $queryInsertMateria) or trigger_error("El query de inserción de materia falló");
      header("Location: materias.php?insertMateria=true");
    } 
  }  
}

if($_SESSION['userRole'] == "alumno"){
  if(isset($_POST['materiaAddSentAlumno'])) {
    foreach($_POST as $calzon => $caca) {
      if($caca == '') $error[] = "The $calzon field is required";
    }

    if(!isset($error)) {
      if($_POST['codigo'] == $codigoData['codigo']){
        $queryInsertMateria = sprintf("INSERT INTO alumno_clase (idMiembros, idMateria) VALUES (%d, %d);",
          mysqli_real_escape_string($conn_localhost, trim($_SESSION['userId'])),
          mysqli_real_escape_string($conn_localhost, trim($codigoData['idMateria']))
      );

       mysqli_query($conn_localhost, $queryInsertMateria) or trigger_error("El query de inserción de materia falló");
      header("Location: materias.php?insertMateria=true");
      }
    }
  }
}    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Registro</title>
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
      <div class="container bg-white pt-5 pb-5">   
        <div class="row blog-item px-3 pb-2">
          <div class="col-md-5"></div>
        <div class = "container py-5 px-5 bg-primary" id="content" class="txt_content">

      <?php
          if ($_SESSION['userRole'] == "admin" || $_SESSION['userRole'] == "profesor") {
      ?>      
            <!-- Si es administrador -->
            <h2 class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold">Crear materia</h2>
            <p class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Use el formulario para crear una nueva clase</p>

            <form action="ingresarMateria.php" method="post">
              <table cellpadding ="3">
                <tr>
                  <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="nombre">Nombre de la clase:* </label></td>
                  <td><input type="text" name="nombre" value="<?php if(isset($_GET['idMateria'])){echo $materiaData['nombre'];}else if(isset($_POST['name'])){ echo $_POST['name'];}?>" ></td>
                </tr>
                <tr>
                  <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="Codigo">Codigo de la clase:*</label></td>
                  <td><input type="text" name="codigo" value="<?php if(isset($_GET['idMateria'])){echo $materiaData['codigo'];}else if(isset($_POST['name'])){ echo $_POST['name'];}?>"></td>
                </tr>
                <tr>
                  <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="Descripcion">Descripcion:* </label></td>
                  <td><input type="text" name="descripcion" value="<?php if(isset($_GET['idMateria'])){echo $materiaData['descripcion'];}else if(isset($_POST['name'])){ echo $_POST['name'];}?>"></td>
                </tr>
                <tr>
                  <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold"> ¿Que profesor da la clase? </td>
                  <td>
                    <select name = "idProfesor">
                    <option value="profesor"> nombre del profesor</option>
                    <?php
                    do{
                    ?>        
                    <option value="<?php echo $miembrosData['idUsuario'];?>"> <?php echo $miembrosData['profesor'];?></option>               
                    <?php    
                    } while($miembrosData = mysqli_fetch_assoc($resQueryGetMiembros));    
                    ?>
                    </select>           
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td><input type="submit" value="Agregar" name="materiaAddSent"></td>
                </tr>
              </table>';  
      <?php            
          }
      ?>
      
      <?php
        if ($_SESSION['userRole'] == "alumno") {
      ?>
          <!-- Si es alumno -->
          <h2 class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold">Entrar a una clase</h2>
          <p class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Use el formulario para entrar a una clase</p>
          <form action="ingresarMateria.php" method="post">
            <table cellpadding ="3">
              <tr>
                <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="codigo">Codigo:* </label></td>
                <td><input type="text" name="codigo" value="<?php if(isset($_POST['codigo'])) echo $_POST['codigo']?>" ></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="submit" value="Agregar" name="materiaAddSentAlumno"></td>
              </tr>
            </table>

          </form>
          </div> 
            <div class="container bg-white pt-1 pb-5">
              <div class="row blog-item px-3 pb-5">
                <div class="col-md-5"></div>
              </div>
            </div>
          </div>
      <?php            
          }
      ?>
      
    </body>
  <?php include("includes/footer.php"); ?>
</html>