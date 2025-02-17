@component('mail::layout')
{{-- Header --}}
@slot ('header')
@component('mail::footer')
<!-- header -->
@endcomponent
@endslot

# {!!$TITLE!!}

{!!$TOP_CONTENT!!}

@component('mail::button', ['url' => $URL, 'color' => 'green'])
{!!$BUTTON_NAME!!}
@endcomponent

{!!$BOTTOM_CONTENT!!}

{{-- Footer --}}
@slot ('footer')
@component('mail::footer')
<!-- footer -->
@endcomponent
@endslot
@endcomponent