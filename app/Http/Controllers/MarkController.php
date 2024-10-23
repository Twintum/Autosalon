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

        return redirect()->back()->with('success', 'Марка успешно добавлена');
    }

    public function destroy(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $info = Mark::find($request->id);
        $info->delete();

        return redirect()->back()->with('success', 'Марка успешно удалена');
    }

    public function update(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:svg|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $timestamp = time();
            $photoPath = $file->storeAs('marks', $timestamp. '.'. $file->getClientOriginalExtension(), 'public');

            Mark::where('id', $request->id)->update([
                'name' => $request->name,
                'photo' => $photoPath,
            ]);
        } else {
            Mark::where('id', $request->id)->update([
                'name' => $request->name,
            ]);
        }

        return redirect()->back()->with('success', 'Изменения успешно сохранены');
    }

    public function search(Request $request) {
        $validate = $request->validate([
            'word' => 'required|string|max:255',
        ]);

        $word = $request->input('word');

        return view('admin.admin-mark', [
            'marks' => Mark::where('name', 'like', '%' . $word . '%')->get()
        ]);
    }
}
