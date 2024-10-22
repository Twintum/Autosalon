<div class="car-card group">
    <div class="car-card__content">
        <h2 class="car-card__content-title">
            {{ $name }}
        </h2>
    </div>

    <p class='flex mt-6 text-[24px] leading-[38px] font-extrabold'>
        <span class='self-start text-[14px] leading-[17px] font-semibold'>₽</span>
        {{ $price }}
    </p>

    <div class='relative w-full h-40 my-3 object-contain'>
        <img src="{{ asset($photo)}}" alt="{{ $name }}">
    </div>

    <div class='relative flex w-full mt-2'>
        <div class='flex group-hover:invisible w-full justify-between text-grey'>
            <div class='flex flex-col justify-center items-center gap-2'>
                <img src="{{ asset('/img/svg/streering-wheel.svg') }}" width="20" height="20" alt="tire">
                <p class='text-[14px] leading-[17px]'>
                    {{ $transmission }}
                </p>
            </div>
            <div class="car-card__icon">
                <img src="{{ asset('/img/svg/tire.svg') }}" width="20" height="20" alt="tire">
                <p class="car-card__icon-text">{{ $drive }}</p>
            </div>
            <div class="car-card__icon">
                <img src="{{ asset('/img/svg/gas.svg') }}" width="20" height="20" alt="gas">
                <p class="car-card__icon-text">{{ $MPG }} Литр</p>
            </div>
        </div>
        <div class="flex justify-between  car-card__btn-container">
            <a href="{{ route('catalog.index', ['id' => 1]) }}">
                <span class='w-full flex-1 text-black dark:text-white text-[14px] leading-[17px] font-bold border border-gray-200 rounded-lg p-2'>Открыть</span>
            </a>

            <button>
                <span class='w-full flex-1 text-white text-[14px] leading-[17px] bg-primary-700 font-bold rounded-lg p-2 hover:bg-primary-800'>Забронировать</span>
            </button>
        </div>
    </div>
</div>
