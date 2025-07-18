@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Users',
        'breadcrumbs' => [['title' => 'Users']],
        'content' => 'users-content',
    ])
@endsection
