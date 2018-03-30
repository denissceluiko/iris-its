@extends('mattermost.layout')
@section('content')
Projects in {{ $team->mm_domain }}

| Code    | Name              |
| :------ | :---------------- |
@foreach($projects as $project)
| {{ $project->code }} | {{ $project->name }} |
@endforeach
@endsection
