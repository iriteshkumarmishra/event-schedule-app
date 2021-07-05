@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-12 mb-4">
            <div class="d-flex justify-content-center">
                @if(!empty($siteSettings['logo'])) 
                    <a href="/"> <img class="img-fluid" src="{{$siteSettings['logo']}}" alt="logo-img" style="max-width: 680px;"> </a>
                @else
                    <a href="/"> <img class="img-fluid mx-auto d-block" src="{{ asset('images/logo.svg') }}" alt="logo-img"> </a>
                @endif
            </div>
        </div>
        <div class="col-md-8">
            <registration ></registration>
        </div>
    </div>
</div>
@endsection
