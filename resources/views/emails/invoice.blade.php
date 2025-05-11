@component('mail::message')
# Invoice from {{ $franchisee ?? '-' }}

Thank you for your order!

Attached is your invoice **#OR-00{{ $invoice->id }}**.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
