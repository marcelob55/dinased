<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicio extends Model
{
    protected $table = 'indicios';
    protected $fillable = ['seguimiento_id','tipo','descripcion'];

    public function seguimiento(){ return $this->belongsTo(Seguimiento::class,'seguimiento_id'); }
}
