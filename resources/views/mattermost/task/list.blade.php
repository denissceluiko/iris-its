@extends('mattermost.layout')
@section('content')
Tasks in {{ $project->name }} of {{ $team->mm_domain }}

| Code    | Name              | Status | Assigned to | Created By | Deadline |
| :------ | :---------------- | ------ | ----------- | ---------- | -------: |
@foreach($tasks as $task)
| {{ $task->code }} | {{ $task->name }} | {{ ucfirst($task->status) }} | {{ ($task->assignee ? $task->assignee->name : '-') }} | {{ $task->creator->name }} |  {{ ( $task->deadline_at ? $task->deadline_at->format('d.m.Y.') : '-' ) }} |
@endforeach
@endsection
