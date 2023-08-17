<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    use HasFactory;

    // mengarahkan ke tabel image_courses
    protected $table = 'image_courses';

    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'course_id', 'image'
    ];

    // untuk modifikasi format tanggal created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];
}
