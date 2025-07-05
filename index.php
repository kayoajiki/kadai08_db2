<?php
include('db.php');
$sql = "SELECT * FROM tb_new";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- モバイル最適化 -->
  <title>World Travel Bookmark</title>
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
      font-size: 2em;
      color: #f0c929;
      margin: 1em 0 0.5em;
    }

    #map {
      width: 100%;
      height: 60vh; /* モバイルでも見やすく */
    }

    .form-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 20px;
      margin: 20px auto;
      width: 90%;
      max-width: 600px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    form input, form select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      box-sizing: border-box;
    }

    form button {
      background: #f0c929;
      color: #1a1a2e;
      border: none;
      padding: 14px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      font-size: 1.12em;
    }

    form button:hover {
      background: #fff;
      color: #1a1a2e;
    }

    .nav-links {
      text-align: center;
      margin-bottom: 2em;
    }

    .nav-links a {
      color: #f0c929;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }

    .nav-links a:hover {
      text-decoration: underline;
    }

    #pac-input {
      margin: 10px;
      padding: 10px;
      width: 80%;
      max-width: 300px;
      font-size: 1em;
      border: none;
      border-radius: 6px;
      outline: none;
      background-color: #fff;
      color: #000;
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 5;
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 1.5em;
      }
      #pac-input {
        width: 90%;
        left: 5%;
        font-size: 0.95em;
      }
    }
  </style>
</head>
<body>
  <input id="pac-input" type="text" placeholder="スポットを検索">

  <h1>🌏 World Travel Bookmark</h1>
  <div id="map"></div>

  <div class="form-container">
    <form method="post" action="insert.php">
      地名：<input type="text" name="name" required>
      緯度：<input type="text" name="lat" id="lat" readonly required>
      経度：<input type="text" name="lng" id="lng" readonly required>
      区分：
      <select name="status">
        <option value="行きたい">行きたい</option>
        <option value="行った">行った</option>
      </select>
      コメント：<input type="text" name="comment">
      <button type="submit">📍 登録する</button>
    </form>
  </div>

  <div class="nav-links">
    <a href="list.php">📄 一覧を見る</a>
  </div>

  <script>
    function initMap() {
      const ny = { lat: 40.7128, lng: -74.0060 };
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: ny
      });

      map.addListener("click", (e) => {
        document.getElementById("lat").value = e.latLng.lat().toFixed(6);
        document.getElementById("lng").value = e.latLng.lng().toFixed(6);
      });

      const input = document.getElementById("pac-input");
      const searchBox = new google.maps.places.SearchBox(input);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
      });

      searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        const bounds = new google.maps.LatLngBounds();

        places.forEach((place) => {
          if (!place.geometry || !place.geometry.location) return;

          bounds.extend(place.geometry.location);
          map.setCenter(place.geometry.location);
          map.setZoom(15);
        });

        map.fitBounds(bounds);
      });

      // DBのマーカーを表示
      const locations = <?= json_encode($results, JSON_UNESCAPED_UNICODE); ?>;

      locations.forEach((item) => {
        const marker = new google.maps.Marker({
          position: { lat: parseFloat(item.lat), lng: parseFloat(item.lng) },
          map,
          title: item.name
        });

        const infowindow = new google.maps.InfoWindow({
          content: `<strong>${item.name}</strong><br>${item.comment}`
        });

        marker.addListener("click", () => {
          infowindow.open(map, marker);
        });
      });
    }
  </script>

  <!-- Google Maps API 読み込み（Placesライブラリ込み） -->
  <script src="https://maps.googleapis.com/maps/api/js?key=●●●&libraries=places&callback=initMap" async defer></script>
</body>
</html>
