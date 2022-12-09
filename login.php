<?php
  // Inicializamos la sesion o la retomamos
  if(!isset($_SESSION)) {
    session_start();
  }

  // Incluimos las utilidades
  include("connections/conn_localhost.php");
  include("includes/utils.php");

  // Evaluamos si el formulario ha sido enviado
  if(isset($_POST["login_sent"])) {
    // Validamos si las cajas están vacias
    foreach($_POST as $calzon => $caca) {
      if($caca == "") $error[] = "The $calzon field is required";
    }

    // Si estamos libres de errores procedemos con la verificación de los datos del usuario
    if(!isset($error)) {
      // Armamos el query para verificar email y password en la BD
      $queryLogin = sprintf("SELECT idUsuario, nombre, apellido, correo, rol FROM usuario WHERE correo = '%s' AND contraseña = '%s'",
          mysqli_real_escape_string($conn_localhost, trim($_POST['email'])),
          mysqli_real_escape_string($conn_localhost, trim($_POST['password']))
      );

      // Ejecutamos el query
      $resQueryLogin = mysqli_query($conn_localhost, $queryLogin) or trigger_error("Login query failed");

      // Determinamos si el login es valido (email y password coincidentes)
      //Contamos el recordset (el resultado esperado para un login valido es 1)
      if(mysqli_num_rows($resQueryLogin)) {
        // Hacemos un fetch del recordset
        $userData = mysqli_fetch_assoc($resQueryLogin);

        // Defninimos variables de sesion en $_SESSION
        $_SESSION['userId'] = $userData['idUsuario'];
        $_SESSION['userFullname'] = $userData['nombre']." ".$userData["apellido"];
        $_SESSION['userEmail'] = $userData['correo'];
        $_SESSION['userRole'] = $userData['rol'];

        // Redireccionamos al usuario al Panel de Control
        header("Location: materias.php");
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Login</title>
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
    <div class="row blog-item px-3 pb-5">
    <div class="col-md-5">
  </div>
<div class = "container py-5 px-5 bg-primary" id="content" class="txt_content">
  <h2 class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Iniciar sesion</h2>
  <p class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Use el formulario para iniciar sesion</p>

  <div>
    <?php if(isset($error)) printMsg($error); ?>
    <?php if(isset($_GET['auth'])) printMsg("Deberas iniciar sesion"); ?>
    <?php if(isset($_GET['loggedOff'])) printMsg("Has cerrado sesion"); ?>
  </div>
  

  <form action="login.php" method="post">
    <table cellpadding ="4">
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="email">Email:</label></td>
        <td class="mb-3 mb-md-0 font-weight-bold" ><input type="text" name="email"></td>
      </tr>
      <tr>
        <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="password">Password:</label></td>
        <td ><input type="password" name="password"></td>
      </tr>
      <tr>
        <td><input type="submit" value="Login" name="login_sent"></td>
        <td></td>
      </tr>
    </table>
  </form>
  <small class="mr-2 text-muted"><a href="registro.php" class="btn btn-primary mt-auto" >¿No tienes cuenta? Registrate</a></small>
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
