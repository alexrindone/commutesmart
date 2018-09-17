@extends('layouts.app')

@section('content')
<companies :data="{{$data}}"></companies>

@endsection