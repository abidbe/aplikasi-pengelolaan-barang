@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Edit Barang Masuk',
        'breadcrumbs' => [['title' => 'Barang Masuk'], ['title' => 'Edit Barang Masuk']],
        'content' => 'edit-barang-masuk-content',
    ])
@endsection
