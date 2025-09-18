<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'audio_file_id',
        'start_time',
        'end_time',
        'text'
    ];

    protected $casts = [
        'start_time' => 'float',
        'end_time' => 'float'
    ];

    public function audioFile()
    {
        return $this->belongsTo(AudioFile::class);
    }
}