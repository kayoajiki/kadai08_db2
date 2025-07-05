<?php
include('db.php');

$id = $_POST['id'];
$name = $_POST['name'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$status = $_POST['status'];
$comment = $_POST['comment'];

$sql = "UPDATE tb_new SET name=?, lat=?, lng=?, status=?, comment=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $lat, $lng, $status, $comment, $id]);

header("Location: list.php");
exit();
