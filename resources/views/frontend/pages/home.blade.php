@extends('frontend.layouts.app')

@section('content')
    @foreach($sections as $section)
        @php $view = 'frontend.sections.'.str_replace('_', '-', $section->type); @endphp
        @if(view()->exists($view))
            @include($view, ['section' => $section])
        @endif
    @endforeach
@endsection
