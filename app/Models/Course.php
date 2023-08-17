<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // mengarahkan ke tabel courses
    protected $table = 'courses';

    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'name', 'certificate', 'thumbnail', 'type',
        'status', 'price', 'level', 'description', 'mentor_id'
    ];

    // untuk modifikasi format tanggal created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    // buat foreignkey untuk mendapatkan data dari model lain
    public function mentors()
    {
        return $this->belongsTo('App\Models\Mentor');
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\Chapter')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ImageCourse')->orderBy('id', 'DESC');
    }
}
