@extends('layouts.app')
@section('title','Seguimiento judicial')

@section('content')
<div class="container" style="max-width:1000px">
  <h2 class="mb-3">Seguimiento judicial — {{ $caso->numero_caso }}</h2>

  <div class="mb-3 small text-muted">
    <strong>ECU:</strong> {{ $contexto['ecu'] ?? '—' }} |
    <strong>Fecha:</strong> {{ $contexto['fecha_hecho'] ?? '—' }} |
    <strong>Delito:</strong> {{ $contexto['tipo_delito'] ?? '—' }} |
    <strong>Motivación:</strong> {{ $contexto['motivacion'] ?? '—' }}
  </div>

  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('segjudicial.store', $caso->id) }}" class="row g-3">
    @csrf

    <div class="col-md-6">
      <label class="form-label">No. causa / No. fiscalía (15 dígitos)</label>
      <input name="no_causa_no_fiscalia" value="{{ old('no_causa_no_fiscalia',$seguimiento->no_causa_no_fiscalia ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Fiscal delegado</label>
      <select name="fiscal_delegado" class="form-select" required>
        <option value="" disabled selected>Seleccione…</option>
        @foreach($cat['fiscales'] as $opt)
          <option value="{{ $opt }}" @selected(old('fiscal_delegado',$seguimiento->fiscal_delegado ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Tipo penal</label>
      <select name="tipo_penal" class="form-select" required>
        <option value="" disabled selected>Seleccione…</option>
        @foreach($cat['tiposPenales'] as $opt)
          <option value="{{ $opt }}" @selected(old('tipo_penal',$seguimiento->tipo_penal ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Medidas cautelares</label>
      <select name="medidas_cautelares" class="form-select">
        <option value="" selected>—</option>
        @foreach($cat['medidas'] as $opt)
          <option value="{{ $opt }}" @selected(old('medidas_cautelares',$seguimiento->medidas_cautelares ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12">
      <label class="form-label">Detalle de medidas</label>
      <input name="detalle_medidas" value="{{ old('detalle_medidas',$seguimiento->detalle_medidas ?? '') }}" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">¿Hubo vinculación?</label>
      <select name="hubo_vinculacion" class="form-select" required>
        @foreach($cat['vinculacion'] as $opt)
          <option value="{{ $opt }}" @selected(old('hubo_vinculacion',$seguimiento->hubo_vinculacion ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-8">
      <label class="form-label">Vinculados</label>
      <select name="vinculados[]" class="form-select" multiple>
        @foreach($vinculados as $v)
          <option value="{{ $v }}">{{ $v }}</option>
        @endforeach
      </select>
      <small class="text-muted">Puedes seleccionar varios (Ctrl/Cmd + click).</small>
    </div>

    <div class="col-md-6">
      <label class="form-label">Situación jurídica</label>
      <select name="situacion_juridica" class="form-select" required>
        <option value="" disabled selected>Seleccione…</option>
        @foreach($cat['situaciones'] as $opt)
          <option value="{{ $opt }}" @selected(old('situacion_juridica',$seguimiento->situacion_juridica ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Escena de levantamiento</label>
      <select name="escena_levantamiento" class="form-select">
        <option value="" selected>—</option>
        @foreach($cat['escenas'] as $opt)
          <option value="{{ $opt }}" @selected(old('escena_levantamiento',$seguimiento->escena_levantamiento ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Escena del suceso</label>
      <select name="escena_suceso" class="form-select">
        <option value="" selected>—</option>
        @foreach($cat['escenas'] as $opt)
          <option value="{{ $opt }}" @selected(old('escena_suceso',$seguimiento->escena_suceso ?? '')===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
      <div class="form-check mt-1">
        <input class="form-check-input" type="checkbox" name="escena_misma" value="1" id="escMisma">
        <label for="escMisma" class="form-check-label">Es la misma escena</label>
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Requerimientos realizados</label>
      <select name="requerimientos_realizados[]" class="form-select" multiple>
        @foreach($cat['reqs'] as $opt)
          <option value="{{ $opt }}">{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Requerimientos pendientes</label>
      <select name="requerimientos_pendientes[]" class="form-select" multiple>
        @foreach($cat['reqs'] as $opt)
          <option value="{{ $opt }}">{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12">
      <label class="form-label">Observación</label>
      <textarea name="observacion" class="form-control" rows="3">{{ old('observacion',$seguimiento->observacion ?? '') }}</textarea>
    </div>

    <div class="col-12 d-flex gap-2">
      <a href="{{ route('casos.show',$caso->id) }}" class="btn btn-outline-secondary">Volver</a>
      <button class="btn btn-success">Guardar seguimiento</button>
    </div>
  </form>
</div>
@endsection
