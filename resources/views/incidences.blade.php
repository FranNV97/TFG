@extends('layouts.app')

@section('content')

<incidences :usuario= "{{ $user->toJson() }}" :permisos= "{{ $permisos }}"></incidences>

@endsection
