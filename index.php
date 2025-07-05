<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>NY Travel Bookmark</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(to bottom, #1a1a2e, #16213e);
      color: #fff;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      text-align: center;
      font-size: 2.5em;
      color: #f0c929;
      margin: 1.2em 0 0.5em;
    }

    #map {
      width: 100%;
      height: 500px;
    }

    .form-container {
      background: rgba(255, 255, 255, 0.08);
      padding: 20px 30px;
      margin: 30px auto;
      max-width: 600px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .form-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group label {
      font-size: 0.95em;
      color: #f0c929;
      font-weight: 600;
    }

    .form-group input,
    .form-group select {
      padding: 10px;
      border: none;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.9);
      color: #333;
      font-size: 1em;
      transition: box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      box-shadow: 0 0 0 2px #f0c92988;
    }

    button {
      background: #f0c929;
      color: #1a1a2e;
      border: none;
      padding: 14px;
      border-radius: 6px;
      font-weight: bold;
      font-size: 1em;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #fff;
      color: #1a1a2e;
    }

    .nav-links {
      text-align: center;
      margin-bottom: 2em;
    }

    .nav-links a {
      color: #f0c929;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
    }

    .nav-links a:hover {
      text-decoration: underline;
    }

    #toast {
      display: none;
      position: fixed;
      top: 20px;
      right: 20px;
      background: #f0c929;
      color: #1a1a2e;
      padding: 15px 20px;
      border-radius: 8px;
      font-weight: bold;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      z-index: 9999;
      transition: opacity 0.5s ease-in-out;
    }

    #pac-input {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 300px;
      padding: 10px;
      z-index: 1000;
      border-radius: 4px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <!-- 検索バー -->
  <input id="pac-input" class="controls" type="text" placeholder="NYスポットを検索">

  <h1>🗽 NY Travel Bookmark</h1>
  <div id="map"></div>

  <!-- フォーム -->
  <div class="form-container">
    <form id="bookmarkForm">
      <div class="form-group">
        <label for="name">地名</label>
        <input type="text" name="name" id="name" required>
      </div>

      <div class="form-group">
        <label for="lat">緯度</label>
        <input type="text" name="lat" id="lat" readonly>
      </div>

      <div class="form-group">
        <label for="lng">経度</label>
        <input type="text" name="lng" id="lng" readonly>
      </div>

      <div class="form-group">
        <label for="status">区分</label>
        <select name="status" id="status">
          <option value="行きたい">行きたい</option>
          <option value="行った">行った</option>
        </select>
      </div>

      <div class="form-group">
        <label for="comment">コメント</label>
        <input type="text" name="comment" id="comment">
      </div>

      <button type="submit" id="submitBtn">📍 登録する</button>
    </form>
  </div>

  <!-- 一覧ボタン -->
  <div class="nav-links">
    <a href="list.php">📄 一覧を見る</a>
  </div>

  <!-- トースト通知 -->
  <div id="toast">✔️ 保存しました！</div>

  <!-- JS読み込み -->
  <script src="map.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=●●●&libraries=places&callback=initMap" async defer></script>

  <!-- 登録処理 + トースト -->
  <script>
    document.getElementById("bookmarkForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);
      const button = document.getElementById("submitBtn");

      fetch("insert.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast("✔️ 保存しました！");
          button.innerText = "✔️ 登録完了！";
          button.style.background = "#4caf50";

          setTimeout(() => {
            button.innerText = "📍 登録する";
            button.style.background = "#f0c929";
            form.reset();
          }, 3000);
        } else {
          showToast("⚠️ エラーが発生しました");
        }
      })
      .catch(() => {
        showToast("⚠️ 通信エラー");
      });
    });

    function showToast(msg) {
      const toast = document.getElementById("toast");
      toast.innerText = msg;
      toast.style.display = "block";
      toast.style.opacity = 1;

      setTimeout(() => {
        toast.style.opacity = 0;
        setTimeout(() => toast.style.display = "none", 500);
      }, 3000);
    }
  </script>
</body>
</html>