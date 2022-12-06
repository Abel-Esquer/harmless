<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Schoology2 - Materia</title>
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

  <?php if(isset($error)) printMsg($error, "error"); ?>
  <?php if(isset($_GET['auth'])) printMsg("You must login first", "announce"); ?>
  <?php if(isset($_GET['loggedOff'])) printMsg("Your session has been closed.", "exito"); ?>

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
