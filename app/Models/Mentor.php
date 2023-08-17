<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    // mengarahkan ke tabel mentors
    protected $table = 'mentors';

    // untuk modifikasi format tanggal created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];


    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'name', 'profile', 'email', 'profession'
    ];
}
