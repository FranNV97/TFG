@extends('layouts.app')

@section('content')

<fivewhys :usuario= "{{ $user->toJson() }}" :problema= "{{ $issue->toJson() }}" :porques= "{{ json_encode($whys) }}"></fivewhys>

@endsection
