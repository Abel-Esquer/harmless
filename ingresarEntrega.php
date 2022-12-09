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
        <!-- Si es administrador -->
        <h2 class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold">Hacer entrega</h2>
        <p class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" >Use el formulario para crear una entrega</p>

        <form action="registro.php" method="post">          
          <table cellpadding ="3">
            <tr>
              <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="titulo">Titulo:* </label></td>
              <td><input type="text" name="titulo" value="<?php if(isset($_POST['titulo'])) echo $_POST['titulo']?>" ></td>
            </tr>
            <tr>
              <td class="mb-3 mb-md-0 text-white text-uppercase font-weight-bold" ><label for="contenido">Contenido de su tarea:*</label></td>
              <td><textarea name="contenido" value="<?php if(isset($_POST['contenido'])) echo $_POST['contenido']?>"></textarea></td>
            </tr>
              <td></td>
              <td><input type="submit" value="Entregar" name=""></td>
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