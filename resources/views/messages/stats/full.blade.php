<b>@lang('bot-core::stats.title')</b><br>
<br>
@foreach($data as $item)
@if($item['type'] === 'stat')
@if($isLocalized)
@lang($item['key'], ['value'=>$item['value']])<br>
@else
{{$item['key']}}: {{$item['value']}}<br>
@endif
@endif
@if($item['type'] === 'space')
<br>
@endif
@endforeach
<br>
@lang('bot-core::stats.last_update', ['value'=>$lastUpdate])
