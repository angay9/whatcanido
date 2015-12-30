@extends('layouts.app')

@section('content')
    <event-view :event="{{ $event }}"></event-view>
@endsection
