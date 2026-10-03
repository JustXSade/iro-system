<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ඉස්සරහට අද දවසේ Data, Naya මුදල් වගේ දේවල් Database එකෙන් අරන් UI එකට යවන්නේ මෙතනින්.
        // දැනට අපි කෙලින්ම UI Page එක load කරමු.
        return view('dashboard');
    }
}