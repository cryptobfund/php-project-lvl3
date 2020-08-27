@extends('layouts.app')

@section('title', 'SEOPageAnalyzer')

@section('content')
    @if ($status)
        <h1>{{$status}}</h1>
    @endif
        <div>
            <table>
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

@endsection
