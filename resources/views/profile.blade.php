@extends('layouts.app')

@section('content')

<profile :usuario= "{{ $user->toJson() }}"></profile>

@endsection
