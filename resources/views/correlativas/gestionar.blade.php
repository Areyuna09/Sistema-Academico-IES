<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Correlativas - {{ $carrera->nombre }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #e0e0e0; }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-primary { background: #667eea; color: white; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .correlativa-item { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 15px; 
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .correlativa-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .correlativa-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 12px; 
            padding-bottom: 12px; 
            border-bottom: 2px solid #f0f0f0; 
        }
        .materia-principal { 
            font-size: 16px; 
            font-weight: 600; 
            color: #333; 
        }
        .correlativas-tags { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 8px; 
        }
        .tag { 
            background: #e8f5e9; 
            color: #2e7d32; 
            padding: 6px 12px; 
            border-radius: 15px; 
            font-size: 13px; 
            font-weight: 500; 
            border: 1px solid #a5d6a7;
        }
        .empty-state { 
            text-align: center; 
            padding: 60px 20px; 
            color: #999; 
        }
        .empty-state-icon { 
            font-size: 48px; 
            margin-bottom: 15px; 
        }
        
        .fade-out {
            animation: fadeOut 0.5s ease forwards;
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>⚙️ Gestión de Correlativas</h1>
                <span style="background: #667eea; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                    {{ $carrera->nombre }}
                </span>
            </div>
            <a href="/correlativas" class="btn btn-secondary">← Cambiar Carrera</a>
        </div>
        

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="alert alert-success" id="successMessage">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Panel Izquierdo: Formulario -->
            <div style="background: #f8f9fa; border-radius: 12px; padding: 25px;">
                <h2>➕ Agregar/Editar Correlativa</h2>
                
                <form action="{{ route('correlativas.guardar') }}" method="POST" style="background: white; padding: 20px; border-radius: 8px;">
                    @csrf
                    
                    <div style="margin-bottom: 15px;">
                        <label for="materia_id">Materia a configurar:</label>
                        <select name="materia_id" id="materia_id" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 6px;">
                            <option value="">-- Seleccione una materia --</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Correlativas (requisitos previos):</label>
                        <div style="max-height: 300px; overflow-y: auto; background: white; border: 2px solid #e0e0e0; border-radius: 6px; padding: 10px;">
                            @foreach($materias as $correlativa)
                                <div style="padding: 10px; margin-bottom: 5px; border-radius: 5px; display: flex; align-items: center;">
                                    <input type="checkbox" name="correlativas[]" value="{{ $correlativa->id }}" id="corr_{{ $correlativa->id }}">
                                    <label for="corr_{{ $correlativa->id }}" style="margin: 0 0 0 10px; cursor: pointer;">
                                        {{ $correlativa->nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success" style="width: 100%;" id="btnGuardar">
                        💾 Guardar Correlativas
                    </button>
                </form>
            </div>

            <!-- Panel Derecho: Lista de Correlativas Configuradas -->
            <div style="background: #f8f9fa; border-radius: 12px; padding: 25px;">
                <h2>📋 Correlativas Configuradas</h2>

                @if(count($correlativasConfiguradas) > 0)
                    <div style="max-height: 600px; overflow-y: auto;" id="correlativasList">
                        @foreach($correlativasConfiguradas as $config)
                            <div class="correlativa-item" id="correlativa-{{ $config['materia']->id }}">
                                <div class="correlativa-header">
                                    <div class="materia-principal">
                                        {{ $config['materia']->nombre }}
                                    </div>
                                    <div>
                                        <form action="{{ route('correlativas.eliminar', $config['materia']->id) }}" method="POST" style="display: inline;" class="form-eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-eliminar">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="correlativas-tags">
                                    @foreach($config['correlativas'] as $correlativa)
                                        <span class="tag">
                                            {{ $correlativa->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📝</div>
                        <p><strong>No hay correlativas configuradas</strong></p>
                        <p>Comienza agregando correlativas en el panel izquierdo</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Script para manejar guardar y eliminar -->
    <script>
        @if(session('success') || session('error'))
            setTimeout(() => {
                window.location.href = '{{ route("correlativas.gestionar", ["carreraId" => $carrera->id]) }}';
            }, 2000);
        @endif

        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('btnGuardar');
            btn.innerHTML = '⏳ Guardando...';
            btn.disabled = true;
        });

        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('.btn-eliminar');
                const item = this.closest('.correlativa-item');
                
                if (confirm('¿Estás seguro de eliminar todas las correlativas de esta materia?')) {
              
                    item.classList.add('fade-out');
                    
                    btn.innerHTML = '⏳ Eliminando...';
                    btn.disabled = true;
    
                    setTimeout(() => {
                        this.submit();
                    }, 500);
                }
            });
        });

        @if(session('success'))
            document.getElementById('materia_id').value = '';
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        @endif
    </script>
</body>
</html>