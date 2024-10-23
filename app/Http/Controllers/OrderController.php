<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function index() {
        return view('order', [
            'orders' => Order::with(['user', 'carModel'])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get()
        ]);
    }
    public function admin() {
        return view('admin.admin-orders', [
            'orders' => Order::with(['user', 'carModel'])->where('status', 'pending')->get()
        ]);
    }

    public function delivered(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        Order::where('id', $validate['id'])->update(['status' => 'delivered']);

        return redirect()->back()->with('success', 'Запись успешно запланирована');
    }
    public function upload(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('model_id', $validate['id'])
            ->first();

        if ($order) {
            if ($order->status === 'pending') {
                return redirect()->route('order.index')->with('error', 'Ошибка бронирования');
            } elseif ($order->status === 'delivered') {
                return redirect()->route('order.index')->with('error', 'Ошибка бронирования');
            } elseif ($order->status === 'cancelled') {
                // Если заказ был отменен, создаем новый заказ
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'model_id' => $validate['id'],
                    'status' => 'pending'
                ]);
            }
        } else {
            // Если заказа нет, создаем новый заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'model_id' => $validate['id'],
                'status' => 'pending'
            ]);
        }

        return redirect()->route('order.index')->with('success', 'Бронирование успешно');
    }

    public function destroy(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $order = Order::find($validate['id']);

        if ($order) {
            $order->status = 'cancelled';
            $order->save();
        }

        return redirect()->route('index')->with('success', 'Бронирование отменено');
    }
}
