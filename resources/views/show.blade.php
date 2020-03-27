@extends('layouts.app')

@section('title', 'Here is your data')

@section('content')
<div class="step">
    @if(isset($data['productions']))
        <ul>
            @foreach ($data['productions'] as $production)
                <li>
                    <b>Title: {{ $production['title'] }}</b>
                </li>
                <ul>
                    @foreach ($production['sites'] as $site)
                        <li>Site: {{ $site['name'] }}</li>
                        <ul>
                            @foreach ($site['shoot_dates'] as $shoot_date)
                                <li>Shoot date: {{ $shoot_date }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </ul>
            @endforeach
        </ul>
    @else
        <div class="alert alert-danger">
            <ul>
                <li>No data found, something has gone wrong</li>
            </ul>
        </div>
    @endif
</div>
@endsection