<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use App\Models\Chapter;

class CourseController extends Controller
{
    // buat fungsi untuk membuat CRUD Get List
    public function index(Request $request)
    {
        // ambil semua list data courses menggunakan query, supaya kita bisa buat paginasi
        $courses = Course::query();

        // buat filter data
        $q = $request->query('q');
        $status = $request->query('status');

        // fungsi filter berdasar nama
        $courses->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE '%".strtolower($q)."%'");
        });

        // fungsi filter berdasar status
        $courses->when($status, function($query) use ($status) {
            return $query->where('status', '=', $status);
        });


        // berikan respon jika data berhasil dipanggil dan buat data menjadi perpage / pagination
        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    // buat fungsi untuk membuat CRUD Get by ID
    public function show($id)
    {
        // cari data courses berdasar id
        // $course = course::find($id);
        // cari data courses berdasar id yang berelasi dengan data chapters, mentors dan image course
        // untuk data chapter akan direlasikan kembali dengan data lesson, datanya diambil dari relasi yang dibuat di model Chapter
        // referensi relasi sudah dibuat di Model Course
        $course = Course::with('chapters.lessons')
            ->with('mentors')
            ->with('images')
            ->find($id);

        // cek apakah ditemukan id course
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        // ambil data review berdasar id course
        $reviews = Review::where('course_id', '=', $id)->get()->toArray();

        // ambil data review dari masing-masing users
        // cek ada tidaknya review user
        if (count($reviews) > 0) {
            // ambil hanya data kolom user id
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);
            // tampilkan data
            // echo "<pre>".print_r($users, 1)."</pre>";
            if ($users['status'] === 'error') {
                // tampilkan array kosong bila error, misal saat service user down
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        // total jumlah murid yang mengikuti course
        $totalStudent = MyCourse::where('course_id', '=', $id)->count();

        // cek jumlah total materi / lessons / video
        $totalVideo = Chapter::where('course_id', '=', $id)->withCount('lessons')->get()->toArray();
        // echo "<pre>".print_r($totalVideo, 1)."</pre>";
        // jumlah total video semua lessons
        $finalTotalVideo = array_sum(array_column($totalVideo, 'lessons_count'));
        // echo "<pre>".print_r($finalTotalVideo, 1)."</pre>";


        // masukkan data review murid dan jumlah murid ke array course
        $course['reviews'] = $reviews;
        $course['total_videos'] = $finalTotalVideo;
        $course['total_students'] = $totalStudent;

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate, advance',
            'mentor_id' => 'required|integer',
            'description' => 'string'
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

        // cek apakah ada mentor id
        // jangan lupa import model Mentor, use App\Models\Mentor
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        // cek apakah ditemukan id mentor
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        // jika tidak ada error, create / save ke database
        // jangan lupa import model Course, use App\Models\Course;
        $course = Course::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);

    }

    // buat fungsi untuk membuat CRUD Update 
    public function update(Request $request, $id)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate, advance',
            'mentor_id' => 'integer',
            'description' => 'string'
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

        // jika tidak ada error, cari id course apakah ada didatabase
        // jangan lupa import Course, use App\Models\Course;
        $course = Course::find($id);

        // cek apakah ditemukan id course
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        // cek apakah ada mentor id
        // jangan lupa import model Mentor, use App\Models\Mentor
        $mentorId = $request->input('mentor_id');
    
        // cek apakah ditemukan id mentor yang datanya dikirimkan dari fontend
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor not found'
                ], 404);
            }
        }
        

        // jika ada maka isi dan save data update
        $course->fill($data);

        $course->save();

        // berikan respon jika data berhasil diupdate
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);

    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data mentor berdasar id
        $course = Course::find($id);

        // cek apakah ditemukan id course
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        // jika ada hapus datanya
        $course->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'course deleted'
        ]);
    }
}
