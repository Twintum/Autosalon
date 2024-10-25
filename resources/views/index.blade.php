@extends('layouts.app')

@section('content')
    @include('components.indexPage.carousel')
    @component('components.indexPage.filters', [
        'marks' => $mark
    ])
    @endcomponent
    <div class='home__cars-wrapper'>
        @foreach($models as $model)
            @if($model->mark->visibility == true)
                @component('components.card', [
                'model' => $model
                ])
                @endcomponent
            @endif
        @endforeach
    </div>
@endsection
