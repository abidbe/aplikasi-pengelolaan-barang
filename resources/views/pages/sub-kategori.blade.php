@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Sub Kategori',
        'breadcrumbs' => [['title' => 'Master Data'], ['title' => 'Sub Kategori']],
        'content' => 'sub-kategori-content',
    ])
@endsection
