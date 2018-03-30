@extends('mattermost.layout')
@section('content')
Use `{{ $mm->getCommand() }}` with following options:

| Option  | Arguments  | Example             | Usage |
| ------- | ---------- | ------------------- | ------ |
| help    | -          | {{ $mm->getCommand() }} help | Display this message
| new     | project_code project_name | {{ $mm->getCommand() }} new NYP New Year's party | Create a new project
| list    | -          | {{ $mm->getCommand() }} list | List all team's projects

For example `{{ $mm->getCommand() }} help` displays this message.
@endsection