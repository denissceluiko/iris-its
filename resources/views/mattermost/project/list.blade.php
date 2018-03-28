Projects in {{ $team->mm_domain }}

| Name    | Code              |
| :------ | :---------------- |
@foreach($projects as $project)
    | {{ $project->code }} | {{ $project->name }} |
@endforeach

