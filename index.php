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

  if (isset($_POST['apellido'])) {
    $consultaSQL = "SELECT * FROM alumnos WHERE apellido LIKE '%" . $_POST['apellido'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM alumnos";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $alumnos = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['apellido']) ? 'Lista de Estudiantes (' . $_POST['apellido'] . ')' : 'Lista de Estudiantes';
$titulo2 = isset($_POST['nombre']) ? 'Lista de Asignaturas (' . $_POST['nombre'] . ')' : 'Lista de Asignaturas';
$titulo3 = isset($_POST['id']) ? 'Lista de Clases (' . $_POST['id'] . ')' : 'Lista de Clases';
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Prog. Académico</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($alumnos && $sentencia->rowCount() > 0) {
            foreach ($alumnos as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["cced"]); ?></td>
                <td><?php echo escapar($fila["nombre"]); ?></td>
                <td><?php echo escapar($fila["apellido"]); ?></td>
                <td><?php echo escapar($fila["email"]); ?></td>
                <td><?php echo escapar($fila["praca"]); ?></td>
                <td>
                  <a href="<?= 'borrar.php?cced=' . escapar($fila["cced"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editar.php?cced=' . escapar($fila["cced"]) ?>">✏️Editar</a>
                </td>
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
      <a href="crear.php"  class="btn btn-primary mt-4">Crear Alumno</a>
      <hr>
    </div>
  </div>
  <?php

  try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion2 = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL2 = "SELECT * FROM asignatura WHERE nombre LIKE '%" . $_POST['nombre'] . "%'";
  } else {
    $consultaSQL2 = "SELECT * FROM asignatura";
  }

  $sentencia2 = $conexion2->prepare($consultaSQL2);
  $sentencia2->execute();

  $asignatura = $sentencia2->fetchAll();

  } catch(PDOException $error) {
  $error= $error->getMessage();
  }
  ?>
  
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo2 ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Salón</th>
            <th>horario</th>
            <th>Id Profesor</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($asignatura && $sentencia2->rowCount() > 0) {
            foreach ($asignatura as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["nombre"]); ?></td>
                <td><?php echo escapar($fila["salon"]); ?></td>
                <td><?php echo escapar($fila["horario"]); ?></td>
                <td><?php echo escapar($fila["idprofe"]); ?></td>
                <td>
                  <a href="<?= 'borrar.php?id=' . escapar($fila["id"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editar.php?id=' . escapar($fila["id"]) ?>">✏️Editar</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
      <a href="crearAsignatura.php"  class="btn btn-primary mt-4">Crear Asignatura</a>
      <a href="tpro.php"  class="btn btn-primary mt-4">Tabla Profesores</a>
      <hr>
    </div>
    <?php

    try {
      $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
      $conexion3 = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

      if (isset($_POST['id'])) {
        $consultaSQL3 = "SELECT * FROM clases WHERE id LIKE '%" . $_POST['id'] . "%'";
      } else {
        $consultaSQL3 = "SELECT * FROM clases";
      }

        $sentencia3 = $conexion3->prepare($consultaSQL3);
        $sentencia3->execute();

        $clases = $sentencia3->fetchAll();

    } catch(PDOException $error) {
      $error= $error->getMessage();
    }
    ?>
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo3 ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Id Alumno</th>
            <th>Id Asignatura</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($clases && $sentencia3->rowCount() > 0) {
            foreach ($clases as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["idalumno"]); ?></td>
                <td><?php echo escapar($fila["idasigna"]); ?></td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
          <a href="crearClase.php"  class="btn btn-primary mt-4">Crear Clase</a>
          <hr>
    </div>
  </div>
  
</div>

<?php include "templates/footer.php"; ?>