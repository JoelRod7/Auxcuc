<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['nombre'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'Asignatura no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $asignatura = [
      "nombre"   => $_POST['nombre'],
      "salon" => $_POST['salon'],
      "horario"    => $_POST['horario'],
      //"id"     => $_POST['id'],
      "idprofe"     => $_POST['idprofe'],
    ];
    
    $consultaSQL = "UPDATE asignatura SET
        nombre = :nombre,
        salon = :salon,
        horario = :horario,
        idprofe = :idprofe,
        updated_at = NOW()
        WHERE id = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($asignatura);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['nombre'];
  $consultaSQL = "SELECT * FROM asignatura WHERE nombre =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $asignatura = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$asignatura) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado la asignatura';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
          Asignatura actualizada correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($asignatura) && $asignatura) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editando la asignatura <?= escapar($asignatura['nombre'])?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?= escapar($asignatura['nombre']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="salon">Salon</label>
            <input type="text" name="salon" id="salon" value="<?= escapar($asignatura['salon']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="horario">Horario</label>
            <input type="text" name="horario" id="horario" value="<?= escapar($asignatura['horario']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="idprofe">Id Profesor</label>
            <input type="number" name="idprofe" id="idprofe" value="<?= escapar($asignatura['idprofe']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "templates/footer.php"; ?>