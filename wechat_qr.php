<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>微信联系方式</title>
    <style>
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background-color: #ededed; /* WeChat'in orijinal gri arka planı */
            margin: 0; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
        }
        .qr-container { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            text-align: center; 
            max-width: 350px;
            width: 90%;
        }
        h2 { color: #191919; margin-bottom: 20px; font-size: 20px; }
        .qr-image { 
            width: 250px; 
            height: 250px; 
            border: 1px solid #eee;
            margin-bottom: 20px;
        }
        .info-box {
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
            color: #576b95; /* WeChat mavisi */
        }
        .phone-number {
            font-size: 22px;
            font-weight: bold;
            color: #191919;
            margin: 10px 0;
            display: block;
        }
        .copy-hint { font-size: 13px; color: #888; }
    </style>
</head>
<body>

    <div class="qr-container">
        <h2>扫一扫上面的二维码，加我微信</h2>
        
        <img src="img/wechat_qr.png" alt="WeChat QR" class="qr-image">
        
        <div class="info-box">
            <p style="margin:0; color:#888;">或者通过电话联系我：</p>
            <span class="phone-number">+86 138 9191 2457</span> <p class="copy-hint">微信号 (WeChat ID): <b>Kendi_ID_Buraya</b></p>
        </div>

        <button onclick="window.close()" style="margin-top: 20px; padding: 8px 20px; border: none; background: #ddd; border-radius: 5px; cursor: pointer;">返回</button>
    </div>

</body>
</html>