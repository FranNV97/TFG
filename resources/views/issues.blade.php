@extends('layouts.app')

@section('content')

<issues :usuario= "{{ $user->toJson() }}" :permisos= "{{ $permisos }}"></issues>

@endsection
