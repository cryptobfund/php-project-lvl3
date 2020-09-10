@extends('layouts.app')

@section('title', 'SEOPageAnalyzer')

@section('content')
    @if ($status)
        <div class="alert alert-info" role="alert">
            <span>{{$status}}</span>
        </div>
    @endif
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Domains</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last check</th>
                    <th>Status Code</th>
                </tr>
                @foreach ($domains as $domain)
                    <tr>
                        <td>{{$domain->id}}</td>
                        <td>
                            <a href="{{route('domains.show', $domain->id)}}">{{$domain->name}}</a>
                        </td>
                        <td>{{$domain->created_at}}</td>
                        <td>{{$domain->status_code}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
