@component('mail::message')

# {{ trans('canvas::app.hello') }}

{{ trans('canvas::app.you_are_receiving_this_email') }}

@component('mail::button', ['url' => $link])
    {{ trans('canvas::app.reset_password') }}
@endcomponent

{{ trans('canvas::app.this_password_reset_link_will_expire') }}

{{ trans('canvas::app.if_you_did_not_request_a_password_reset') }}

{{ trans('canvas::app.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
