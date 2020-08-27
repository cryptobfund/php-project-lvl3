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

    <form method="POST" action="{{ route("domains.check", $domain->id) }}">
        @csrf
        <input type="submit" value="Run check">
    </form>
    <table>
        <thead>
        <tr>
            <th>Id</th>
            <th>Status Code</th>
            <th>h1</th>
            <th>Keywords</th>
            <th>Description</th>
            <th>Created At</th>
        </tr>
        </thead>
        <tbody>
        @if ($checks)
        @foreach($checks as $check)
            <tr>
                <td>{{ $check->id }}</td>
                <td>{{ $check->status_code }}</td>
                <td>{{ $check->h1 }}</td>
                <td>{{ $check->keywords }}</td>
                <td>{{ $check->description }}</td>
                <td>{{ $check->created_at }}</td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
@endsection
