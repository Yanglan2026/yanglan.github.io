<?php
$conn = new mysqli("localhost", "root", "", "telefon_sitesi");
$conn->set_charset("utf8mb4");
if ($conn->connect_error) { die("Bağlantı hatası: " . $conn->connect_error); }
$result = $conn->query("SELECT * FROM telefonlar");

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>西安先机电子A1315杨激</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .kart { background: white; width: 320px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding-bottom: 15px; }
        
        /* Slider Alanı */
        .swiper { width: 100%; height: 280px; }
        .swiper-slide img { width: 100%; height: 100%; object-fit: contain; cursor: zoom-in; }
        
        .icerik { padding: 15px; text-align: center; }
        .fiyat { color: #2ecc71; font-size: 24px; font-weight: bold; margin: 10px 0; }
        .bilgi { color: #666; font-size: 14px; margin-bottom: 15px; min-height: 40px; }
        
        /* WeChat Butonu */
        .btn-wechat { 
            display: inline-block; background: #07C160; color: white; padding: 12px 25px; 
            text-decoration: none; border-radius: 30px; font-weight: bold; transition: 0.3s;
        }
        .btn-wechat:hover { background: #06ad56; transform: scale(1.05); }

        /* Zoom Modal */
        #zoomModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:9999; justify-content:center; align-items:center; }
        #zoomModal img { max-width: 95%; max-height: 95%; cursor: zoom-out; }
    </style>
</head>
<body>

<h1 style="text-align:center;">📱 手机销售</h1>

<div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="kart">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php for($i=1; $i<=6; $i++): $f = "foto".$i; if(!empty($row[$f])): ?>
                        <div class="swiper-slide"><img src="img/<?php echo $row[$f]; ?>" onclick="zoomAc(this.src)"></div>
                    <?php endif; endfor; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>

            <div class="icerik">
                <h3><?php echo $row['手机型号']; ?></h3>
                <p class="关于手机"><?php echo $row['关于手机']; ?></p>
                <p class="手机价格"><?php echo number_format($row['手机价格'], 2); ?> 元</p>
                <a href="wechat_qr.php" target="_blank" class="btn-wechat">通过微信联系</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div id="zoomModal" onclick="this.style.display='none'"><img id="zoomImg"></div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    function zoomAc(src) {
        document.getElementById('zoomModal').style.display = 'flex';
        document.getElementById('zoomImg').src = src;
    }
</script>
</body>
</html>