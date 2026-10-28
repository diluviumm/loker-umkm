@extends('errors.layout')

@section('title', 'Terlalu banyak permintaan')
@section('heading', 'Terlalu banyak permintaan')
@section('code', '429')
@section('message', 'Coba lagi beberapa saat lagi. Permintaan dibatasi untuk keamanan.')
@section('icon', 'fa-hourglass-half')
@section('badge_class', 'bg-amber-100 text-amber-600')
