<style>
    #loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        z-index: 999;
        transition: opacity 0.5s;
    }

    #loading img {
        width: 100px; /* veya loader boyutu */
        height: 100px; /* veya loader boyutu */
    }
</style>

<div id="loading">
    <img src="https://ajaxcalender.clicksuslabs.com/admin/loader.gif" alt="Yükleniyor..." />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('myForm');
    var customDateInput = document.getElementById('custom-date');
    var customDateInput2 = document.getElementById('custom-date2');
    var loadingElement = document.getElementById('loading');

    if (form && customDateInput && customDateInput2 && loadingElement) {
        form.addEventListener('submit', function(event) {
            // Kontrol et ve gerekirse formu gönderme
            if (customDateInput.value === '' || customDateInput2.value === '') {
                alert('Tarih alanları boş bırakılamaz!');
                event.preventDefault();
            } else {
                // Tarihler seçildiyse loading ekranını aç
                loadingElement.style.display = 'flex';
                loadingElement.style.opacity = '1';
            }
        });
    }

    window.addEventListener('load', function() {
        // Sayfa yüklendiğinde loading ekranını kapat
        loadingElement.style.opacity = '0';
        setTimeout(function() {
            loadingElement.style.display = 'none';
        }, 500); // 500 milisaniye = 0.5 saniye
    });
});

</script>

<div class="footer-main">
	Copyright & Clicks'us , 2023
</div>











  