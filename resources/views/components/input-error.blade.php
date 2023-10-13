@props(['messages'])

@if ($messages)
<ul {{ $attributes }}>
    @foreach ((array) $messages as $message)
    <li style="color:red; list-style: none">{{ $message }}</li>
    @endforeach
</ul>
@endif
