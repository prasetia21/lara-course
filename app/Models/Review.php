<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // mengarahkan ke tabel reviews
    protected $table = 'reviews';

    // field yang bisa diisi ke dalam tabel
    protected $fillable = [
        'user_id', 'course_id', 'rating', 'note'
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
