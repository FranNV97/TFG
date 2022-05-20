@extends('layouts.app')

@section('content')

<processes :usuario= "{{ $user->toJson() }}" :permisos= "{{ $permisos }}"></processes>

@endsection
