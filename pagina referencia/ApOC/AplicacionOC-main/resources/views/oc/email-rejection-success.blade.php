<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Rechazada</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            padding: 60px 40px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: checkmark 0.6s ease 0.2s forwards;
            opacity: 0;
        }
        
        @keyframes checkmark {
            0% {
                opacity: 0;
                transform: scale(0.5) rotate(-45deg);
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }
        
        h1 {
            font-size: 24px;
            color: #101828;
            margin-bottom: 12px;
        }
        
        p {
            color: #5b6473;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        
        .redirect-info {
            color: #8b92a9;
            font-size: 12px;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">✓</div>
        <h1>Solicitud Rechazada</h1>
        <p>La solicitud OC ha sido rechazada correctamente.</p>
        <p class="redirect-info">Redirigiendo en <span id="countdown">3</span> segundos...</p>
    </div>
    
    <script>
        let count = 3;
        const countdownEl = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            count--;
            countdownEl.textContent = count;
            
            if (count <= 0) {
                clearInterval(interval);
                window.location.href = '{{ route("oc.index") }}';
            }
        }, 1000);
    </script>
</body>
</html>
