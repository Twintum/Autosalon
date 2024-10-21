@extends('layouts.app')

@section('content')
    @include('components.indexPage.carousel')
    @include('components.indexPage.filters')
    <div class='home__cars-wrapper'>
        @component('components.card', [
        'name' => 'Honda Odyssey',
        'price' => 520000,
        'transmission' => 'автомат',
        'drive' => 'AWD',
        'MPG' => 21,
        'photo' => '/img/tovar/12.png'
        ])
        @endcomponent
    </div>
@endsection
