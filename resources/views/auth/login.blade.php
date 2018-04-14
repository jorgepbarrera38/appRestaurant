<!DOCTYPE html>
<html lang="en">
<head>
	<title>Iniciar sesión</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{ asset('login-assets/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('login-assets/css/main.css')}}">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(login-assets/images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Iniciar sesión
					</span>
				</div>
				@if($errors->any())
					<div class="alert alert-danger">
						@foreach($errors->all() as $error)
							{{ $error }}
						@endforeach
					</div>
				@endif
				<form class="login100-form" action="login" method="POST">
					@csrf
					<div class="wrap-input100">
						<span class="label-input100">Usuario</span>
						<input class="input100" type="text" name="name" value="{{ old('name') }}" placeholder="Ingrese usuario" required>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Contraseña</span>
						<input class="input100" type="password" name="password" placeholder="Ingrese contraseña" required>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Entrar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>