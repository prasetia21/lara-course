<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mentor;

class MentorController extends Controller
{

    // buat fungsi untuk membuat CRUD Get
    public function index()
    {
        // ambil semua data mentors
        $mentors = Mentor::all();

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $mentors
        ]);
    }

    // buat fungsi untuk membuat CRUD Get by ID
    public function show($id)
    {
        // cari data mentors berdasar id
        $mentor = Mentor::find($id);

        // cek apakah ditemukan id mentor
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        // berikan respon jika data berhasil dipanggil
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    // buat fungsi untuk membuat CRUD Create 
    public function create(Request $request)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email'
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

        // jika tidak ada error, create / save ke database
        // jangan lupa import Mentor, use App\Models\Mentor;
        $mentor = Mentor::create($data);

        // berikan respon jika data berhasil dicreate
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);

    }

    // buat fungsi untuk membuat CRUD Update 
    public function update(Request $request, $id)
    {
        // mendefinisikan data yang akan diisi ke database
        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email'
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

        // jika tidak ada error, cari id apakah ada didatabase
        // jangan lupa import Mentor, use App\Models\Mentor;
        $mentor = Mentor::find($id);

        // cek apakah ditemukan id mentor
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        // jika ada maka isi dan save data update
        $mentor->fill($data);

        $mentor->save();

        // berikan respon jika data berhasil diupdate
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);

    }

    // buat fungsi untuk membuat CRUD Delete by ID
    public function destroy($id)
    {
        // cari data mentor berdasar id
        $mentor = Mentor::find($id);

        // cek apakah ditemukan id mentor
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        // jika ada hapus datanya
        $mentor->delete();

        // berikan respon jika data berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'mentor deleted'
        ]);
    }
}
