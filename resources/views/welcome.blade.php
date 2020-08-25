@extends('layouts.app')

@section('title', 'SEOPageAnalyzer')

@section('content')


    {{Form::open(['url' => route('domains.store'), 'method' => 'POST'])}}
    {{Form::url('name')}}
    {{Form::submit('Check')}}
    {{Form::close()}}
@endsection
