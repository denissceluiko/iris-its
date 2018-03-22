# Simple Mattermost integrated issue tracker

Temp docs

[Mattermost Slash command docs](https://docs.mattermost.com/developer/slash-commands.html)

Mattermost request
```
Content-Length: 244
User-Agent: Go 1.1 package http
Host: localhost:5000
Accept: application/json
Content-Type: application/x-www-form-urlencoded

channel_id=cniah6qa73bjjjan6mzn11f4ie&
channel_name=town-square&
command=/somecommand&
response_url=not+supported+yet&
team_domain=someteam&
team_id=rdc9bgriktyx9p4kowh3dmgqyc&
text=hello+world&
token=xr3j5x3p4pfk7kk6ck7b4e6ghh&
user_id=c3a4cqe3dfy6dgopqt8ai3hydh&
user_name=somename
```

Expexted Responses
```
{"response_type": "ephemeral", "text": "Hello, this is some text\nThis is more text. :tada:"}
{"response_type": "in_channel", "text": "Hello, this is some text\nThis is more text. :tada:"}
{"response_type": "ephemeral", "goto_location": "https://about.mattermost.com", "text": "Hello, this is some text\nThis is more text. :tada:"}
```

PHP validation rule
```
$this->validate($request, [
    'channel_id' => 'required',
    'channel_name' => 'required',
    'command' => 'required',
    'team_domain' => 'required',
    'team_id' => 'required',
    'text' => 'required',
    'token' => 'required',
    'user_id' => 'required',
    'user_name' => 'required',
]);
```