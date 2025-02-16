<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\{Mark, CarModel};

class ModelController extends Controller
{
    public function index(): View
    {
        // Получаем все марки, где visibility = true
        $marks = Mark::where('visibility', true)->pluck('id');

        // Получаем модели автомобилей, которые соответствуют условиям
        $models = CarModel::whereIn('mark_id', $marks)
            ->where(function ($query) {
                $query->whereDoesntHave('orders')
                    ->orWhereHas('orders', function ($query) {
                        $query->where('status', 'cancelled')
                            ->latest()
                            ->limit(1);
                    });
            })
            ->whereDoesntHave('orders', function ($query) {
                $query->whereIn('status', ['pending', 'delivered'])
                    ->latest()
                    ->limit(1);
            })
            ->with('mark')
            ->paginate(5);

        $item = Mark::where('visibility', true)->get();
        if (!$item) {
            $item = null;
        }

        return view('admin.admin-model', [
            'models' => $models,
            'mark' => $item,
            'item' => null,
        ]);
    }

    public function upload(Request $request) {
        $validatedData = $request->validate([
            'mark_id' => 'required|integer',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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

        return redirect()->back()->with('success', 'Модель успешно добавлена');
    }

    public function destroy(Request $request) {
        $validatedData = $request->validate([
            'id' => 'required|integer|min:1',
        ]);
        CarModel::destroy($validatedData['id']);

        return redirect()->back()->with('success', 'Модель успешно удалена');
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'id' => 'required|integer|min:1',
            'mark_id' => 'required|integer|min:1',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'color' => 'required|string|max:255',
            'fuel_tank' => 'required|numeric|min:0',
            'mileage' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'transmission' => 'required|in:automatic,mechanic,robot',
            'drive' => 'required|in:FWD,RWD,AWD',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $timestamp = time();
            $photoPath = $file->storeAs('models', $timestamp. '.'. $file->getClientOriginalExtension(), 'public');

            CarModel::where('id', $request->id)->update([
                'mark_id' => $request->mark_id,
                'model' => $request->model,
                'price' => $request->price,
                'photo' => $photoPath,
                'color' => $request->color,
                'fuel_tank' => $request->fuel_tank,
                'mileage' => $request->mileage,
                'year' => $request->year,
                'transmission' => $request->transmission,
                'drive' => $request->drive,
                'discount' => $request->discount,
            ]);
        } else {
            CarModel::where('id', $request->id)->update([
                'mark_id' => $request->mark_id,
                'model' => $request->model,
                'price' => $request->price,
                'color' => $request->color,
                'fuel_tank' => $request->fuel_tank,
                'mileage' => $request->mileage,
                'year' => $request->year,
                'transmission' => $request->transmission,
                'drive' => $request->drive,
                'discount' => $request->discount,
            ]);
        }
        return redirect()->back()->with('success', 'Изменения успешно сохранены');
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
