@props(['title' => null, 'header' => null, 'subheader' => null])

@extends('layouts.app')

@section('content')
    {{ $slot }}
@endsection
