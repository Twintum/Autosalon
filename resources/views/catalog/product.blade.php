@extends('layouts.app')

@section('content')
<section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
            <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                <img src="{{ asset('/storage/' . $model->photo) }}" alt="{{ $model->mark->name }} {{ $model->model }}" class="w-full">
            </div>

            <div class="mt-6 sm:mt-8 lg:mt-0">
                <h1
                    class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                >
                    {{ $model->mark->name }} {{ $model->model }} {{ $model->year }} года
                </h1>
                <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                    @if($model->discount == 0)
                    <p
                        class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
                    >
                        ₽{{ $model->price }}
                    </p>
                    @else
                        <div class="flex gap-5">
                            <p  class="text-2xl font-extrabold text-primary-800 sm:text-3xl">
                                ₽{{ round($model->price - ($model->price * ($model->discount / 100))) }}
                            </p>
                            <p
                                class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white line-through"
                            >
                                ₽{{ $model->price }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                    <form action="{{ route('order.upload') }}" method="POST">
                        @csrf
                        <input name="id" value="{{ $model->id }}" class="hidden">
                        <button
                            title=""
                            class="text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                            role="button"
                        >
                            <svg
                                class="w-5 h-5 -ms-2 me-2"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"
                                />
                            </svg>

                            Забронировать
                        </button>
                    </form>
                </div>

                <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
                <div class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Марка</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->mark->name }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Модель</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->model }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Пробег</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->mileage }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Год</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->year }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Коробка передач</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ [
                            'mechanic' => 'Механика',
                            'automatic' => 'Автомат',
                            'robot' => 'Робот'
                        ][$model->transmission] ?? $model->transmission }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Привод</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ [
                            'FWD' => 'Передний',
                            'RWD' => 'Задний',
                            'AWD' => 'Полный'
                        ][$model->drive] ?? $model->drive }}</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Объем топливного бака</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->fuel_tank }} л.</dd>
                    </dl>
                    <dl class="sm:flex items-center justify-between gap-4">
                        <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Цвет</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $model->color }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
