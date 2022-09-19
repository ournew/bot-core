<b>@lang('bot-core::about.bot')</b><br>
@lang('bot-core::about.name',['value'=>config('bot-core.info.name')])<br>
@lang('bot-core::about.username',['value'=>'@'.config('bot-core.info.username')])<br>
@lang('bot-core::about.version',['value'=>config('bot-core.info.version')])<br>

@if(config('bot-core.info.source'))
    @lang('bot-core::about.source',['value'=>'<a href="'.config('bot-core.info.source').'">'.trans('bot-core::common.open_url').'</a>'])
    <br>
@endif

@if(config('bot-core.info.changelog'))
    @lang('bot-core::about.changelog',['value'=>'<a href="'.config('bot-core.info.changelog').'">'.trans('bot-core::common.open_url').'</a>'])
    <br>
@endif
<br>

<b>@lang('bot-core::about.developer')</b><br>
@lang('bot-core::about.name',['value'=>config('bot-core.developer.name')])<br>
@lang('bot-core::about.username',['value'=>config('bot-core.developer.username')])<br>
@lang('bot-core::about.email',['value'=>config('bot-core.developer.email')])<br>
@lang('bot-core::about.website',['value'=>config('bot-core.developer.website')])<br>
