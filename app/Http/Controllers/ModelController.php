<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\{Mark, CarModel};

class ModelController extends Controller
{
    public function index(): View {
        return view('admin.admin-model', [
            'models' => CarModel::with('mark')->paginate(5),
            'mark' => Mark::all(),
        ]);
    }

    public function upload(Request $request) {
        $validatedData = $request->validate([
            'mark_id' => 'required|integer',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'required|string|max:255',
            'fuel_tank' => 'required|numeric|min:0',
            'mileage' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'transmission' => 'required|in:automatic,mechanic,robot',
            'drive' => 'required|in:FWD,RWD,AWD',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);

        $file = $request->file('photo');
        $timestamp = time();
        $photoPath = $file->storeAs('models', $timestamp. '.'. $file->getClientOriginalExtension(), 'public');
        $car = new CarModel($validatedData);
        $car->photo = $photoPath;
        $car->save();

        return redirect()->back();
    }

    public function destroy(Request $request) {
        $validatedData = $request->validate([
            'id' => 'required|integer|min:1',
        ]);
        CarModel::destroy($validatedData['id']);
        return redirect()->back();
    }

    public function search(Request $request) {
        $validate = $request->validate([
            'word' => 'required|string|max:255',
        ]);

        $word = $request->input('word');

        return view('admin.admin-model', [
            'models' => CarModel::where('model', 'like', '%' . $word . '%')->paginate(5),
            'mark' => Mark::all(),
        ]);
    }
}
