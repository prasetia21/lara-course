<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\MyCourse;

class MyCourseController extends Controller
{

    // buat fungsi untuk membuat CRUD Get
    public function index(Request $request)
    {
        // ambil semua data myCourse beserta data course, dengan cara menambahkan ->with('course)
        // ini dibuat berdasar relasi antar model di fungsi belongsTo
        $myCourse = MyCourse::query()->with('course');

        // filter menerima data user id
        $userId = $request->query('user_id');

        // fungsi filter berdasar user id
        $myCourse->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $myCourse->get()
        ]);
    }

    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
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

        // pengecekan untuk cegah duplikasi data
        $isExistMyCourse = MyCourse::where('course_id', '=', $courseId)
                                    ->where('user_id', '=', $userId)
                                    ->exists();
                                    
        if ($isExistMyCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'user already taken this course'
            ], 409);
        }

        if ($course->type === 'premium') {
            // pengecekan jika harga course premium 0 maka tidak akan diproses
            if ($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price can\'t be 0'
                ], 405);
            }

            // panggil helper untuk memanggil order payment
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);

            // debug error array status not defined
            // echo "<pre>".print_r($order, 1)."</pre>";

            // cek jika order gagal
            if ($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            // berikan respon bila berhasil
            return response()->json([
                'status' => $order['status'],
                'data' => $order['data']
            ]);
        } else {
            // jika tidak ada error, create / save ke database
            // jangan lupa import MyCourse, use App\Models\MyCourse;
            $myCourse = MyCourse::create($data);

            // berikan respon jika data berhasil dicreate
            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }
    }

    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
