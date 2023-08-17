<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Chapter;

class ChapterController extends Controller
{
    // buat fungsi untuk membuat CRUD Get List
    public function index(Request $request)
    {
        // ambil semua list data chapters menggunakan query
        $chapters = Chapter::query();

        // filter menerima data course id
        $courseId = $request->query('course_id');

        // fungsi filter berdasar course id
        $chapters->when($courseId, function($query) use ($courseId) {
            return $query->where('course_id', '=', $courseId);
        });

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $chapters->get()
        ]);
    }

    // buat fungsi untuk membuat CRUD Get by ID
    public function show($id)
    {
        // cari data chapters berdasar id
        $chapter = Chapter::find($id);

        // cek apakah ditemukan id chapter
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
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

        // cek apakah ada course id
        // jangan lupa import model Course, use App\Models\Course
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        // cek apakah ditemukan id course
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        // jika tidak ada error, create / save ke database
        // jangan lupa import model Chapter, use App\Models\Chapter;
        $chapter = Chapter::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);

    }

    // buat fungsi untuk membuat CRUD Update 
    public function update(Request $request, $id)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'string',
            'course_id' => 'integer'
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

        // jika tidak ada error, cari id chapter apakah ada didatabase
        // jangan lupa import Chapter, use App\Models\Chapter;
        $chapter = Chapter::find($id);

        // cek apakah ditemukan id chapter
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        // cek apakah ada course id
        // jangan lupa import model Course, use App\Models\Course
        $courseId = $request->input('course_id');
    
        // cek apakah ditemukan id course yang datanya dikirimkan dari fontend
        if ($courseId) {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course not found'
                ], 404);
            }
        }
        

        // jika ada maka isi dan save data update
        $chapter->fill($data);

        $chapter->save();

        // berikan respon jika data berhasil diupdate
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);

    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data chapter berdasar id
        $chapter = Chapter::find($id);

        // cek apakah ditemukan id chapter
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        // jika ada hapus datanya
        $chapter->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'chapter deleted'
        ]);
    }
}
