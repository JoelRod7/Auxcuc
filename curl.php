<?php
$ch = curl_init();
$headers = array(
    "Authorization: JWT eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo0LCJ1c2VybmFtZSI6InBydWViYTIwMjJAY3VjLmVkdS5jbyIsImV4cCI6MTY0OTQ1MzA1NCwiY29ycmVvIjoicHJ1ZWJhMjAyMkBjdWMuZWR1LmNvIn0.MAoFJE2SBgHvp9BS9fyBmb2gZzD0BHGPiyKoAo_uYAQ",
    "Content-Type: application/json",
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_URL, "http://consultas.cuc.edu.co/api/v1.0/profesores");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);

echo $res;
curl_close($ch);
?>
<?php 
$sql = array();
$data = array();

    $resultado = [
      'error' => false,
      //'mensaje' => 'El alumno ' . escapar($_POST['nombre']) . ' con id '. escapar($_POST['cced']) .' ha sido agregado con éxito'
    ];
  
    $config = include 'config.php';
    $api_pro = json_decode($res, true);
    try {
      $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
      $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
      foreach( $api_pro as $row ) {
        $data [] = [
            "id" => $row['id'],
            "nombre" => $row['nombre'],
            "email" => $row['correo'],
            "telefono" => $row['telefono'],
        ]; 
    }
    
      $consultaSQL = "INSERT INTO profesores (id, nombre, email, telefono) VALUES (:id, :nombre, :email, :telefono)";


      $sentencia = $conexion->prepare($consultaSQL);
      //$sentencia->execute($sql);
      foreach ($data as $row){
        $sentencia->execute($row);
      }
      
    } catch(PDOException $error) {
      $resultado['error'] = true;
      $resultado['mensaje'] = $error->getMessage();
    }
  ?>

<?php include 'templates/header.php'; ?>


<?php include 'templates/footer.php'; ?>