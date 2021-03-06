@extends('mattermost.layout')
@section('content')
Your tasks in {{ $team->mm_domain }}

| Code    | Name              | Status | Created By | Deadline |
| :------ | :---------------- | ------ | ---------- | -------: |
@foreach($tasks as $task)
| {{ $task->code }} | {{ $task->name }} | {{ ucfirst($task->status) }} | {{ $task->creator->name }} |  {{ ( $task->deadline_at ? $task->deadline_at->format('d.m.Y.') : '-' ) }} |
@endforeach
@endsection