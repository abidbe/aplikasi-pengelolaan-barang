@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Tambah Barang Masuk',
        'breadcrumbs' => [['title' => 'Barang Masuk'], ['title' => 'Tambah Barang Masuk']],
        'content' => 'create-barang-masuk-content',
    ])
@endsection
