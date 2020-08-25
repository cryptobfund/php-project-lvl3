@extends('layouts.app')

@section('title', 'SEOPageAnalyzer')

@section('content')
    @if ($status)
        <h1>{{$status}}</h1>
    @endif
    <div>
        <h1>Site: {{$domain->name}}</h1>
        <table>
            @foreach ($domain as $key => $value)
                <tr>
                    <td>{{$key}}</td>
                    <td>{{$value}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
