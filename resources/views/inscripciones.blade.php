<!DOCTYPE html>
<html>
<head>
    <title>Mis Inscripciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }
        .alumno-info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .inscripciones-list {
            margin-top: 20px;
        }
        .inscripcion-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .inscripcion-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .inscripcion-info p {
            margin: 5px 0;
            color: #666;
        }
        .mesa-badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-eliminar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-eliminar:hover {
            background: #c82333;
        }
        .btn-volver {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-volver:hover {
            background: #5a6268;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1>Mis Inscripciones a Mesas de Examen</h1>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="alumno-info">
        <h2>Alumno: {{ $alumno->nombre }} {{ $alumno->apellido }}</h2>
        <p><strong>DNI:</strong> {{ $alumno->dni }}</p>
    </div>

    @if($inscripciones->isEmpty())
        <div class="empty-state">
            <h3>📝 No tienes inscripciones activas</h3>
            <p>No te has inscrito a ninguna mesa de examen todavía.</p>
            <a href="{{ route('materias', ['idAlumno' => $alumno->id]) }}" class="btn-volver">
                ← Inscribirse a Materias
            </a>
        </div>
    @else
        <div class="inscripciones-list">
            @foreach($inscripciones as $inscripcion)
            <div class="inscripcion-card">
                <div class="inscripcion-info">
                    <h3>{{ $inscripcion->materia_nombre }}</h3>
                    <p><strong>Fecha de inscripción:</strong> {{ \Carbon\Carbon::parse($inscripcion->fecha)->format('d/m/Y H:i') }}</p>
                    <p><strong>Estado:</strong> {{ $inscripcion->estado ?? 'Inscripto' }}</p>
                    <span class="mesa-badge">Mesa {{ $inscripcion->mesa }}° Llamado</span>
                </div>
                <div class="inscripcion-actions">
                    <form action="{{ route('examenes.eliminar-inscripcion', ['mesa' => $inscripcion->mesa, 'id' => $inscripcion->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta inscripción?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <a href="{{ route('materias', ['idAlumno' => $alumno->id]) }}" class="btn-volver">
            ← Volver a Materias
        </a>
    @endif
</body>
</html>