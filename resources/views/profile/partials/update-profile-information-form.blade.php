<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Информация') }}
        </h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="first_name" :value="__('Имя')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Фамилия')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autocomplete="last_name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Email')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="username" oninput="formatPhoneNumber(this)"/>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-primary-700 hover:bg-primary-800">{{ __('Сохранить') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Сохранено.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function formatPhoneNumber(input) {
            // Удаляем все символы, кроме цифр
            let phoneNumber = input.value.replace(/\D/g, '');

            // Если номер начинается с 7, добавляем +7
            if (phoneNumber.startsWith('7')) {
                phoneNumber = '+7' + phoneNumber.slice(1);
            } else if (phoneNumber.startsWith('8')) {
                phoneNumber = '+7' + phoneNumber.slice(1);
            } else if (!phoneNumber.startsWith('+7')) {
                phoneNumber = '+7' + phoneNumber;
            }

            // Форматируем номер
            if (phoneNumber.length > 2) {
                phoneNumber = phoneNumber.slice(0, 2) + ' ' + phoneNumber.slice(2);
            }
            if (phoneNumber.length > 6) {
                phoneNumber = phoneNumber.slice(0, 6) + ' ' + phoneNumber.slice(6);
            }
            if (phoneNumber.length > 10) {
                phoneNumber = phoneNumber.slice(0, 10) + ' ' + phoneNumber.slice(10);
            }

            // Обновляем значение ввода
            input.value = phoneNumber;
        }
    </script>
</section>
