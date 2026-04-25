// Kayit formu dogrulama
function kayitDogrula() {
    var kullanici = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var sifre = document.getElementById("password").value;
    var sifre2 = document.getElementById("password2").value;

    if (kullanici == "") {
        alert("Kullanici adi bos olamaz!");
        return false;
    }

    if (kullanici.length < 3) {
        alert("Kullanici adi en az 3 karakter olmali!");
        return false;
    }

    if (email == "") {
        alert("Email bos olamaz!");
        return false;
    }

    if (email.indexOf("@") == -1) {
        alert("Gecerli bir email girin!");
        return false;
    }

    if (sifre == "") {
        alert("Sifre bos olamaz!");
        return false;
    }

    if (sifre.length < 6) {
        alert("Sifre en az 6 karakter olmali!");
        return false;
    }

    if (sifre != sifre2) {
        alert("Sifreler eslesmyor!");
        return false;
    }

    return true;
}

// Giris formu dogrulama
function girisDogrula() {
    var email = document.getElementById("email").value;
    var sifre = document.getElementById("password").value;

    if (email == "") {
        alert("Email bos olamaz!");
        return false;
    }

    if (sifre == "") {
        alert("Sifre bos olamaz!");
        return false;
    }

    return true;
}

// Upload formu dogrulama
function uploadDogrula() {
    var baslik = document.getElementById("title").value;
    var dosya = document.getElementById("file").value;

    if (baslik == "") {
        alert("Baslik bos olamaz!");
        return false;
    }

    if (dosya == "") {
        alert("Lutfen bir dosya secin!");
        return false;
    }

    return true;
}

// Yorum formu dogrulama
function yorumDogrula() {
    var yorum = document.getElementById("comment_text").value;

    if (yorum == "") {
        alert("Yorum bos olamaz!");
        return false;
    }

    if (yorum.length < 5) {
        alert("Yorum cok kisa!");
        return false;
    }

    return true;
}
