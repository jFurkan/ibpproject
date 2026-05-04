// Kayit formu
function kayitDogrula() {
    var kullanici = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var sifre = document.getElementById("password").value;
    var sifre2 = document.getElementById("password2").value;

    if (kullanici == "") { alert("Kullanici adi bos olamaz!"); return false; }
    if (kullanici.length < 3) { alert("En az 3 karakter!"); return false; }
    if (email.indexOf("@") == -1) { alert("Gecerli email girin!"); return false; }
    if (sifre.length < 6) { alert("Sifre en az 6 karakter!"); return false; }
    if (sifre != sifre2) { alert("Sifreler eslesmedi!"); return false; }
    return true;
}

// Giris formu
function girisDogrula() {
    if (document.getElementById("email").value == "") { alert("Email bos!"); return false; }
    if (document.getElementById("password").value == "") { alert("Sifre bos!"); return false; }
    return true;
}

// Upload formu
function uploadDogrula() {
    if (document.getElementById("title").value == "") { alert("Baslik bos olamaz!"); return false; }
    if (document.getElementById("file").value == "") { alert("Dosya secin!"); return false; }
    return true;
}

// AJAX canli arama
function canliArama() {
    var kelime = document.getElementById("arama").value;
    if (kelime.length < 2) {
        document.getElementById("arama-sonuc").innerHTML = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ajax_arama.php?q=" + kelime, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("arama-sonuc").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// AJAX yorum gonderme
function yorumGonder(dataset_id) {
    var yorum = document.getElementById("comment_text").value;
    if (yorum == "") { alert("Yorum bos olamaz!"); return; }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax_yorum.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("yorumlar").innerHTML = xhr.responseText;
            document.getElementById("comment_text").value = "";
        }
    };
    xhr.send("dataset_id=" + dataset_id + "&yorum=" + encodeURIComponent(yorum));
}
