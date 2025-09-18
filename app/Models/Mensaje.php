<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = ['de_usuario_id', 'para_usuario_id', 'mensaje'];

    public function emisor() 
    {
        return $this->belongsTo(User::class, 'de_usuario_id');
    }

    public function receptor() 
    {
        return $this->belongsTo(User::class, 'para_usuario_id');
    }
}
