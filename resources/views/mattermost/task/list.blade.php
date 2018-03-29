Tasks in {{ $project->name }} of {{ $team->mm_domain }}

| Code    | Name              | Status | Assigned to | Created By | Deadline |
| :------ | :---------------- | ------ | ----------- | ---------- | -------: |
@foreach($tasks as $task)
    | {{ $task->code }} | {{ $task->name }} | {{ $task->status }} | {{ $task->assignee->name }} | {{ $task->creator->name }} |  {{ $task->assignee->deadline_at->format('d.m.Y.') }} |
@endforeach

