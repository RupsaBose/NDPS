<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NDPS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body style="background-image: url({{asset('images/smoke-face.jpg')}});background-position: center;background-repeat: no-repeat;background-size: cover;">
    <div id="app">

        <main class="py-4">

            <div class="container" style="opacity:0.7; margin-top:15%">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-sm-12 text-center" style="border:#d9a04e 5px solid;background-color:#b8c9f6;-webkit-border-radius: 15px 15px 15px 15px;border-radius: 15px 15px 15px 15px;">
                                    <strong><h4 style="margin-bottom:1px;">DRUG DISPOSAL MONITORING SYSTEM</h4></strong>
                                    <strong>CALCUTTA HIGH COURT</strong>
                                </div>
                            </div>                            

                            <div class="card-body">
                                <div class="col-sm-2">
                                    <img src="{{asset('images/CHC_logo.png')}}" style="height:70px; margin-left:200px; margin-bottom:10px">
                                </div>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="user_id" class="col-md-4 col-form-label text-md-right">{{ __('User ID') }}</label>

                                        <div class="col-md-6">
                                            <input id="user_id" type="text" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id" value="{{ old('user_id') }}" autofocus> @if ($errors->has('user_id'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span> @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"> @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span> @endif
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-5">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>

                                            @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                            @endif
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>