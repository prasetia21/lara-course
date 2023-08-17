<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) Mentors,
// jangan lupa import contollernya use App\Http\Controllers\MentorController;
Route::get('mentors', [MentorController::class, 'index']);
Route::get('mentors/{id}', [MentorController::class, 'show']);
Route::post('mentors', [MentorController::class, 'create']);
Route::put('mentors/{id}', [MentorController::class, 'update']);
Route::delete('mentors/{id}', [MentorController::class, 'destroy']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) Courses,
// jangan lupa import contollernya use App\Http\Controllers\CourseController;
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{id}', [CourseController::class, 'show']);
Route::post('courses', [CourseController::class, 'create']);
Route::put('courses/{id}', [CourseController::class, 'update']);
Route::delete('courses/{id}', [CourseController::class, 'destroy']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) Chapters,
// jangan lupa import contollernya use App\Http\Controllers\ChapterController;
Route::get('chapters', [ChapterController::class, 'index']);
Route::get('chapters/{id}', [ChapterController::class, 'show']);
Route::post('chapters', [ChapterController::class, 'create']);
Route::put('chapters/{id}', [ChapterController::class, 'update']);
Route::delete('chapters/{id}', [ChapterController::class, 'destroy']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) Lessons,
// jangan lupa import contollernya use App\Http\Controllers\LessonController;
Route::get('lessons', [LessonController::class, 'index']);
Route::get('lessons/{id}', [LessonController::class, 'show']);
Route::post('lessons', [LessonController::class, 'create']);
Route::put('lessons/{id}', [LessonController::class, 'update']);
Route::delete('lessons/{id}', [LessonController::class, 'destroy']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) ImageCourse,
// jangan lupa import contollernya use App\Http\Controllers\ImageCourseController;
Route::post('image-courses', [ImageCourseController::class, 'create']);
Route::delete('image-courses/{id}', [ImageCourseController::class, 'destroy']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) MyCourse,
// jangan lupa import contollernya use App\Http\Controllers\MyCourseController;
Route::get('my-courses', [MyCourseController::class, 'index']);
Route::post('my-courses', [MyCourseController::class, 'create']);
Route::post('my-courses/premium', [MyCourseController::class, 'createPremiumAccess']);


// buat route untuk CRUD (Get All, Get By ID, Create, Update, Destroy ) Review,
// jangan lupa import contollernya use App\Http\Controllers\ReviewController;
//Route::get('review', [ReviewController::class, 'index']);
Route::post('reviews', [ReviewController::class, 'create']);
Route::put('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
