<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Mesa de Examen</title>
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
            max-width: 500px;
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
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 40px;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .mesa-options {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .mesa-card {
            flex: 1;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .mesa-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }
        
        .mesa-card.selected {
            border-color: #28a745;
            background: #f0fff4;
        }
        
        .mesa-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .mesa-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .mesa-desc {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
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
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
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

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            display: none;
        }
        
        input[type="radio"] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-header">📝</div>
        
        <h1>Inscripción a Mesa de Examen</h1>
        <p class="subtitle">
            Seleccione el llamado al que desea inscribirse
        </p>

        <div class="error-msg" id="errorMsg">
            Por favor seleccione un llamado de examen
        </div>

        <form action="{{ route('examenes.procesar-mesa') }}" method="POST" id="mesaForm">
            @csrf
            <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
            
            <div class="mesa-options">
                <label class="mesa-card" id="mesa1Card">
                    <input type="radio" name="mesa" value="1">
                    <div class="mesa-icon">🥇</div>
                    <div class="mesa-title">1° Llamado</div>
                    <div class="mesa-desc">Primera oportunidad de rendir la materia</div>
                </label>
                
                <label class="mesa-card" id="mesa2Card">
                    <input type="radio" name="mesa" value="2">
                    <div class="mesa-icon">🥈</div>
                    <div class="mesa-title">2° Llamado</div>
                    <div class="mesa-desc">Segunda oportunidad para rendir</div>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" id="btnContinuar" disabled>
                Continuar a Materias →
            </button>
        </form>

        <a href="/examenes/identificar" class="btn btn-secondary">
            ← Volver al Inicio
        </a>
    </div>

    <script>
        const mesa1Card = document.getElementById('mesa1Card');
        const mesa2Card = document.getElementById('mesa2Card');
        const btnContinuar = document.getElementById('btnContinuar');
        const errorMsg = document.getElementById('errorMsg');

        function seleccionarMesa(card, value) {
            // Remover selección anterior
            document.querySelectorAll('.mesa-card').forEach(c => {
                c.classList.remove('selected');
            });
            
            // Seleccionar nueva mesa
            card.classList.add('selected');
            
            // Marcar radio button
            const radio = card.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Habilitar botón y ocultar error
            btnContinuar.disabled = false;
            errorMsg.style.display = 'none';
        }

        mesa1Card.addEventListener('click', () => seleccionarMesa(mesa1Card, 1));
        mesa2Card.addEventListener('click', () => seleccionarMesa(mesa2Card, 2));

        // Validar antes de enviar
        document.getElementById('mesaForm').addEventListener('submit', function(e) {
            const mesaSeleccionada = document.querySelector('input[name="mesa"]:checked');
            
            if (!mesaSeleccionada) {
                e.preventDefault();
                errorMsg.style.display = 'block';
                btnContinuar.disabled = true;
            }
        });
    </script>
</body>
</html>