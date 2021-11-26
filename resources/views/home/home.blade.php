<form action="/logout" method="POST">
  @csrf
  <button type="submit" class="btn btn-default"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
</form>
<h1>Welcome back to Home</h1>
{{auth()->user()}}