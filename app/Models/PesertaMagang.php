<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PesertaMagang extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "peserta_magang";
    protected $primaryKey = "npm";
    protected $guard = "peserta_magang";

    protected $fillable = [
        'npm',
        'jobtrain_id',
        'nama_lengkap',
        'foto',
        'jurusan',
        'telepon',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function Jobtrain()
    {
        return $this->belongsTo(JobTrain::class);
    }
}
