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

    public function product(int $id): View {
        return view('catalog.product', [
            'model' => CarModel::with('mark')->find($id)
        ]);
    }

    public function filter(Request $request): View
    {
        $searchQuery = $request->input('simple-search');
        $selectedMarks = $request->input('marks', []);

        $query = CarModel::query();

        // Поиск по словам в названии модели или марке
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('model', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('mark', function ($q) use ($searchQuery) {
                        $q->where('name', 'like', '%' . $searchQuery . '%');
                    });
            });
        }

        // Фильтрация по выбранным маркам
        if (!empty($selectedMarks)) {
            $query->whereIn('mark_id', $selectedMarks);
        }

        $cars = $query->get();

        $marks = Mark::all();

        return view('index', [
            'mark' => $marks,
            'models' => $cars
        ]);
    }
}
