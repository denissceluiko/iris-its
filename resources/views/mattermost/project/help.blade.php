Use `{{ $mm->getCommand() }}` with following options:

| Command | Usage             | Example |
| :------ | :---------------- | :------ |
| help    | Show this message | {{ $mm->getCommand() }} help
| new     | project_code project_name | {{ $mm->getCommand() }} new NYP New Year's party |

For example `{{ $mm->getCommand() }} help` displays this message.