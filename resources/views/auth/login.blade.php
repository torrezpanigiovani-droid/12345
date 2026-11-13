<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar · ParkControl</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo"><i class="fas fa-parking text-primary"></i> <b>Park</b>Control</div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicia sesión para continuar</p>

            @if($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary"><label><input type="checkbox" name="remember"> Recordarme</label></div>
                    </div>
                    <div class="col-5"><button type="submit" class="btn btn-primary btn-block">Ingresar</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
