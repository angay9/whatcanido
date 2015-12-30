@extends('layouts.app')

@section('content')
    <div class="container-fluid container-pad">
        <div class="row">
            <div class="col-md-4">
                <h4>ORGANIZED BY ME</h4>
            </div>
            <div class="col-md-8">
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <event-list :paginator="{{ json_encode($paginator) }}" url="{{ route('user.events') }}"></event-list>
            </div>
        </div>
    </div>
@endsection
