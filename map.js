let map;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 40.712776, lng: -74.005974 }, // NY
    zoom: 12,
  });

  map.addListener("click", (e) => {
    document.getElementById("lat").value = e.latLng.lat();
    document.getElementById("lng").value = e.latLng.lng();
  });

  fetch("select.php")
    .then(res => res.json())
    .then(data => {
      data.forEach(d => {
        const marker = new google.maps.Marker({
          position: { lat: parseFloat(d.lat), lng: parseFloat(d.lng) },
          map: map,
          label: d.status === "行った" ? "✔️" : "⭐",
        });

        const info = new google.maps.InfoWindow({
          content: `<strong>${d.name}</strong><br>${d.status}<br>${d.comment}`
        });

        marker.addListener("click", () => {
          info.open(map, marker);
        });
      });
    });

  // 🔍 検索ボックス機能の追加
  let searchMarker;

  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();
    if (places.length === 0) return;

    const place = places[0];
    if (!place.geometry || !place.geometry.location) return;

    if (searchMarker) {
      searchMarker.setMap(null);
    }

    searchMarker = new google.maps.Marker({
      map,
      position: place.geometry.location,
      title: place.name
    });

    map.setCenter(place.geometry.location);
    map.setZoom(15);
  });
}
