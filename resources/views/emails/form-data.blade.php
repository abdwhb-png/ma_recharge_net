<x-mail::message>
# Vous avez une nouvelle soumission : {{ $data['form_name'] ?? ''}}

@if($code)
<x-mail::panel>
    <b style="color: blue">{{$code}}</b>
</x-mail::panel>
@endif

Autres données soumises :

<x-mail::table>
| Désignation       | Valeur         |
| :---------: | :------------: |
@foreach ($data as $k => $v)
| {!! $k !!} | {!! $v ?? '--' !!} |
@endforeach
</x-mail::table>

{{ now() }},<br>
{{ config('app.name') }}
</x-mail::message>
