<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL = "SELECT * FROM profesores WHERE nombre LIKE '%" . $_POST['nombre'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM profesores";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $profesores = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['nombre']) ? 'Lista de Profesores (' . $_POST['nombre'] . ')' : 'Lista de Profesores';
?>


<?php include 'templates/header.php'; ?>

<div class="container">
  
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($profesores && $sentencia->rowCount() > 0) {
            foreach ($profesores as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["nombre"]); ?></td>
                <td><?php echo escapar($fila["email"]); ?></td>
                <td><?php echo escapar($fila["telefono"]); ?></td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>


<div class="container"> 
    <div class="row">
        <div class="col-md-12">
            <form method="post">
                <div class="form-group">
                    <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
                </div>
            </form>
            </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>