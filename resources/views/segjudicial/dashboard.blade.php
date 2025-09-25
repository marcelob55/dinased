@extends('layouts.app')
@section('title','Dashboard — Seguimiento Judicial')
@section('body-class','no-carousel')

@section('content')
<div class="page" style="max-width:1200px;margin-inline:auto;">
  <div class="grid" style="display:grid;grid-template-columns:320px 1fr;gap:18px;">
    {{-- ===== LATERAL: KPIs ===== --}}
    <aside>
      <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;">
        <div style="font-weight:800;color:#64748b;letter-spacing:.3px">Total de casos</div>
        <div style="font-size:42px;font-weight:900;line-height:1;margin-top:6px">{{ $totalCasos }}</div>
        <div style="color:#64748b;margin-top:4px">en la tabla <code>casos</code></div>

        <div style="height:1px;background:#e5e7eb;margin:14px 0;"></div>

        <div style="font-weight:800;color:#64748b;letter-spacing:.3px">Seguimientos con N° de causa</div>
        <div style="font-size:32px;font-weight:900;line-height:1.1;margin-top:6px">{{ $totalSegConCausa }}</div>
        <div style="color:#64748b;margin-top:4px">en la tabla <code>seguimiento</code> (con No. CAUSA/No. FISCALÍA)</div>

        <div style="height:1px;background:#e5e7eb;margin:14px 0;"></div>

        <div style="font-weight:800;color:#64748b;letter-spacing:.3px">Casos en ZONA 8</div>
        <div style="font-size:28px;font-weight:900;line-height:1.1;margin-top:6px">{{ $totalZona8 }}</div>

        <a class="btn btn--pill"
           href="{{ route('casos.index') }}"
           style="display:inline-block;margin-top:12px;background:#0d5b7a;color:#fff;padding:.55rem .9rem;border-radius:9px;">
          Consultar casos
        </a>

        {{-- Conteo por zona (seguimientos con causa) --}}
        <div style="height:1px;background:#e5e7eb;margin:14px 0;"></div>
        <div style="font-weight:800;color:#64748b;letter-spacing:.3px;margin-bottom:6px">Seg. con causa — por zona</div>
        <table style="width:100%;border-collapse:collapse;font-size:.92rem">
          <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
              <th style="padding:6px 4px;">Zona</th>
              <th style="padding:6px 4px;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($zonasCount as $z)
              <tr style="border-bottom:1px solid #f1f5f9">
                <td style="padding:6px 4px;">{{ $z->zona_norm==='SIN ZONA' ? 'SIN ZONA' : 'ZONA '.$z->zona_norm }}</td>
                <td style="padding:6px 4px;">{{ $z->total }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </aside>

    {{-- ===== DERECHA: Filtro + gráfico + tabla ===== --}}
    <section>
      {{-- Filtro por zona (queda a la derecha) --}}
      <form method="GET" style="margin:10px 0; display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
        <input type="hidden" name="q" value="{{ $q }}">
        <label class="ttu" style="font-weight:800;color:#64748b">Filtrar por zona</label>
        <select name="zona" class="ctrl" style="max-width:150px">
          <option value="">— Todas —</option>
          @foreach($zonasCount as $z)
            @php $zv = $z->zona_norm; @endphp
            @if($zv !== 'SIN ZONA')
              <option value="{{ $zv }}" @selected($zona===$zv)>ZONA {{ $zv }}</option>
            @endif
          @endforeach
          <option value="SIN ZONA" @selected($zona==='SIN ZONA')>SIN ZONA</option>
        </select>
        <button class="btn btn-ghost ttu" style="padding:.55rem .8rem">Aplicar</button>

        @php
          $totalZonaSel = ($zona!=='')
            ? optional($zonasCount->firstWhere('zona_norm', $zona))->total
            : null;
        @endphp
        @if($zona!=='')
          <span class="badge" style="margin-left:.5rem">Total seg. en {{ $zona==='SIN ZONA'?'SIN ZONA':'ZONA '.$zona }}:
            <b>{{ $totalZonaSel ?? 0 }}</b>
          </span>
          <a href="{{ route('segjudicial.dashboard') }}" class="btn btn-ghost ttu" style="margin-left:.5rem">Quitar filtro</a>
        @endif
      </form>

      {{-- Gráfico --}}
      <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;margin-bottom:16px;">
        <div style="font-weight:800;color:#64748b;letter-spacing:.3px;margin-bottom:10px">
          Situación Jurídica — distribución
        </div>
        <canvas id="situChart" height="220"></canvas>

        {{-- tabla/leyenda rápida --}}
        <table style="width:100%;border-collapse:collapse;margin-top:12px;font-size:.95rem">
          <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
              <th style="padding:6px 4px;">Situación</th>
              <th style="padding:6px 4px;">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            @foreach($situaciones as $s)
              <tr style="border-bottom:1px solid #f1f5f9">
                <td style="padding:6px 4px;">{{ ucwords(strtolower($s->situacion)) }}</td>
                <td style="padding:6px 4px;">{{ $s->total }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Buscador + tabla de seguimientos con causa --}}
      <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;">
        <form method="GET" class="row" style="display:flex;gap:.5rem;align-items:center;margin-bottom:10px">
          <input type="hidden" name="zona" value="{{ $zona }}">
          <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por número de caso, cédula de agente, nombre de vinculados o fiscal…"
                 class="ctrl"
                 style="flex:1;padding:.6rem .75rem;border:1px solid #e5e7eb;border-radius:10px;outline:0;">
          <button class="btn" style="background:#0d5b7a;color:#fff;border:0;border-radius:10px;padding:.6rem .9rem;font-weight:800;">Buscar</button>
        </form>

        <div style="color:#64748b;margin-bottom:6px">
          Mostrando los últimos {{ $rows->perPage() }} seguimientos
          @if($q) para <b>{{ $q }}</b> @endif
        </div>

        <div class="table-wrap" style="overflow:auto;">
          <table style="width:100%;border-collapse:collapse;font-size:.95rem">
            <thead>
              <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                <th style="padding:8px 6px;">Fecha</th>
                <th style="padding:8px 6px;">N° de caso</th>
                <th style="padding:8px 6px;">Distrito</th>
                <th style="padding:8px 6px;">Agente</th>
                <th style="padding:8px 6px;">Persona (vinculados)</th>
                <th style="padding:8px 6px;">Fiscal Delegado</th>
                <th style="padding:8px 6px;">Situación</th>
                <th style="padding:8px 6px;">N° de causa</th>
                <th style="padding:8px 6px;">Acción</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $r)
                <tr style="border-bottom:1px solid #f1f5f9">
                  <td style="padding:8px 6px;">{{ \Illuminate\Support\Carbon::parse($r->fecha)->format('Y-m-d') }}</td>
                  <td style="padding:8px 6px;">
                    <a href="{{ route('casos.show',$r->id) }}">{{ $r->numero_caso }}</a>
                  </td>
                  <td style="padding:8px 6px;">{{ $r->distrito ?: '—' }}</td>
                  <td style="padding:8px 6px;">
                    @php $ag = trim(($r->agente_nombres.' '.$r->agente_apellidos)); @endphp
                    {{ $ag !== '' ? $ag : $r->agente_cedula }}
                  </td>
                  <td style="padding:8px 6px;">{{ \Illuminate\Support\Str::limit($r->nombre_del_o_los_vinculados ?: '—', 48) }}</td>
                  <td style="padding:8px 6px;">{{ \Illuminate\Support\Str::limit($r->nombres_del_fiscal_delegado ?: '—', 36) }}</td>
                  <td style="padding:8px 6px;">{{ $r->situacion_juridica_actual ?: '—' }}</td>
                  <td style="padding:8px 6px;">{{ $r->no_causa_no_fiscalia ?: '—' }}</td>
                  <td style="padding:8px 6px;">
                    <a class="btn" style="border:1px solid #e5e7eb;border-radius:9px;padding:.35rem .6rem;font-weight:800"
                       href="{{ route('segjudicial.create',$r->id) }}">Ver</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="9" style="padding:10px">Sin resultados.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($rows->hasPages())
          <div style="margin-top:10px">
            {{ $rows->onEachSide(1)->links() }}
          </div>
        @endif
      </div>
    </section>
  </div>
</div>

{{-- Responsive para la grilla --}}
<style>
@media(max-width: 900px){
  .grid{ grid-template-columns: 1fr !important; }
}
</style>

{{-- Chart.js (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script>
(() => {
  const ctx = document.getElementById('situChart');
  if(!ctx) return;

  const labels = @json($chartLabels);
  const data   = @json($chartData);

  new Chart(ctx, {
    type: 'doughnut',
    data: { labels, datasets: [{ data }] },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: { callbacks: { label: (c) => `${c.label}: ${c.formattedValue}` } }
      },
      cutout: '58%'
    }
  });
})();
</script>
@endsection
