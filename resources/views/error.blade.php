@extends('layouts.app')

@section('title', 'Error Page')

@section('content')
<div class="step">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <span>No errors found, something else has gone wrong</span>
    @endif
</div>
@endsection