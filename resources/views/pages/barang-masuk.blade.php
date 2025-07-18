@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Barang Masuk',
        'breadcrumbs' => [['title' => 'Barang Masuk']],
        'content' => 'barang-masuk-content',
    ])
@endsection
