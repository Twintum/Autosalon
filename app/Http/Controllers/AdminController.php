<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;


class AdminController extends Controller
{
    public function index() {
        return Excel::download(new ReportExport, 'report.xlsx');
    }
}
