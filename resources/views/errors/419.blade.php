@extends('errors._layout')
@section('code', '419')
@section('heading', 'Page expired')
@section('message', 'Your session timed out for security. Please refresh the page and try again.')
@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-acc">↻ Go back &amp; retry</a>
    <a href="{{ url('/') }}" class="btn btn-ghost">Home</a>
@endsection
