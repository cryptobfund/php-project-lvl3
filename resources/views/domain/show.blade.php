@extends('layouts.app')

@section('title', 'SEOPageAnalyzer')

@section('content')
    @if ($status)
        <div class="alert alert-info" role="alert">
            <span>{{$status}}</span>
        </div>
    @endif
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Site: {{$domain->name}}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                @foreach ($domain as $key => $value)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$value}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <h2 class="mt-5 mb-3">Checks</h2>
        <form method="POST" action="{{ route("domains.check", $domain->id) }}">
            @csrf
            <input type="submit" class="btn btn-primary" value="Run check">
        </form>
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>Id</th>
                <th>Status Code</th>
                <th>h1</th>
                <th>Keywords</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
            @if ($checks)
            @foreach($checks as $check)
                <tr>
                    <td>{{ $check->id }}</td>
                    <td>{{ $check->status_code }}</td>
                    <td>{{ Str::limit($check->h1, 10) }}</td>
                    <td>{{ Str::limit($check->keywords, 30) }}</td>
                    <td>{{ Str::limit($check->description, 30) }}</td>
                    <td>{{ $check->created_at }}</td>
                </tr>
            @endforeach
            @endif
        </table>
    </div>
@endsection
