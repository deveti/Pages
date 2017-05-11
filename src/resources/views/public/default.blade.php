@extends('pages::public.master')

@section('page')

    @if($children)
    <ul class="nav nav-subpages">
        @foreach ($children as $child)
        @include('pages::public._list-item', array('child' => $child))
        @endforeach
    </ul>
    @endif

    {!! $page->present()->body !!}
    @include('files::public._files', ['model' => $page])

@endsection
