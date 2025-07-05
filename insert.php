<?php
// JSON形式で返すためのヘッダー
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB接続ファイル読み込み（必要に応じてパス調整）
include("db.php");

// POSTデータの取得（未設定の場合は空に）
$name = $_POST["name"] ?? '';
$lat = $_POST["lat"] ?? '';
$lng = $_POST["lng"] ?? '';
$status = $_POST["status"] ?? '';
$comment = $_POST["comment"] ?? '';

// バリデーション（空チェックなど。最低限の例）
if (empty($name) || empty($lat) || empty($lng)) {
  echo json_encode(["success" => false, "error" => "必須項目が未入力です"]);
  exit;
}

// SQL文の準備
$sql = "INSERT INTO tb_new (name, lat, lng, status, comment) 
        VALUES (:name, :lat, :lng, :status, :comment)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
$stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
$stmt->bindValue(':status', $status, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

// SQL実行と判定
$status = $stmt->execute();

if ($status) {
  echo json_encode(["success" => true]);
} else {
  // エラー内容をデバッグ用に含める（公開時は注意）
  echo json_encode(["success" => false, "error" => $stmt->errorInfo()]);
}
exit;