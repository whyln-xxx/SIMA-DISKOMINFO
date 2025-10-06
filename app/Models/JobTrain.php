<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PesertaMagang;

class JobTrain extends Model
{
    use HasFactory;

    protected $table = "jobtrain";

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function pesertamagang()
    {
        return $this->hasMany(PesertaMagang::class);
    }
}

