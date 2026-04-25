// AJAX ile canli arama
function canliArama() {
    var kelime = document.getElementById("arama").value;

    if (kelime.length < 2) {
        document.getElementById("arama-sonuc").innerHTML = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/ajax_arama.php?q=" + kelime, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("arama-sonuc").innerHTML = xhr.responseText;
        }
    };

    xhr.send();
}

// AJAX ile yorum gonderme
function yorumGonder(dataset_id) {
    var yorum = document.getElementById("comment_text").value;

    if (yorum == "") {
        alert("Yorum bos olamaz!");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/ajax_yorum.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("yorumlar").innerHTML = xhr.responseText;
            document.getElementById("comment_text").value = "";
        }
    };

    xhr.send("dataset_id=" + dataset_id + "&yorum=" + encodeURIComponent(yorum));
}
