<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Mark;

class MarkController extends Controller
{
    public function index(): View {
        return view('admin.admin-mark',[
            'marks'=>Mark::all()
        ]);
    }
    public function upload(Request $request) {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:svg|max:2048',
        ]);

        $file = $request->file('photo');
        $timestamp = time();
        $photoPath = $file->storeAs('marks', $timestamp. '.'. $file->getClientOriginalExtension(), 'public');

        Mark::create([
            'name' => $request->name,
            'photo' => $photoPath,
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $info = Mark::find($request->id);
        $info->delete();

        return redirect()->back();
    }
}
