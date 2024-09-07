<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Time;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimesController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->startOfDay();

        // 最新の3件の勤怠データを取得
        $items = Time::where('user_id', Auth::id())
            ->orderBy('punchIn', 'desc')
            ->get()  // クエリビルダーではなくコレクションを返す
            ->groupBy(function($date) {
                return Carbon::parse($date->punchIn)->format('Y-m-d');
            })
            ->take(3);

        // 'home' ビューにデータを渡す
        return view('home', ['items' => $items]);
    }

    public function punchIn()
    {
        $currentTime = Carbon::now('Asia/Tokyo');
    \Log::info('PunchIn Time:', ['time' => $currentTime]);

    try {
        $timeEntry = Time::create([
            'user_id' => Auth::id(),
            'punchIn' => $currentTime,
        ]);

        \Log::info('Time Entry Created:', ['entry' => $timeEntry->toArray()]);
    } catch (\Exception $e) {
        \Log::error('Failed to create Time Entry:', ['error' => $e->getMessage()]);
    }

        //$currentTime = Carbon::now('Asia/Tokyo');
        //\Log::info('PunchIn Time:', ['time' => $currentTime]);

        //Time::create([
            //'user_id' => Auth::id(),
            //'punchIn' => Carbon::now('Asia/Tokyo'),
        //]);

        return response()->json(['message' => '出勤しました']);
    }

    public function punchOut()
    {
        \Log::info('PunchOut method called for user:', ['user_id' => Auth::id()]);

        $attendance = Time::where('user_id', Auth::id())
                            ->whereNull('punchOut')
                            ->orderBy('punchIn', 'desc')
                            ->first();

                            if ($attendance) {
                                $currentTime = Carbon::now('Asia/Tokyo');
                                \Log::info('Updating PunchOut time for user:', ['user_id' => Auth::id(), 'time' => $currentTime]);
                        
                                try {
                                    $attendance->update([
                                        'punchOut' => $currentTime,
                                    ]);
                        
                                    \Log::info('Updated Attendance Record:', ['attendance' => $attendance->toArray()]);
                                } catch (\Exception $e) {
                                    \Log::error('Failed to update PunchOut:', ['error' => $e->getMessage()]);
                                }
        //if ($attendance) {
        //    \Log::info('Updating PunchOut time for user:', ['user_id' => Auth::id(), 'time' => Carbon::now('Asia/Tokyo')]);

        //    $attendance->update([
        //        'punchOut' => Carbon::now('Asia/Tokyo'),
        //   ]);
            return response()->json(['message' => '退勤しました']);
        }

        return response()->json(['message' => '退勤処理に失敗しました'], 400);
    }
}