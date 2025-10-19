<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Correlativas</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
        }
        
        .icon-header {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 40px;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 30px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #444;
            font-weight: 600;
            font-size: 15px;
        }
        
        select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
            cursor: pointer;
        }
        
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            margin-top: 15px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .info-card {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info-card h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-card ul {
            margin-left: 20px;
            color: #555;
            line-height: 1.8;
        }

        .info-card li {
            margin-bottom: 8px;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-header">⚙️</div>
        
        <h1>Gestión de Correlativas</h1>
        <p class="subtitle">
            Configura las materias correlativas para controlar los requisitos de inscripción a exámenes
        </p>

        <div class="info-card">
            <h3>📌 ¿Qué son las correlativas?</h3>
            <ul>
                <li>Son materias que el alumno debe tener <strong>aprobadas</strong> (nota ≥ 7)</li>
                <li>Necesarias para poder rendir otra materia</li>
                <li>Ejemplo: Programación I es correlativa de Programación II</li>
            </ul>
        </div>

        @if(session('error'))
            <div class="error-msg">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORMULARIO CON CARRERAS MANUALES -->
        <form action="{{ route('correlativas.seleccionar') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="carrera">Seleccione la Carrera:</label>
                <select id="carrera" name="carrera_id" required>
                    <option value="">-- Seleccione una carrera --</option>
                    <option value="1">TSDS</option>
                    <option value="2">PEI</option>
                    <option value="3">PEP</option>
                    <option value="4">TST</option>
                    <option value="5">TSM</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Continuar →
            </button>
        </form>

        <a href="/examenes/identificar" class="btn btn-secondary">
            ← Volver a Exámenes
        </a>
    </div>
</body>
</html>