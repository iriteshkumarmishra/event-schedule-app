@extends('layouts.app')

@section('content')

<div class="container login-container">
<div class="row justify-content-center">
   <div class="card m-4">
      <div class="card-header d-flex justify-content-center">
         @if(!empty($siteSettings['logo'])) 
            <a href="/"> <img class="img-fluid" src="{{$siteSettings['logo']}}" alt="logo-img" style="max-width: 416px;"> </a>
         @else
            <a href="/"> <img class="img-fluid mx-auto d-block" src="{{ asset('images/logo.svg') }}" alt="logo-img" ></a>
         @endif
      </div>
      <div class="card-body">
         <div class="u-login-form">
            <form class="mb-3" method="POST" action="{{ route('login') }}">
               @csrf
               
               <h4 class="medium mb-5">{{ __('Login with your registered email address and password') }}</h4>
               <div class="form-group mb-4">
                  <label for="email">{{ __('Your email') }}</label>
                  <input id="email" class="form-control  @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('Enter your email address') }}" required autocomplete="email" autofocus>
                  @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group mb-4">
                  <label for="password">{{ __('Password') }}</label>
                  <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" type="password" placeholder="{{ __('Enter your password') }}" required autocomplete="current-password">
                  @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group d-flex justify-content-between align-items-center mb-4">
               </div>
               <button class="btn btn-primary btn-block" type="submit">{{ __('Login') }}</button>
            </form>
            <div class="separator" style="position: relative;">
               <hr style="margin: 2rem;border-width: 2px;">
            </div>
            <a href="{{route('register')}}">
               <div class="btn btn-block" id="signUpButton"><i class="fas fa-plus"></i>{{ __('Create an account') }}</div>
            </a>
         </div>
      </div>
   </div>
</div>
@endsection
