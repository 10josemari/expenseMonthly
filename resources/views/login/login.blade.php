@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row row-login justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header colorSecondary colorTextPrimary">Inicio de sesión</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('validateCredentials') }}">
                        @csrf
                        <!-- nombre de usuario -->    
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Nombre de usuario</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- nombre de usuario -->

                        <!-- contraseña -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- contraseña -->

                        <!-- Recordarme -->
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">Recuérdame</label>
                                </div>
                            </div>
                        </div>
                        <!-- Recordarme -->

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-lg btn-primary btn-login">Iniciar sesión <div class="spinner-login none floatRight marginLeft"><i class="fas fa-circle-notch fa-spin"></i></div></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
