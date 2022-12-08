<?php
// Incluimos la conexión a la base de datos
include("connections/conn_localhost.php");
include("includes/utils.php");

// Lo primero que vamos a validar es si el forumulario ha sido enviado o no
if(isset($_POST['userAddSent'])) {
  // Vamos a validar que no existan cajas vacias
  foreach($_POST as $calzon => $caca) {
    if($caca == '') $error[] = "The $calzon field is required";
  }

  // Validamos que los passwords coincidan
  if($_POST['password'] != $_POST['password2']) $error[] = "La contraseña no coincide";

  // Si estamos libres de errores, continuamos a insertar el registro en la BD
  if(!isset($error)) {
    // Preparamos el query de insercion
    $queryInsertUser = sprintf("INSERT INTO usuario (nombre, apellido, correo, contraseña, rol) VALUES ('%s', '%s', '%s', '%s', '%s')",
        mysqli_real_escape_string($conn_localhost, trim($_POST['name'])),
        mysqli_real_escape_string($conn_localhost, trim($_POST['lastname'])),
        mysqli_real_escape_string($conn_localhost, trim($_POST['email'])),
        mysqli_real_escape_string($conn_localhost, trim($_POST['password'])),
        mysqli_real_escape_string($conn_localhost, trim($_POST['rol']))
    );

    // Ejecutamos el query
    mysqli_query($conn_localhost, $queryInsertUser) or trigger_error("El query de inserción de usuarios falló");

    // Redireccionamos al usuario al Panel de Control
    header("Location: materias.php?insertUser=true");
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
    <div class="col-md-5">
  </div>
<div class = "container py-5 px-5 bg-primary" id="content" class="txt_content">
  <h2 class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold">Nuevo usuario</h2>
  <p class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Use el formulario para registrarte</p>

  <?php if(isset($error)) printMsg($error, "error"); ?>
  <?php if(isset($_GET['auth'])) printMsg("You must login first", "announce"); ?>
  <?php if(isset($_GET['loggedOff'])) printMsg("Your session has been closed.", "exito"); ?>

  <form action="registro.php" method="post">
    
    <table cellpadding ="2">
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="name">Nombre:* </label></td>
        <td><input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']?>" ></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="lastname">Apellidos:*</label></td>
        <td><input type="text" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']?>"></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="email">Email:* </label></td>
        <td><input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>"></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="password">Contraseña:*</label></td>
        <td><input type="password" name="password"></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="password2">Confirmar Contraseña:*</label></td>
        <td><input type="password" name="password2"></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold"> ¿Que eres? </td>
        <td>
          <select name = "rol">
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>  
            <!-- Si es admin -->
            <?php
              // Inicializamos la sesion o la retomamos
              if(!isset($_SESSION)) {
                session_start();
                print_r($_SESSION);
              }
              // Este documento es solo para administradores, evaluamos el rol del usuario para determinar "si no es admin", en ese caso lo pateamos cordialmente
              if($_SESSION['userRole'] == "admin"){
                echo '<option value="admin">Administrador</option>';
              }
            ?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" value="Registrate" name="userAddSent"></td>
      </tr>
    </table>

  </form>
</div> 
    <div class="container bg-white pt-1 pb-5">
    <div class="row blog-item px-3 pb-5">
    <div class="col-md-5">
    </div>
    </div>
    </div>
</div>
    </body>
<?php include("includes/footer.php"); ?>
</html>
