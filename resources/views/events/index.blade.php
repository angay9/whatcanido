@extends('layouts.app')

@section('content')
    <div class="container-fluid container-pad">
        <div class="row">
            <div class="col-md-12">
                <h4>UPCOMING EVENTS</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <event-list :paginator="{{ json_encode($paginator) }}" url="{{ route('events.index') }}"></event-list>
            </div>
        </div>
    </div>
@endsection
