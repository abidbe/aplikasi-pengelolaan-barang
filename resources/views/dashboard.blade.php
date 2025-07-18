@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Dashboard',
        'breadcrumbs' => [['title' => 'Dashboard']],
        'content' => 'dashboard-content',
    ])
@endsection
