@extends('layouts.app')

@section('content')

<valueadded :usuario= "{{ $user->toJson() }}" :proceso= "{{ $process->toJson() }}" :tareas= "{{ $tasks->toJson() }}" :analisis= "{{ $analysis->toJson() }}"></valueadded>

@endsection
