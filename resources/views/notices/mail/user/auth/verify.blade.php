@component('mail::layout')
{{-- Header --}}
@slot ('header')
@component('mail::footer')
<!-- header -->
@endcomponent
@endslot

# {!!$TITLE!!}

{!!$TOP_CONTENT!!}

# {!!$BODY_CONTENT!!}

{!!$BOTTOM_CONTENT!!}

{{-- Footer --}}
@slot ('footer')
@component('mail::footer')
<!-- footer -->
@endcomponent
@endslot
@endcomponent
