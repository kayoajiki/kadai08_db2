<?php
include('db.php');

$id = $_GET['id'];

$sql = "DELETE FROM tb_new WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: list.php");
exit();
