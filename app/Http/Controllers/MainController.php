<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\View\View;


class MainController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'mark' => Mark::all(),
            'models' => CarModel::with('mark')->get()
        ]);
    }
}
