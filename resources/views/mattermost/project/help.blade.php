@extends('mattermost.layout')
@section('content')
Use `{{ $mm->getCommand() }}` with following options:

| Option  | Arguments                 | Example                                            | Usage                                                        | Aliases     |
| ------- | ------------------------- | -------------------------------------------------- | ------------------------------------------------------------ | ----------- |
| help    | -                         | {{ $mm->getCommand() }} help                       | Displays this message                                        | -           |
| list    | -                         | {{ $mm->getCommand() }} list NYP                   | Lists all team's projects.                                   | -           |
| new     | project_code project_name | {{ $mm->getCommand() }} new NYP New Year's party   | Creates a new project.                                       | add, create |

Option's alias is an alternative word you can use to invoke the option. E.g., `{{ $mm->getCommand() }} new` and `{{ $mm->getCommand() }} add` both can be used to create a new project.
For example use `{{ $mm->getCommand() }} help` to display this message.
@endsection