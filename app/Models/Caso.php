<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model {
    protected $table = 'casos';
    protected $fillable = ['numero_caso','label','fecha','cedula'];
    public function usuario(){ return $this->belongsTo(Usuario::class,'cedula','cedula'); }
    public function detalle(){ return $this->hasOne(DetalleCaso::class,'caso_id'); }
}
