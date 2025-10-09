<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTrain extends Model
{
    use HasFactory;

    protected $table = "jobtrain";

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function peserta_magang()
    {
        return $this->hasMany(PesertaMagang::class);
    }
}

