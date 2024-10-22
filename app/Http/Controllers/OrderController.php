<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function index() {
        return view('order', [
            'orders' => Order::with(['user', 'carModel'])->where('user_id', Auth::id())->get()
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
        return redirect()->back();
    }
    public function upload(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $order = Order::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'model_id' => $validate['id'],
            ],
            [
                'status' => 'pending'
            ]
        );

        return redirect()->route('order.index');
    }

    public function destroy(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        Order::destroy($validate['id']);

        return redirect()->route('index');
    }
}
