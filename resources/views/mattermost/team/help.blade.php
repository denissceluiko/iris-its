@extends('mattermost.layout')
@section('content')
Use `{{ $mm->getCommand() }}` with following options:

| Option  | Arguments                 | Example                                            | Usage                                                        | Aliases     |
| ------- | ------------------------- | -------------------------------------------------- | ------------------------------------------------------------ | ----------- |
| help    | -                         | {{ $mm->getCommand() }} help                       | Display this message                                         | -           |

Option's alias is an alternative word you can use to invoke the option. E.g., `{{ $mm->getCommand() }} new` and `{{ $mm->getCommand() }} add` both cen be used to create a new task.
For example `{{ $mm->getCommand() }} help` displays this message.
@endsection