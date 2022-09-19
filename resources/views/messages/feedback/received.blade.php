@lang('bot-core::feedback.new')<br>
@lang('bot-core::common.from') {{ $from }}
@isset($username)
    ({{ '@'.$username }})
@endisset
[{{ $user_id }}]<br>
@lang('bot-core::common.message')<br>
{!! $message  !!}
