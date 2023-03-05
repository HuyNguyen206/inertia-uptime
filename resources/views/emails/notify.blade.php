<x-mail::message>
# {{$messageEmail}}

It look like the endpoint was updated.

<x-mail::button :url="$link">
Go to logs endpoint
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>


