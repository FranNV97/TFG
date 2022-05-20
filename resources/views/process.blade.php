@extends('layouts.app')

@section('content')

<process :usuario= "{{ $user->toJson() }}" :proceso= "{{ $process->toJson() }}" :tareas= "{{ $tasks->toJson() }}" :documentos= "{{ $documents->toJson() }}" :objetivos= "{{ $objectives->toJson() }}" :permisos= "{{ $permisos }}"></process>

@endsection
