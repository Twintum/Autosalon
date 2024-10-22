
@extends('layouts.app')

@section('content')
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Модель
                </th>
                <th scope="col" class="px-6 py-3">
                    Фото
                </th>
                <th scope="col" class="px-6 py-3">
                    Дата бронирования
                </th>
                <th scope="col" class="px-6 py-3">
                    Цена
                </th>
                <th scope="col" class="px-6 py-3">
                    Статус
                </th>
                <th scope="col" class="px-6 py-3">
                    Действия
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $item) @endforeach
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $item->carModel->mark->name }} {{ $item->carModel->model }}
                </th>
                <td class="px-6 py-4">
                    <img src="{{ asset('/storage/' .  $item->carModel->photo) }}" alt="" class="w-20">
                </td>
                <td class="px-6 py-4">
                    {{ $item->created_at }}
                </td>
                <td class="px-6 py-4">
                    ₽ {{ $item->carModel->price }}
                </td>
                <td class="px-6 py-4">
                    @if ($item->status === 'pending')
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">Ожидает</span>
                    @elseif ($item->status === 'delivered')
                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">Успешно</span>
                    @elseif ($item->status === 'cancelled')
                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:border-red-400 dark:bg-gray-700 dark:text-red-400">Отменён</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($item->status == 'pending')
                        <form action="{{ route('order.destroy') }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <input name="id" class="hidden" value="{{ $item->id }}">
                            <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Отменить</button>
                        </form>
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
