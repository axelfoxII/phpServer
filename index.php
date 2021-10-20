<?php

include 'conexion.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$json = file_get_contents('php://input'); // RECIBE EL JSON DE ANGULAR

$params = json_decode($json); // DECODIFICA EL JSON Y LO GUARADA EN LA VARIABLE
 


$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD']=='GET') {

    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM contacto WHERE id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql-> execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        
        header("HTTP/1.1 200 OK");
        
        echo json_encode($sql-> fetchAll() );
        exit;
    }else{
	

$sql = $pdo->prepare("SELECT * FROM contacto");
$sql-> execute();
$sql->setFetchMode(PDO::FETCH_ASSOC);

header("HTTP/1.1 200 OK");

echo json_encode($sql-> fetchAll() );
exit;

    }

}

//Insertar registro
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql = "INSERT INTO contacto (nombre, telefono, email) VALUES(:nombre, :telefono, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $params->nombre);
    $stmt->bindValue(':telefono', $params->telefono);
    $stmt->bindValue(':email', $params->email);
    $stmt->execute();
    $idPost = $pdo->lastInsertId(); 
    if($idPost)
    {
        header("HTTP/1.1 200 Ok");
        echo json_encode('El registro se agrego correctamente');
        exit;
    }
}

//Actualizar registro
if($_SERVER['REQUEST_METHOD'] == 'PUT')
{		
    $sql = "UPDATE contacto SET nombre=:nombre, telefono=:telefono, email=:email WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $params->nombre);
    $stmt->bindValue(':telefono', $params->telefono);
    $stmt->bindValue(':email', $params->email);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    echo json_encode('El registro se actualizo correctamente');

    exit;
}
//Eliminar registro
if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $sql = "DELETE FROM contacto WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    echo json_encode('El registro se elimino correctamente');
    exit;
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");

?>