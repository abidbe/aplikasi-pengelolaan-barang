@extends('layouts.app')

@section('content')
    @include('layouts.main', [
        'title' => 'Kategori',
        'breadcrumbs' => [['title' => 'Master Data'], ['title' => 'Kategori']],
        'content' => 'kategori-content',
    ])
@endsection
