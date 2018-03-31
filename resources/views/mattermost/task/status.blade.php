@extends('mattermost.layout')
@section('content')
List of available actions to change task's status:

| Option   | Example                               | Usage                        |
| -------- |  ------------------------------------ | ---------------------------- |
| start    | {{ $mm->getCommand() }} start NYP-42  | Start working on NYP-42.     |
| stop     | {{ $mm->getCommand() }} stop NYP-42   | Stop working on NYP-42.      |
| done     | {{ $mm->getCommand() }} done NYP-42   | Mark NYP-42 as done.         |

@endsection