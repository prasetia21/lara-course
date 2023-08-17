<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ImageCourse;
use App\Models\Course;

class ImageCourseController extends Controller
{
    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'image' => 'required|url',
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
        // jangan lupa import model ImageCourse, use App\Models\ImageCourse;
        $imageCourse = ImageCourse::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $imageCourse
        ]);

    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data ImageCourse berdasar id
        $imageCourse = ImageCourse::find($id);

        // cek apakah ditemukan id imageCourse
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image course not found'
            ], 404);
        }

        // jika ada hapus datanya
        $imageCourse->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'image course deleted'
        ]);
    }
}
