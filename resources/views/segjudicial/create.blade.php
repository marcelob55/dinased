@extends('layouts.app')
@section('title','SEGUIMIENTO JUDICIAL')

@section('content')
<style>
  :root{
    --surf:#f8fafc; --stroke:#e5e7eb; --ink:#0f172a; --muted:#64748b; --brand:#0ea5e9; --badge:#e5e7eb;
  }
  .page-wrap{max-width:1000px;margin-inline:auto}
  .h1{font-weight:800;letter-spacing:.4px;font-size:clamp(1.25rem,2.2vw,1.6rem);margin:8px 0 18px}
  .meta-line{display:flex;gap:.75rem;flex-wrap:wrap;color:var(--muted);font-weight:600;margin-bottom:14px}
  .card{background:#fff;border:1px solid var(--stroke);border-radius:14px;margin-bottom:18px}
  .card-pad{padding:16px 18px}
  .section-title{margin:20px 0 10px;font-weight:800;color:var(--muted);letter-spacing:.4px;font-size:.85rem}
  .kv{display:grid;grid-template-columns:220px 1fr;gap:.35rem 1rem;margin:.15rem 0}
  .kv b{color:var(--muted);font-weight:800;letter-spacing:.3px}
  .field{margin-bottom:14px}
  .field label{display:block;font-size:.82rem;font-weight:800;color:var(--muted);letter-spacing:.35px;margin-bottom:6px}
  .ctrl{width:100%;padding:.70rem .9rem;border:1px solid var(--stroke);border-radius:10px;outline:0;background:#fff}
  .ctrl:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(14,165,233,.15)}
  select.ctrl{padding:.62rem .8rem}
  textarea.ctrl{min-height:120px;resize:vertical}
  .chips-wrap{background:var(--surf);border:1px solid var(--stroke);border-radius:12px;padding:10px}
  .chips{display:flex;gap:.5rem;flex-wrap:wrap}
  .chip{background:#eef2ff;border:1px solid #c7d2fe;color:#111827;padding:.45rem .7rem;border-radius:999px;font-weight:700}
  .chip .x{margin-left:.45rem;cursor:pointer;font-weight:900;border:0;background:transparent}
  .badge{display:inline-block;background:var(--badge);border-radius:999px;padding:.05rem .5rem;font-size:.75rem;font-weight:800}
  .ttu{text-transform:uppercase}
  .upper{text-transform:uppercase}
  .row{display:grid;grid-template-columns:repeat(12,1fr);gap:12px}
  .col-12{grid-column:span 12}
  .col-6{grid-column:span 12}
  .col-5{grid-column:span 12}
  .col-4{grid-column:span 12}
  .col-3{grid-column:span 12}
  @media(min-width:900px){
    .col-6{grid-column:span 6}
    .col-5{grid-column:span 5}
    .col-4{grid-column:span 4}
    .col-3{grid-column:span 3}
  }
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;border:1px solid var(--stroke);background:#fff;border-radius:10px;padding:.65rem 1rem;font-weight:800}
  .btn-primary{background:#22c55e;border-color:#22c55e;color:#fff}
  .btn-ghost{background:#fff}
</style>

<div class="page-wrap">
  {{-- Título y meta (FECHA + DELITO) --}}
  <h1 class="h1 ttu">SEGUIMIENTO JUDICIAL — {{ $caso->numero_caso }}</h1>
  <div class="meta-line ttu">
    <span>FECHA: {{ $contexto['fecha_hora'] ?: '—' }}</span>
    <span>DELITO: {{ $contexto['tipo_delito'] ?: '—' }}</span>
  </div>

  @if (session('ok'))
    <div class="alert alert-success">{{ strtoupper(session('ok')) }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li class="ttu">{{ strtoupper($e) }}</li>@endforeach</ul>
    </div>
  @endif

  {{-- ===== DETALLE DEL CASO ===== --}}
  <div class="card card-pad">
    <div class="section-title ttu">DETALLE DEL CASO</div>

    <div class="kv"><b class="ttu">CÓDIGO ECU:</b> {{ $contexto['ecu'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">VERIFICACIÓN:</b> {{ $contexto['verificacion'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">ZONA:</b> {{ $contexto['zona'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">SUBZONA:</b> {{ $contexto['subzona'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">DISTRITO:</b> {{ $contexto['distrito'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">CIRCUITO:</b> {{ $contexto['circuito'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">SUBCIRCUITO:</b> {{ $contexto['subcircuito'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">ESPACIO:</b> {{ $contexto['espacio'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">ÁREA:</b> {{ $contexto['area'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">LUGAR DEL HECHO:</b> {{ $contexto['lugar_hecho'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">COORDENADAS:</b> {{ $contexto['coordenadas'] ?: '—' }}</div>
    <div class="kv"><b class="ttu">CRIMINALÍSTICA:</b> {{ $contexto['criminalistica'] ?: '—' }}</div>

    {{-- INDICIOS: SI/NO + detalle desde Alimentar --}}
    <div class="kv">
      <b class="ttu">¿INDICIOS?:</b>
      <div>
        {{ $contexto['indicios'] ?: '—' }}
        @if(!empty($contexto['indicios_lines']))
          <div style="margin-top:.35rem; white-space:pre-line">
            {!! nl2br(e(implode("\n", $contexto['indicios_lines']))) !!}
          </div>
        @endif
      </div>
    </div>

    <div class="kv"><b class="ttu">TIPO DE ARMA:</b> {{ $contexto['tipo_arma'] ?: '—' }}</div>

    @php
      $fall = collect($contexto['fallecidos'] ?? []);
      $her  = collect($contexto['heridos'] ?? []);
    @endphp

    <div class="section-title ttu" style="margin-top:10px">PERSONAS</div>

    <div class="kv">
      <b class="ttu">FALLECIDO{{ $fall->count()===1?'':'S' }}:</b>
      <div>
        @if($fall->count()>1)
          <span class="badge ttu">MÚLTIPLE ({{ $fall->count() }})</span><br>
        @endif
        <ul style="margin:8px 0 0 18px">
          @forelse($fall as $p)
            <li class="upper">{{ $p->nombre }}{{ $p->cedula ? " — {$p->cedula}" : '' }}</li>
          @empty
            <li>—</li>
          @endforelse
        </ul>
      </div>
    </div>

    <div class="kv">
      <b class="ttu">HERIDO{{ $her->count()===1?'':'S' }}:</b>
      <div>
        <ul style="margin:0 0 0 18px">
          @forelse($her as $p)
            <li class="upper">{{ $p->nombre }}{{ $p->cedula ? " — {$p->cedula}" : '' }}</li>
          @empty
            <li>—</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- ===== FORMULARIO ===== --}}
  <div class="section-title ttu">INGRESAR DATOS DE LA INSTRUCCIÓN FISCAL</div>

  <form method="POST" action="{{ route('segjudicial.store',$caso->id) }}">
    @csrf

    <div class="row">
      <div class="col-6 field">
        <label class="ttu">No. CAUSA / No. FISCALÍA (15 DÍGITOS)</label>
        <input class="ctrl upper" name="no_causa_no_fiscalia" maxlength="15"
               value="{{ old('no_causa_no_fiscalia',$seguimiento->no_causa_no_fiscalia ?? '') }}">
        <div style="color:var(--muted);font-size:.8rem;margin-top:4px">DEBE TENER EXACTAMENTE 15 DÍGITOS.</div>
      </div>

      <div class="col-6 field">
        <label class="ttu">FISCAL DELEGADO</label>
        <select name="nombres_del_fiscal_delegado" class="ctrl upper">
          <option value="">— SELECCIONE —</option>
          @foreach($cat['fiscales'] as $opt)
            <option value="{{ $opt }}" @selected(old('nombres_del_fiscal_delegado',$seguimiento->nombres_del_fiscal_delegado ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-4 field">
        <label class="ttu">NOMBRE DE FISCALÍA</label>
        <select name="fiscalia_nombre" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['fiscaliasNombres'] as $opt)
            <option value="{{ $opt }}" @selected(old('fiscalia_nombre',$seguimiento->fiscalia_nombre ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-3 field">
        <label class="ttu">No. DE FISCALÍA</label>
        <select name="fiscalia_numero" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['fiscaliasNumeros'] as $opt)
            <option value="{{ $opt }}" @selected(old('fiscalia_numero',$seguimiento->fiscalia_numero ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-5 field">
        <label class="ttu">TIPO PENAL (FORMULACIÓN)</label>
        <select name="tipo_penal_en_audiencia_de_formulacion_de_cargos" class="ctrl upper">
          <option value="">— SELECCIONE —</option>
          @foreach($cat['tiposPenales'] as $opt)
            <option value="{{ $opt }}" @selected(old('tipo_penal_en_audiencia_de_formulacion_de_cargos',$seguimiento->tipo_penal_en_audiencia_de_formulacion_de_cargos ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-4 field">
        <label class="ttu">MEDIDAS CAUTELARES</label>
        <select name="tipo_de_medidas" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['medidas'] as $opt)
            <option value="{{ $opt }}" @selected(old('tipo_de_medidas',$seguimiento->tipo_de_medidas ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-8 field">
        <label class="ttu">DETALLE DE MEDIDAS</label>
        <select name="detalle_de_medidas" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['detalleMedidas'] as $opt)
            <option value="{{ $opt }}" @selected(old('detalle_de_medidas',$seguimiento->detalle_de_medidas ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-4 field">
        <label class="ttu">¿HUBO VINCULACIÓN?</label>
        <select name="existio_vinculacion_dentro_de_la_instruccion_fiscal" class="ctrl upper">
          <option value="NO" @selected(old('existio_vinculacion_dentro_de_la_instruccion_fiscal',$seguimiento->existio_vinculacion_dentro_de_la_instruccion_fiscal ?? '')==='NO')>NO</option>
          <option value="SI" @selected(old('existio_vinculacion_dentro_de_la_instruccion_fiscal',$seguimiento->existio_vinculacion_dentro_de_la_instruccion_fiscal ?? '')==='SI')>SI</option>
        </select>
      </div>

      {{-- Vinculados --}}
      <div class="col-12 field">
        <label class="ttu">VINCULADOS</label>
        <input id="vinculadoInput" type="text" class="ctrl upper"
               placeholder="ESCRIBE NOMBRE(S) Y APELLIDO(S), PRESIONA ENTER O USA EL BOTÓN AGREGAR">
        <button id="addVinculadoBtn" type="button" class="btn btn-ghost ttu" style="margin-top:8px">AGREGAR</button>

        <div class="chips-wrap" style="margin-top:10px">
          <div id="chips" class="chips">
            @php
              $chips = collect(old('vinculados', explode(', ', (string)($seguimiento->nombre_del_o_los_vinculados ?? ''))))
                        ->filter()->values();
            @endphp
            @foreach($chips as $chip)
              <span class="chip upper" data-value="{{ $chip }}">{{ $chip }} <button class="x" type="button">&times;</button></span>
              <input type="hidden" name="vinculados[]" value="{{ $chip }}">
            @endforeach
          </div>
        </div>

        @if(isset($vinculados) && $vinculados->count())
          <div class="meta-line" style="margin-top:8px">SUGERIDOS (CLIC PARA AGREGAR):</div>
          <div class="chips" style="margin-top:4px">
            @foreach($vinculados as $sug)
              <button type="button" class="btn btn-ghost upper add-suggest" data-value="{{ $sug }}">{{ $sug }}</button>
            @endforeach
          </div>
        @endif
      </div>

      <div class="col-6 field">
        <label class="ttu">SITUACIÓN JURÍDICA</label>
        <select name="situacion_juridica_actual" class="ctrl upper">
          <option value="">— SELECCIONE —</option>
          @foreach($cat['situaciones'] as $opt)
            <option value="{{ $opt }}" @selected(old('situacion_juridica_actual',$seguimiento->situacion_juridica_actual ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-3 field">
        <label class="ttu">ESCENA DE LEVANTAMIENTO</label>
        <select name="escena_levantamiento" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['escenas'] as $opt)
            <option value="{{ $opt }}" @selected(old('escena_levantamiento',$seguimiento->escena_levantamiento ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-3 field">
        <label class="ttu">ESCENA DEL SUCESO</label>
        <select name="escena_suceso" class="ctrl upper">
          <option value="">—</option>
          @foreach($cat['escenas'] as $opt)
            <option value="{{ $opt }}" @selected(old('escena_suceso',$seguimiento->escena_suceso ?? '')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
        <div class="form-check" style="margin-top:6px">
          <input class="form-check-input" type="checkbox" name="escena_misma" value="1" id="escMisma" @checked(old('escena_misma'))>
          <label for="escMisma" class="form-check-label ttu" style="font-weight:700;color:var(--muted)">ES LA MISMA ESCENA</label>
        </div>
      </div>

      {{-- Requerimientos realizados --}}
      <div class="col-6 field">
        <label class="ttu">REQUERIMIENTOS REALIZADOS</label>
        <div style="display:flex; gap:.5rem; align-items:center">
          <select id="reqRealSelect" class="ctrl upper" style="flex:1">
            <option value="">— SELECCIONE —</option>
            @foreach($cat['reqs'] as $opt)
              <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
          </select>
          <button id="addReqRealBtn" type="button" class="btn btn-ghost ttu">AGREGAR</button>
        </div>

        <div id="reqRealChips" class="chips-wrap" style="margin-top:10px">
          <div class="chips">
            @php
              $reqReal = collect(old('requerimientos_realizados',
                          explode(', ', (string)($seguimiento->requerimientos_realizados ?? ''))))
                          ->filter()->values();
            @endphp
            @foreach($reqReal as $item)
              <span class="chip upper" data-value="{{ $item }}">{{ $item }}
                <button type="button" class="x">&times;</button>
              </span>
              <input type="hidden" name="requerimientos_realizados[]" value="{{ $item }}">
            @endforeach
          </div>
        </div>
      </div>

      {{-- Requerimientos pendientes --}}
      <div class="col-6 field">
        <label class="ttu">REQUERIMIENTOS PENDIENTES</label>
        <div style="display:flex; gap:.5rem; align-items:center">
          <select id="reqPendSelect" class="ctrl upper" style="flex:1">
            <option value="">— SELECCIONE —</option>
            @foreach($cat['reqs'] as $opt)
              <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
          </select>
          <button id="addReqPendBtn" type="button" class="btn btn-ghost ttu">AGREGAR</button>
        </div>

        <div id="reqPendChips" class="chips-wrap" style="margin-top:10px">
          <div class="chips">
            @php
              $reqPend = collect(old('requerimientos_pendientes',
                          explode(', ', (string)($seguimiento->requerimientos_pendientes ?? ''))))
                          ->filter()->values();
            @endphp
            @foreach($reqPend as $item)
              <span class="chip upper" data-value="{{ $item }}">{{ $item }}
                <button type="button" class="x">&times;</button>
              </span>
              <input type="hidden" name="requerimientos_pendientes[]" value="{{ $item }}">
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 field">
        <label class="ttu">OBSERVACIÓN</label>
        <textarea name="observacion" class="ctrl upper" rows="4">{{ old('observacion',$seguimiento->observacion ?? '') }}</textarea>
      </div>

      <div class="col-12" style="display:flex;gap:.5rem;flex-wrap:wrap">
        <a href="{{ route('casos.show',$caso->id) }}" class="btn btn-ghost ttu">VOLVER</a>
        <button class="btn btn-primary ttu">GUARDAR SEGUIMIENTO</button>
      </div>
    </div>
  </form>
</div>

<script>
(function () {
  /* ---------- Vinculados ---------- */
  const vincInput = document.getElementById('vinculadoInput');
  const vincBtn   = document.getElementById('addVinculadoBtn');
  const vincBox   = document.getElementById('chips');

  function addVinc(val){
    val = (val || '').trim();
    if(!val) return;
    const exists = Array.from(vincBox.querySelectorAll('.chip'))
      .some(c => c.dataset.value === val);
    if(exists){ vincInput.value=''; return; }

    const span = document.createElement('span');
    span.className = 'chip upper';
    span.dataset.value = val;
    span.innerHTML = `${val} <button type="button" class="x">&times;</button>`;

    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'vinculados[]';
    hidden.value = val;

    vincBox.appendChild(span);
    vincBox.appendChild(hidden);
    vincInput.value = '';
  }

  vincBox?.addEventListener('click', (e)=>{
    if(e.target.classList.contains('x')){
      const chip = e.target.closest('.chip');
      const val  = chip.dataset.value;
      chip.remove();
      const hid = vincBox.querySelector(
        `input[type="hidden"][name="vinculados[]"][value="${CSS.escape(val)}"]`
      );
      hid?.remove();
    }
  });

  vincBtn?.addEventListener('click', ()=> addVinc(vincInput.value));
  vincInput?.addEventListener('keydown', (e)=>{
    if(e.key==='Enter'){ e.preventDefault(); addVinc(vincInput.value); }
  });
  document.querySelectorAll('.add-suggest').forEach(btn=>{
    btn.addEventListener('click', ()=> addVinc(btn.dataset.value || btn.textContent));
  });

  /* ---------- Select -> chips (reqs) ---------- */
  function wireSelectChips(selectId, btnId, chipsBoxId, inputName) {
    const sel   = document.getElementById(selectId);
    const btn   = document.getElementById(btnId);
    const wrap  = document.getElementById(chipsBoxId);
    const box   = wrap?.querySelector('.chips');

    function add(val){
      val = (val || '').trim();
      if(!val) return;
      const exists = Array.from(box.querySelectorAll('.chip'))
        .some(c => c.dataset.value === val);
      if(exists){ sel.value=''; return; }

      const span = document.createElement('span');
      span.className = 'chip upper';
      span.dataset.value = val;
      span.innerHTML = `${val} <button type="button" class="x">&times;</button>`;

      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = inputName + '[]';
      hidden.value = val;

      box.appendChild(span);
      box.appendChild(hidden);
      sel.value = '';
    }

    btn?.addEventListener('click', ()=> add(sel.value));
    sel?.addEventListener('change', ()=> add(sel.value));

    box?.addEventListener('click', (e)=>{
      if(e.target.classList.contains('x')){
        const chip = e.target.closest('.chip');
        const val  = chip.dataset.value;
        chip.remove();
        const hid = box.querySelector(
          `input[type="hidden"][name="${inputName}[]"][value="${CSS.escape(val)}"]`
        );
        hid?.remove();
      }
    });
  }

  wireSelectChips('reqRealSelect','addReqRealBtn','reqRealChips','requerimientos_realizados');
  wireSelectChips('reqPendSelect','addReqPendBtn','reqPendChips','requerimientos_pendientes');
})();
</script>
@endsection
