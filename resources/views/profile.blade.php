<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <title>Profile</title>
		@vite(['resources/js/pages/profile/app.js', 'resources/css/app.css', 'resources/css/profile.css'])
    </head>
    <body data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
	    <div class="app-container">
	        <div id="header"></div>
		    <div class="profile">
                <h2>Profile edit</h2>
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                         <strong>{{ $message }}</strong>
                    </div>
                @endif
				<div class="_container">
				    <img src="{{$user->getPhoto() ?: '/images/default-avatar.jpg'}}" />
                    <div style="margin-left: 100px;">
				    <form method="POST" action="{{ url('/profile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('email') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$user->email}}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$user->name}}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
					    <div class="form-group row">
                            <label for="photo" class="col-sm-4 col-form-label text-md-right">{{ __('photo') }}</label>
                            <div class="col-md-6 _photo">
                                <input type="file" id="photo" name="photo" accept="image/*" />
                                @if ($errors->has('photo'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif
                            </div>
					    </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-4 col-form-label text-md-right">{{ __('new password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirm" class="col-sm-4 col-form-label text-md-right">{{ __('password confirm') }}</label>
                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control{{ $errors->has('password_confirm') ? ' is-invalid' : '' }}" name="password_confirm" value="">
                                @if ($errors->has('password_confirm'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="btn_wr">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('save') }}
                                </button>
                        </div>
				    </form>
				    <div>
				</div>
		    </div>
		</div>
    </body>
</html>