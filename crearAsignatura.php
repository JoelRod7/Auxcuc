<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'La asignatura ' . escapar($_POST['nombre']) . ' ha sido agregada con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $asignatura = [
      "nombre"   => $_POST['nombre'],
      "salon" => $_POST['salon'],
      "horario"    => $_POST['horario'],
      "idprofe"     => $_POST['idprofe'],
    ];

    $consultaSQL = "INSERT INTO asignatura (nombre, salon, horario, idprofe)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($asignatura)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($asignatura);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    //$resultado['mensaje'] = $error->getMessage();
    $resultado['mensaje'] = 'ASIGNATURA NO CREADA, VERIFICAR CAMPOS';
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
      <h2 class="mt-4">Creación de Asignaturas</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control">
        </div>
        <div class="form-group">
          <label for="salon">Salón</label>
          <input type="text" name="salon" id="salon" class="form-control">
        </div>
        <div class="form-group">
            <label for="horario">Horario</label>
            <input type="text" name="horario" id="horario" class="form-control">
          </div>
        <div class="form-group">
            <label for="idprofe">Id Profesor</label>
            <input type="number" name="idprofe" id="idprofe" class="form-control">
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