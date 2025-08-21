@extends('layouts.app')
@section('content')
<h1>Alimentar Detalle - {{ $caso->numero_caso }}</h1>
<form method="POST" action="{{ route('detalle.store',$caso) }}">
@csrf
<label>Verificación</label>
<input name="verificacion" value="{{ old('verificacion',$detalle->verificacion ?? '') }}">

<label>Código ECU</label>
<input name="codigo_ecu" value="{{ old('codigo_ecu',$detalle->codigo_ecu ?? '') }}">

<label>Zona / Subzona / Distrito</label>
<input name="zona" value="{{ old('zona',$detalle->zona ?? '') }}">
<input name="subzona" value="{{ old('subzona',$detalle->subzona ?? '') }}">
<input name="distrito" value="{{ old('distrito',$detalle->distrito ?? '') }}">

<label>Circuito / Subcircuito</label>
<input name="circuito" value="{{ old('circuito',$detalle->circuito ?? '') }}">
<input name="subcircuito" value="{{ old('subcircuito',$detalle->subcircuito ?? '') }}">

<label>Espacio / Área</label>
<input name="espacio" value="{{ old('espacio',$detalle->espacio ?? '') }}">
<input name="area" value="{{ old('area',$detalle->area ?? '') }}">

<label>Lugar del hecho</label>
<input name="lugar_hecho" value="{{ old('lugar_hecho',$detalle->lugar_hecho ?? '') }}">

<label>Fecha/Hora del hecho</label>
<input name="fecha_hora" value="{{ old('fecha_hora',$detalle->fecha_hora ?? '') }}">

<label>Coordenadas</label>
<input name="coordenadas" value="{{ old('coordenadas',$detalle->coordenadas ?? '') }}">

<label>¿Asiste Criminalística?</label>
<input name="criminalistica" value="{{ old('criminalistica',$detalle->criminalistica ?? '') }}">

<label>Tipo de arma</label>
<input name="tipo_arma" value="{{ old('tipo_arma',$detalle->tipo_arma ?? '') }}">

<label>¿Indicios? (Sí/No)</label>
<input name="indicios" value="{{ old('indicios',$detalle->indicios ?? '') }}">

<label>Tipo de delito</label>
<input name="tipo_delito" value="{{ old('tipo_delito',$detalle->tipo_delito ?? '') }}">

<label>Motivación</label>
<input name="motivacion" value="{{ old('motivacion',$detalle->motivacion ?? '') }}">

<label>Justificación</label>
<textarea name="justificacion">{{ old('justificacion',$detalle->justificacion ?? '') }}</textarea>

<label>Circunstancias</label>
<textarea name="circunstancias">{{ old('circunstancias',$detalle->circunstancias ?? '') }}</textarea>

<label>Entrevistas</label>
<textarea name="entrevistas">{{ old('entrevistas',$detalle->entrevistas ?? '') }}</textarea>

<label>Actividades</label>
<textarea name="actividades">{{ old('actividades',$detalle->actividades ?? '') }}</textarea>

<label>Reporta</label>
<input name="reporta" value="{{ old('reporta',$detalle->reporta ?? '') }}">

<button>Guardar Detalle</button>
</form>
@endsection
