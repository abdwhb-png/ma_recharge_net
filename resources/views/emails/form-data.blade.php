<x-mail::message>
# Vous avez une nouvelle soumission de formulaire:

@if($code)
<x-mail::panel>
    <b style="color: darkorange">{{$code}}</b>
</x-mail::panel>
@endif

<x-mail::table>
| Index       | Valeur         |
| :---------: | :------------: |
@foreach ($data as $k => $v)
| {!! $k !!} | {!! $v ?? '--' !!} |
@endforeach
</x-mail::table>

{{ now() }},<br>
{{ config('app.name') }}
</x-mail::message>
