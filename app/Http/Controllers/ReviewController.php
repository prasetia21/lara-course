<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use App\Models\Course; 

class ReviewController extends Controller
{
    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
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

        // cek apakah ada user id
        $userId = $request->input('user_id');
        // ambil function dari helpers getUser yang menyambung dengan service user
        $user = getUser($userId);

        // cek apakah ditemukan id user
        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }
        
        // pengecekan untuk cegah duplikasi data pemberian review
        $isExistReview = Review::where('course_id', '=', $courseId)
                                    ->where('user_id', '=', $userId)
                                    ->exists();
        if ($isExistReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'review already exist'
            ], 409);
        }


        // jika tidak ada error, create / save ke database
        // jangan lupa import Review, use App\Models\Review;
        $review = Review::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);

    }

    // buat fungsi untuk membuat CRUD Update 
    public function update(Request $request, $id)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];

        // ambil data dari body kecuali data user id dan course id, karena tidak diijinkan untuk diubah
        $data = $request->except('user_id', 'course_id');

        // cek validasi data, import terlebih dahulu Validator, use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($data, $rules);

        // cek apakah ada error di validasi data
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek apakah ada review id
        // jangan lupa import model review, use App\Models\review
        $review = Review::find($id);

        // cek apakah ditemukan id review
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        // jika ada maka isi dan save data update
        $review->fill($data);

        $review->save();

        // berikan respon jika data berhasil diupdate
        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data review berdasar id
        $review = Review::find($id);

        // cek apakah ditemukan id review
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        // jika ada hapus datanya
        $review->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'review deleted'
        ]);
    }
}
