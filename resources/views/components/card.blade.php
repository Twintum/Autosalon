<div class="car-card group">
    <div class="car-card__content">
        <h2 class="car-card__content-title">
            {{ $model->mark->name }} {{ $model->model }}
        </h2>
    </div>

    @if($model->discount == 0)
        <p
            class="flex mt-6 text-[24px] leading-[38px] font-extrabold"
        >
            ₽{{ $model->price }}
        </p>
    @else
        <p  class="text-2xl font-extrabold text-primary-800 sm:text-3xl">
            ₽{{ round($model->price - ($model->price * ($model->discount / 100))) }}
        </p>
        <p
            class="flex text-[24px] leading-[38px] font-extrabold line-through"
        >
            ₽{{ $model->price }}
        </p>
    @endif

    <div class='relative w-full h-40 my-3 object-contain'>
        <img src="{{ asset('/storage/' . $model->photo) }}" alt="{{ $model->model }}">
    </div>

    <div class='relative flex w-full mt-2'>
        <div class='flex group-hover:invisible w-full justify-between text-grey'>
            <div class='flex flex-col justify-center items-center gap-2'>
                <img src="{{ asset('/img/svg/streering-wheel.svg') }}" width="20" height="20" alt="tire">
                <p class='text-[14px] leading-[17px]'>
                    {{ [
                        'mechanic' => 'Механика',
                        'automatic' => 'Автомат',
                        'robot' => 'Робот'
                    ][$model->transmission] ?? $model->transmission }}
                </p>
            </div>
            <div class="car-card__icon">
                <img src="{{ asset('/img/svg/tire.svg') }}" width="20" height="20" alt="tire">
                <p class="car-card__icon-text">{{ $model->drive }}</p>
            </div>
            <div class="car-card__icon">
                <img src="{{ asset('/img/svg/gas.svg') }}" width="20" height="20" alt="gas">
                <p class="car-card__icon-text">{{ $model->fuel_tank }} Литр</p>
            </div>
        </div>
        <div class="flex justify-between car-card__btn-container">
            <a href="{{ route('product', ['id' => $model->id]) }}">
                <span class='w-full flex-1 text-black dark:text-white text-[14px] leading-[17px] font-bold border border-gray-200 rounded-lg p-2'>Открыть</span>
            </a>

            <button>
                <span class='w-full flex-1 text-white text-[14px] leading-[17px] bg-primary-700 font-bold rounded-lg p-2 hover:bg-primary-800'>Забронировать</span>
            </button>
        </div>
    </div>
</div>
