@extends('mattermost.layout')
@section('content')
Use `{{ $mm->getCommand() }}` with following options:

| Option  | Arguments                 | Example                                            | Usage                                                        | Aliases     |
| ------- | ------------------------- | -------------------------------------------------- | ------------------------------------------------------------ | ----------- |
| help    | -                         | {{ $mm->getCommand() }} help                       | Display this message                                         | -           |
| assign  | task_code user.name       | {{ $mm->getCommand() }} assign NYP-42 @john.smith  | Assigns task to a user.                                      | -           |
| done    | task_code                 | {{ $mm->getCommand() }} done NYP-42                | Changes task's status to _Done_.                             | -           |
| drop    | task_code                 | {{ $mm->getCommand() }} drop NYP-42                | Unassigns you from the task.                                 | -           |
| intro   | -                         | {{ $mm->getCommand() }} intro                      | Shows introduction to Mattermost ITS.                        | -           |
| list    | project_code              | {{ $mm->getCommand() }} list NYP                   | Lists all project's tasks.                                   | from, in    |
| my      | -                         | {{ $mm->getCommand() }} my                         | Lists all tasks assigned to you.                             | mine        |
| new     | project_code task_name    | {{ $mm->getCommand() }} new NYP Buy gingerbread    | Creates a new task.                                          | add, create |
| start   | task_code                 | {{ $mm->getCommand() }} start NYP-42               | Assigns task to you and changes its status to _In progress_. | -           |
| status  | task_code                 | {{ $mm->getCommand() }} status NYP-42              | Shows task's status.                                         | -           |
| stop    | task_code                 | {{ $mm->getCommand() }} stop NYP-42                | Changes task's status to _On hold_.                          | -           |
| take    | task_code                 | {{ $mm->getCommand() }} take NYP-42                | Assigns task to you.                                         | get         |

Option's alias is an alternative word you can use to invoke the option. E.g., `{{ $mm->getCommand() }} new` and `{{ $mm->getCommand() }} add` both cen be used to create a new task.
For example `{{ $mm->getCommand() }} help` displays this message.
@endsection