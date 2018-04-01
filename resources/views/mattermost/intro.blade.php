## Short intro into Mattermost ITS
ITS - issue tracking system.
Primary purpose of this its (as any other) is to make project management easier and make it available using Mattermost slash commands.
#### Basic workflow
Structure is simple - there are teams, currently you're in {{ $team->name }}. Teams have projects. Projects have tasks. That's it.
Let's say we want to create a _project_ called `New Year's party`. In this ITS all _projects_ have _codes_. Therefore let's give our party's project a _code_ - `NYP`.
> `/p new NYP New Year's party`

Will create this project for us, we can now see it project in the project list:
> `/p list`

As we now have a party preparations to do, let's make a list of _tasks_.
We need to invite guests, right?
> `{{ $mm->getCommand() }} new NYP `

This will create a task in `NYP` project.
> `{{ $mm->getCommand() }} new NYP Buy snacks`
> `{{ $mm->getCommand() }} new NYP Decorate`
> `{{ $mm->getCommand() }} new NYP Buy presents`

After adding all the tasks we can see a list of them by typing
> `{{ $mm->getCommand() }} list NYP`

which will output a table like

| Code    | Name              | Status | Assigned to | Created By | Deadline |
| :------ | :---------------- | ------ | ----------- | ---------- | -------: |
| NYP-1   | Invite guests     | New    | your.name   | your.name  |  -       |
| NYP-2   | Buy snacks        | New    | your.name   | your.name  |  -       |
| NYP-3   | Decorate          | New    | your.name   | your.name  |  -       |
| NYP-4   | Buy presents      | New    | your.name   | your.name  |  -       |

Now there are three new pieces of data - columns _Status_, _Assigned to_ and _Created by_. _Created by_ is the easiest - its you, as you've just _created_ this task. _Status_ is also pretty straight forward - all new tasks nave a status _New_. Right now there are 4 statuses - _New_, _In progress_, _On hold_ and _Done_. When you create a task it's _New_, when you start working on a task its _In progress_, when you finish it's _Done_. If for some reason you have to stop working on a task for a while it's _On hold_ until you or someone else can pick it up again. Makes sense?

It's hard to have a party by yourself, let's start inviting guests:
> `{{ $mm->getCommand() }} start NYP-1`

If you try `{{ $mm->getCommand() }} list NYP` it will show `NYP-1` as

| Code    | Name              | Status      | Assigned to | Created By | Deadline |
| :------ | :---------------- | ----------- | ----------- | ---------- | -------: |
| NYP-1   | Invite guests     | In progress | your.name   | your.name  |  -       |

You've invited John and Megan both are very excited about the party and want to help. John is keen on decorating while Megan is on snacks.
> `{{ $mm->getCommand() }} assign NYP-3 @john.smith`
> `{{ $mm->getCommand() }} assign NYP-2 @megan.whales`

And they're now responsible for these tasks! `{{ $mm->getCommand() }} list NYP` will now show us:

| Code    | Name              | Status      | Assigned to  | Created By | Deadline |
| :------ | :---------------- | ----------- | ------------ | ---------- | -------: |
| NYP-1   | Invite guests     | In progress | your.name    | your.name  |  -       |
| NYP-2   | Buy snacks        | New         | megan.whales | your.name  |  -       |
| NYP-3   | Decorate          | New         | john.smith   | your.name  |  -       |
| NYP-4   | Buy presents      | New         | your.name    | your.name  |  -       |

After you've sent all the invitations you'd like to set `ITS-1` to _Done_, do it by:
> `{{ $mm->getCommand() }} done NYP-1`

Next you start working on buying presents `{{ $mm->getCommand() }} start NYP-4`
While task list `{{ $mm->getCommand() }} list NYP` will now show us:

| Code    | Name              | Status      | Assigned to  | Created By | Deadline |
| :------ | :---------------- | ----------- | ------------ | ---------- | -------: |
| NYP-1   | Invite guests     | Done        | your.name    | your.name  |  -       |
| NYP-2   | Buy snacks        | In progress | megan.whales | your.name  |  -       |
| NYP-3   | Decorate          | In progress | john.smith   | your.name  |  -       |
| NYP-4   | Buy presents      | In progress | your.name    | your.name  |  -       |

John has done the bulk of the work but unfortunately he has fallen ill and you have to take over decorations.
> `{{ $mm->getCommand() }} take NYP-3`

The _take_ option changes the the assignee to you.
You now have two tasks assigned in this project and maybe more in other ones, can you list all _your_ tasks? Of course:
> `{{ $mm->getCommand() }} my`

Will give you a table of all your tasks. As you've noticed, manipulations are mostly done through codes be it project code or task code to maximize speed. Experience shows that 2-3 letter codes are the best especially if they're an abbreviation of the project's name.
These are the basic commands you will be using, more can be found `{{ $mm->getCommand() }} help` and `/p help`.

#### Command structure
`/command option argument1 argument2 ...`

You got here by typing `{{ $mm->getCommand() }} intro` therefore `{{ $mm->getCommand() }}` is the _command_ and `intro` is _option_ and there are no _arguments_ in this case. As you've noticed above there are commands that do have arguments, keep in mind that arguments **must** be placed in the right order.
Start with
> `{{ $mm->getCommand() }} help`

