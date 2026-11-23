@extends('layouts.app')

@section('title', 'Rookie Tennis — ' . $activeTournament->name)

@section('content')
    @if($activeTab === 'home')
        @include('tournaments.partials.home')
    @elseif($activeTab === 'tournaments')
        @include('tournaments.partials.list')
    @elseif($activeTab === 'participants')
        @include('tournaments.partials.participants')
    @elseif($activeTab === 'group')
        @include('tournaments.partials.groups')
    @elseif($activeTab === 'bracket')
        @include('tournaments.partials.bracket')
    @elseif($activeTab === 'schedule')
        @include('tournaments.partials.schedule')
    @else
        @include('tournaments.partials.home')
    @endif

    @push('modals')
        @include('tournaments.modals.all_modals')
    @endpush
@endsection
