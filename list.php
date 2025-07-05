<?php
include('db.php');

$sql = "SELECT * FROM tb_new ORDER BY indate DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>NY Travel Bookmark 一覧</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(to bottom, #1a1a2e, #16213e);
      color: #fff;
      margin: 0;
      padding: 40px 20px;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      text-align: center;
      font-size: 2.5em;
      color: #f0c929;
      margin-bottom: 1.5em;
    }

    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .card {
      background: rgba(255,255,255,0.05);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .card h2 {
      font-size: 1.2em;
      margin: 0 0 10px;
      color: #f0c929;
    }

    .card p {
      margin: 4px 0;
      font-size: 0.95em;
    }

    .card .actions {
      margin-top: 10px;
    }

    .card .actions a {
      color: #f0c929;
      margin-right: 15px;
      text-decoration: none;
      font-weight: bold;
      font-size: 0.9em;
    }

    .card .actions a:hover {
      text-decoration: underline;
    }

    .btn-back {
      display: block;
      width: fit-content;
      margin: 40px auto 0;
      background: #f0c929;
      color: #1a1a2e;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-back:hover {
      background: #fff;
      color: #1a1a2e;
    }
  </style>
</head>
<body>

  <h1>📍 NY Travel Bookmarks</h1>

  <div class="card-container">
    <?php foreach ($results as $row): ?>
      <div class="card">
        <h2><?= htmlspecialchars($row['name']) ?>（<?= htmlspecialchars($row['status']) ?>）</h2>
        <p>📍 緯度: <?= htmlspecialchars($row['lat']) ?> / 経度: <?= htmlspecialchars($row['lng']) ?></p>
        <p>📝 <?= nl2br(htmlspecialchars($row['comment'])) ?></p>
        <p style="font-size: 0.8em; color: #ccc;">🕒 <?= htmlspecialchars($row['indate']) ?></p>
        <div class="actions">
          <a href="edit.php?id=<?= $row['id'] ?>">✏️編集</a>
          <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('本当に削除しますか？');">🗑️削除</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <a href="index.php" class="btn-back">← 地図に戻る</a>

</body>
</html>