@extends('layouts.app')

@section('content')
{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-12">--}}
{{--            <drap_drop></drap_drop>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<card-component>
    <template v-slot:header>
        <h1>Gallery</h1>
    </template>
    <template v-slot:body>
        <drap_drop></drap_drop>
    </template>
</card-component>
@endsection
