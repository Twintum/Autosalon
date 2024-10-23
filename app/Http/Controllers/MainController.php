<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\Mark;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class MainController extends Controller
{
    public function index(): View
    {
        $models = CarModel::whereDoesntHave('orders')
            ->orWhereHas('orders', function ($query) {
                $query->where('status', 'cancelled')
                    ->latest()
                    ->limit(1);
            })
            ->whereDoesntHave('orders', function ($query) {
                $query->whereIn('status', ['pending', 'delivered'])
                    ->latest()
                    ->limit(1);
            })
            ->get();

        return view('index', [
            'mark' => Mark::all(),
            'models' => $models
        ]);
    }
    public function product(int $id): View {
        // Получаем модель автомобиля с маркой
        $carModel = CarModel::with('mark')->find($id);

        // Проверяем, существует ли модель
        if (!$carModel) {
            abort(404); // Если модель не найдена, возвращаем 404
        }

        // Проверяем наличие заказов со статусами 'pending' или 'delivered'
        $hasActiveOrders = $carModel->orders()->whereIn('status', ['pending', 'delivered'])->exists();

        // Если есть активные заказы, возвращаем 404
        if ($hasActiveOrders) {
            abort(404);
        }

        // Если нет активных заказов, отображаем страницу продукта
        return view('catalog.product', [
            'model' => $carModel
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

        // Логика с заказами
        $query->whereDoesntHave('orders')
            ->orWhereHas('orders', function ($query) {
                $query->where('status', 'cancelled')
                    ->latest()
                    ->limit(1);
            })
            ->whereDoesntHave('orders', function ($query) {
                $query->whereIn('status', ['pending', 'delivered'])
                    ->latest()
                    ->limit(1);
            });

        $cars = $query->get();

        $marks = Mark::all();

        return view('index', [
            'mark' => $marks,
            'models' => $cars
        ]);
    }
}
