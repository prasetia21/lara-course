<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;

    // mengarahkan ke tabel my_courses
    protected $table = 'my_courses';

    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'course_id', 'user_id'
    ];

    // untuk modifikasi format tanggal created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    // buat foreignkey untuk mendapatkan data dari model lain
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
