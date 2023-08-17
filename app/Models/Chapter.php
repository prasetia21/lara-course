<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    // mengarahkan ke tabel chapters
    protected $table = 'chapters';

    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'name', 'course_id'
    ];

    // untuk modifikasi format tanggal created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    // buat foreignkey untuk mendapatkan data dari model lain
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson')->orderBy('id', 'ASC');
    }
}
