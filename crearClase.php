<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'Clase creada.'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $alumno = [
      "idalumno"   => $_POST['idalumno'],
      "idprofes" => $_POST['idprofes'],
    ];

    $consultaSQL = "INSERT INTO clases (idalumno, idprofes)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($clases)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($clases);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include 'templates/header.php'; ?>

<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Creación de Clases</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="idalumno">Id Estudiante</label>
          <input type="text" name="idalumno" id="idalumno" class="form-control">
        </div>
        <div class="form-group">
          <label for="idprofes">Id Profesor</label>
          <input type="text" name="idprofes" id="idprofes" class="form-control">
        </div>
        <div class="form-group">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
          <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>