@extends('layouts.app')

@section('content')

<notifications :usuario= "{{ $user->toJson() }}" :notificaciones= "{{ $notifications->toJson() }}" ></notifications>

@endsection
