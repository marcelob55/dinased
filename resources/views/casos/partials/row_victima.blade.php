@php
  $prefix = $tipo === 'occiso' ? 'fallecidos' : 'heridos';
@endphp
<tr>
  <td><input name="{{ $prefix }}[][etiqueta]" value="{{ old($prefix.'.*.etiqueta', $v->etiqueta ?? '') }}"></td>
  <td><input name="{{ $prefix }}[][nombres]"  value="{{ old($prefix.'.*.nombres',  $v->nombres ?? '') }}"></td>
  <td><input name="{{ $prefix }}[][apellidos]" value="{{ old($prefix.'.*.apellidos', $v->apellidos ?? '') }}"></td>
  <td><input name="{{ $prefix }}[][cedula]"    value="{{ old($prefix.'.*.cedula',   $v->cedula ?? '') }}"></td>
  <td><input name="{{ $prefix }}[][edad]"      value="{{ old($prefix.'.*.edad',     $v->edad ?? '') }}" style="width:60px"></td>
  <td>
    <select name="{{ $prefix }}[][sexo]">
      <option value=""></option>
      @foreach(['M'=>'M','F'=>'F','I'=>'I'] as $sxVal => $sxTxt)
        <option value="{{ $sxVal }}" @selected(($v->sexo ?? '')===$sxVal)>{{ $sxTxt }}</option>
      @endforeach
    </select>
  </td>
  <td><input name="{{ $prefix }}[][alias]" value="{{ $v->alias ?? '' }}"></td>
  <td><input name="{{ $prefix }}[][nacionalidad]" value="{{ $v->nacionalidad ?? '' }}"></td>
  <td><input name="{{ $prefix }}[][profesion_ocupacion]" value="{{ $v->profesion_ocupacion ?? '' }}"></td>
  <td><input name="{{ $prefix }}[][movilizacion]" value="{{ $v->movilizacion ?? '' }}"></td>
  <td>
    <select name="{{ $prefix }}[][antecedentes]">@foreach([''=>'',1=>'Sí',0=>'No'] as $k=>$t)
      <option value="{{ $k }}" @selected(($v->antecedentes ?? null)===(is_numeric($k)?(int)$k:null))>{{ $t }}</option>
    @endforeach</select>
  </td>
  <td>
    <select name="{{ $prefix }}[][sajte_judicatura]">@foreach([''=>'',1=>'Sí',0=>'No'] as $k=>$t)
      <option value="{{ $k }}" @selected(($v->sajte_judicatura ?? null)===(is_numeric($k)?(int)$k:null))>{{ $t }}</option>
    @endforeach</select>
  </td>
  <td>
    <select name="{{ $prefix }}[][noticia_del_delito_fiscalia]">@foreach([''=>'',1=>'Sí',0=>'No'] as $k=>$t)
      <option value="{{ $k }}" @selected(($v->noticia_del_delito_fiscalia ?? null)===(is_numeric($k)?(int)$k:null))>{{ $t }}</option>
    @endforeach</select>
  </td>
  <td>
    <select name="{{ $prefix }}[][pertenece_gao]">@foreach([''=>'',1=>'Sí',0=>'No'] as $k=>$t)
      <option value="{{ $k }}" @selected(($v->pertenece_gao ?? null)===(is_numeric($k)?(int)$k:null))>{{ $t }}</option>
    @endforeach</select>
  </td>
  <td><input name="{{ $prefix }}[][gao_cargo_funcion]" value="{{ $v->gao_cargo_funcion ?? '' }}"></td>
  <input type="hidden" name="{{ $prefix }}[][tipo]" value="{{ $tipo }}">
</tr>
