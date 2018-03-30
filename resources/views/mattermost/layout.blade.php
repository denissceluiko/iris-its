@if($mm->showRequest)
You tried `{{ $mm->userRequest() }}`, that's not quite right :(
@endif
@if($mm->showUsage)
Usage: {{ $usage }}
@endif
@yield('content')