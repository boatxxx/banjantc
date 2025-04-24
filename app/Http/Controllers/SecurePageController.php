<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SecurePageController extends Controller
{
    
    public function show()
    {
        if (!Session::get('pin_verified')) {
            // เก็บ URL หน้าก่อนหน้าลงใน session
            Session::put('previous_url', url()->previous());
            return view('enter-pin');
        }

        return view('secure-content');
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        if ($request->pin === '4122') {
            Session::put('pin_verified', true);

            // ✅ กลับไปยังหน้าที่เคยอยู่ก่อนหน้านี้
            $previous = Session::pull('previous_url', '/'); // ถ้าไม่เจอใช้หน้า `/` แทน
            return redirect($previous);
        }

        return back()->withErrors(['pin' => 'รหัส PIN ไม่ถูกต้อง'])->withInput();
    }
    
}
