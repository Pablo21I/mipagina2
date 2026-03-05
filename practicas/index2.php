<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Prácticas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px);
        }
        
        .card-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 60px;
        }
        
        .card-content {
            padding: 20px;
        }
        
        .card-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .card-desc {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .card-link {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .card-link:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Mis Prácticas</h1>
        
        <div class="grid">
            <div class="card">
                <div class="card-image">📄</div>
                <div class="card-content">
                    <div class="card-title">Currículum</div>
                    <p class="card-desc">Mi currículum profesional con experiencia y habilidades</p>
                    <a href="curriculum.html" class="card-link">Ver Currículum</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-image">💻</div>
                <div class="card-content">
                    <div class="card-title">Proyecto 1</div>
                    <p class="card-desc">Descripción de mi primer proyecto práctica</p>
                    <a href="#" class="card-link">Más Información</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-image">🚀</div>
                <div class="card-content">
                    <div class="card-title">Proyecto 2</div>
                    <p class="card-desc">Descripción de mi segundo proyecto práctica</p>
                    <a href="#" class="card-link">Más Información</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>