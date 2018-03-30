@extends('mattermost.layout')
@section('content')
Use `{{ $mm->getCommand() }}` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| all     | Show all tasks    |

For example `{{ $mm->getCommand() }} help` displays this message.
@endsection