<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lesson;
use App\Models\Chapter;

class LessonController extends Controller
{
    // buat fungsi untuk membuat CRUD Get List
    public function index(Request $request)
    {
        // ambil semua list data lessons menggunakan query
        $lessons = Lesson::query();

        // filter menerima data chapter id
        $chapterId = $request->query('chapter_id');

        // fungsi filter berdasar chapter id
        $lessons->when($chapterId, function($query) use ($chapterId) {
            return $query->where('chapter_id', '=', $chapterId);
        });

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $lessons->get()
        ]);
    }

    // buat fungsi untuk membuat CRUD Get by ID
    public function show($id)
    {
        // cari data lessons berdasar id
        $lesson = Lesson::find($id);

        // cek apakah ditemukan id lesson
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        // ambil data dari body
        $data = $request->all();

        // cek validasi data, import terlebih dahulu Validator, use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($data, $rules);

        // cek apakah ada error di validasi data
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek apakah ada chapter id
        // jangan lupa import model Chapter, use App\Models\Chapter
        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);

        // cek apakah ditemukan id chapter
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        // jika tidak ada error, create / save ke database
        // jangan lupa import model Lesson, use App\Models\Lesson;
        $lesson = Lesson::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);

    }

    // buat fungsi untuk membuat CRUD Update 
    public function update(Request $request, $id)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        // ambil data dari body
        $data = $request->all();

        // cek validasi data, import terlebih dahulu Validator, use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($data, $rules);

        // cek apakah ada error di validasi data
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // jika tidak ada error, cari id lesson apakah ada didatabase
        // jangan lupa import Lesson, use App\Models\Lesson;
        $lesson = Lesson::find($id);

        // cek apakah ditemukan id lesson
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        // cek apakah ada chapter id
        // jangan lupa import model Chapter, use App\Models\Chapter
        $chapterId = $request->input('chapter_id');
    
        // cek apakah ditemukan id chapter yang datanya dikirimkan dari fontend
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'chapter not found'
                ], 404);
            }
        }
        

        // jika ada maka isi dan save data update
        $lesson->fill($data);

        $lesson->save();

        // berikan respon jika data berhasil diupdate
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);

    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data lesson berdasar id
        $lesson = Lesson::find($id);

        // cek apakah ditemukan id lesson
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        // jika ada hapus datanya
        $lesson->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'lesson deleted'
        ]);
    }
}
