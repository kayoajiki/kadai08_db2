<?php
include('db.php');

$id = $_GET['id'];
$sql = "SELECT * FROM tb_new WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$row = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ブックマーク編集</title>
</head>
<body>
  <h1>✏️ ブックマーク編集</h1>
  <form method="post" action="update.php">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    地名：<input type="text" name="name" value="<?= $row['name'] ?>"><br>
    緯度：<input type="text" name="lat" value="<?= $row['lat'] ?>"><br>
    経度：<input type="text" name="lng" value="<?= $row['lng'] ?>"><br>
    区分：
    <select name="status">
      <option value="行きたい" <?= $row['status']=='行きたい' ? 'selected' : '' ?>>行きたい</option>
      <option value="行った" <?= $row['status']=='行った' ? 'selected' : '' ?>>行った</option>
    </select><br>
    コメント：<input type="text" name="comment" value="<?= $row['comment'] ?>"><br>
    <button type="submit">💾 保存</button>
  </form>
</body>
</html>
