<!DOCTYPE html>
<html>
<head>
    <title>Materias del Alumno</title>
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
        .materias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .materia-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .materia-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .materia-card.selected {
            border-color: #28a745;
            background: #f8fff9;
        }
        .materia-nombre {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .materia-estado {
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .estado-aprobada {
            background: #d4edda;
            color: #155724;
        }
        .estado-regular {
            background: #fff3cd;
            color: #856404;
        }
        .estado-pendiente {
            background: #f8d7da;
            color: #721c24;
        }
        .correlativas-info {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        .correlativas-cumplidas {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .correlativas-faltantes {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .correlativas-list {
            margin-top: 8px;
        }
        .correlativa-item {
            padding: 5px 0;
            display: flex;
            align-items: center;
        }
        .correlativa-cumplida::before {
            content: "✅ ";
            margin-right: 8px;
        }
        .correlativa-faltante::before {
            content: "❌ ";
            margin-right: 8px;
        }
        .btn-inscribir {
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-inscribir:hover:not(:disabled) {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-inscribir:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .mesa-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #17a2b8;
        }
        .seleccion-multiple-info {
            background: #e7f3ff;
            color: #004085;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        .materias-seleccionadas-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        .materias-seleccionadas-list li {
            margin: 5px 0;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Inscripción a Mesa de Examen</h1>

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="alumno-info">
        <h2>Alumno: {{ $alumno->nombre }} {{ $alumno->apellido }}</h2>
        <p><strong>DNI:</strong> {{ $alumno->dni }}</p>
        <p><strong>Carrera:</strong> {{ $carrera ? $carrera->nombre : 'Sin carrera asignada' }}</p>
        
        @if(session('mesa_seleccionada'))
            <div class="mesa-info">
                <strong>📝 Mesa Seleccionada:</strong> 
                {{ session('mesa_seleccionada') == 1 ? '1° Llamado' : '2° Llamado' }}
            </div>
        @endif
        
        <div style="margin-top: 10px;">
            <a href="{{ route('examenes.inscripciones', ['idAlumno' => $alumno->id]) }}" 
               style="display: inline-block; background: #17a2b8; color: white; padding: 8px 16px; 
                      border-radius: 5px; text-decoration: none; font-weight: bold; border: 1px solid #138496;
                      font-size: 14px; transition: all 0.3s; margin-right: 10px;">
                📋 Ver Mis Inscripciones
            </a>
            <a href="{{ route('examenes.seleccionar-mesa', ['idAlumno' => $alumno->id]) }}" 
               style="display: inline-block; background: #ffc107; color: #000; padding: 8px 16px; 
                      border-radius: 5px; text-decoration: none; font-weight: bold; border: 1px solid #e0a800;
                      font-size: 14px; transition: all 0.3s; margin-right: 10px;">
                🔄 Cambiar Mesa
            </a>
            <a href="{{ route('correlativas.index') }}" 
               style="display: inline-block; background: #6f42c1; color: white; padding: 8px 16px; 
                      border-radius: 5px; text-decoration: none; font-weight: bold; border: 1px solid #5a3790;
                      font-size: 14px; transition: all 0.3s;">
                ⚙️ Crear Correlativas
            </a>
        </div>
    </div>

    @if($materias->isEmpty())
        <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; text-align: center;">
            <p><strong>No hay materias disponibles para rendir.</strong></p>
            <p>Todas las materias de tu carrera ya están aprobadas o no hay materias registradas.</p>
        </div>
    @else

        <h3>Seleccione las materias para ver correlativas e inscribirse:</h3>
        
        <div class="materias-grid" id="materiasGrid">
            @foreach ($materias as $materia)
                @php
                    $esRegular = in_array($materia->id, $materiasRegulares);
                    $esAprobada = in_array($materia->id, $materiasAprobadas);
                @endphp
                
                <div class="materia-card" data-materia-id="{{ $materia->id }}">
                    <div class="materia-nombre">{{ $materia->nombre }}</div>
                    
                    @if($esAprobada)
                        <span class="materia-estado estado-aprobada">✅ Aprobada</span>
                    @elseif($esRegular)
                        <span class="materia-estado estado-regular">📝 Regular</span>
                    @else
                        <span class="materia-estado estado-pendiente">⏳ Pendiente</span>
                    @endif

                    <div id="correlativas-{{ $materia->id }}" class="correlativas-info"></div>
                </div>
            @endforeach
        </div>

        <!-- Formulario de inscripción (sale solo y si alguien selecciona una materia)-->
        <div id="formInscripcion" style="display: none; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3>📋 Confirmar Inscripción</h3>
            <div id="resumenInscripcion"></div>
            
            <form action="{{ route('examenes.inscribir') }}" method="POST" id="formInscripcionMultiple">
                @csrf
                <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
                <input type="hidden" name="mesa" value="{{ session('mesa_seleccionada', 1) }}">
                
                <div id="materiasSeleccionadas"></div>
                
                <button type="submit" class="btn-inscribir" id="btnInscribir">
                    ✅ Confirmar Inscripción a Mesa
                </button>
            </form>
        </div>
    @endif

    <script>
        const alumnoId = {{ $alumno->id }};
        let materiasSeleccionadas = new Set(); 
        let correlativasInfo = new Map(); 

        document.querySelectorAll('.materia-card').forEach(card => {
            card.addEventListener('click', function() {
                const materiaId = this.getAttribute('data-materia-id');
                const materiaNombre = this.querySelector('.materia-nombre').textContent;
                
                if (materiasSeleccionadas.has(materiaId)) {

                    this.classList.remove('selected');
                    materiasSeleccionadas.delete(materiaId);
                } else {
                    this.classList.add('selected');
                    materiasSeleccionadas.add(materiaId);
                    
                    if (!correlativasInfo.has(materiaId)) {
                        cargarCorrelativas(materiaId);
                    } else {
        
                        mostrarCorrelativas(materiaId);
                    }
                }
                
      
                actualizarFormularioInscripcion();
            });
        });

        function actualizarFormularioInscripcion() {
            const formInscripcion = document.getElementById('formInscripcion');
            const resumenInscripcion = document.getElementById('resumenInscripcion');
            const materiasContainer = document.getElementById('materiasSeleccionadas');
            const btnInscribir = document.getElementById('btnInscribir');
            
            if (materiasSeleccionadas.size > 0) {
               
                formInscripcion.style.display = 'block';
                
                materiasContainer.innerHTML = '';
                let resumenHTML = '<p><strong>Materias seleccionadas:</strong></p><ul class="materias-seleccionadas-list">';
                
                let todasPuedenRendir = true;
                
                materiasSeleccionadas.forEach(materiaId => {
                    // Agregar input hidden para cada materia
                    materiasContainer.innerHTML += `<input type="hidden" name="materia_id[]" value="${materiaId}">`;
                    
                    // Buscar nombre de la materia
                    const materiaCard = document.querySelector(`[data-materia-id="${materiaId}"]`);
                    const materiaNombre = materiaCard ? materiaCard.querySelector('.materia-nombre').textContent : `Materia ${materiaId}`;
                    
                    // Verificar si puede rendir esta materia
                    const puedeRendir = correlativasInfo.get(materiaId)?.puede_rendir || false;
                    const icono = puedeRendir ? '✅' : '❌';
                    const estado = puedeRendir ? '' : ' (No cumple correlativas)';
                    
                    if (!puedeRendir) {
                        todasPuedenRendir = false;
                    }
                    
                    resumenHTML += `<li>${icono} ${materiaNombre}${estado}</li>`;
                });
                
                resumenHTML += `</ul>`;
                resumenHTML += `<p><strong>Mesa:</strong> {{ session('mesa_seleccionada') == 1 ? '1° Llamado' : '2° Llamado' }}</p>`;
                
                // Mostrar advertencia si hay materias que no pueden rendir
                if (!todasPuedenRendir) {
                    resumenHTML += `
                        <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107;">
                            <strong>⚠️ Advertencia:</strong> Algunas materias no cumplen con las correlativas requeridas y no se inscribirán.
                        </div>
                    `;
                }
                
                resumenInscripcion.innerHTML = resumenHTML;
 
                const alMenosUnaPuedeRendir = Array.from(materiasSeleccionadas).some(materiaId => 
                    correlativasInfo.get(materiaId)?.puede_rendir
                );
                
                btnInscribir.disabled = !alMenosUnaPuedeRendir;
                btnInscribir.textContent = alMenosUnaPuedeRendir 
                    ? `✅ Inscribir ${materiasSeleccionadas.size} Materia(s)` 
                    : '❌ No hay materias válidas para inscribir';
                    
            } else {
                formInscripcion.style.display = 'none';
            }
        }

        async function cargarCorrelativas(materiaId) {
            const contenedor = document.getElementById(`correlativas-${materiaId}`);
            
            try {
                contenedor.innerHTML = '<div style="text-align: center; padding: 10px;">⏳ Cargando correlativas...</div>';
                contenedor.style.display = 'block';

                const response = await fetch(`/examenes/validar/${alumnoId}/${materiaId}`);
                const data = await response.json();

                // Guardar la información de correlativas
                correlativasInfo.set(materiaId, data);
                
                mostrarCorrelativas(materiaId);
                
                actualizarFormularioInscripcion();

            } catch (error) {
                const html = `
                    <div class="correlativas-info correlativas-faltantes">
                        <strong>❌ Error al cargar correlativas</strong>
                        <p>Intente nuevamente</p>
                    </div>
                `;
                contenedor.innerHTML = html;
                console.error('Error:', error);
            }
        }

        function mostrarCorrelativas(materiaId) {
            const contenedor = document.getElementById(`correlativas-${materiaId}`);
            const data = correlativasInfo.get(materiaId);
            
            if (!data) return;
            
            let html = '';
            
            if (data.puede_rendir) {
                html = `
                    <div class="correlativas-info correlativas-cumplidas">
                        <strong>✅ Puede inscribirse</strong>
                        <div class="correlativas-list">
                            <div class="correlativa-item correlativa-cumplida">
                                Todas las correlativas están cumplidas
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html = `
                    <div class="correlativas-info correlativas-faltantes">
                        <strong>❌ No puede inscribirse</strong>
                        <div class="correlativas-list">
                            <div class="correlativa-item correlativa-faltante">
                                ${data.mensaje}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            contenedor.innerHTML = html;
            contenedor.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const primeraMateria = document.querySelector('.materia-card');
            if (primeraMateria) {
                primeraMateria.click();
            }
        });
    </script>
</body>
</html>