@extends('layouts.app')

@section('content')
<challenges :data="{{$data}}"></challenges>

@endsection