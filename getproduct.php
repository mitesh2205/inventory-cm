<?php 
include_once('database/connectdb.php');

$id = $_GET["id"];

$select = $inventory->prepare("select * from products where  id = :ppid");
$select->bindParam(":ppid",$id);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$response = $row;

header('Content-Type: application/json');

echo json_encode($response);
?>