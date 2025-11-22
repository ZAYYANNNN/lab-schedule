@props(['title' => null, 'header' => null])

@extends('layouts.superadmin')

@section('content')
    {{ $slot }}
@endsection
