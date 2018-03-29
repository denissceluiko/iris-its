Tasks assigned to you.

| Code | Name | Status    | Deadline  |
| :--- | :--- | :-------- | :-------- |
@foreach($tasks as $task)
    | $task->code | $task->name | $task->status | $task->deadline_at |
@endforeach