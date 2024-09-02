<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verificação de E-mail</title>
</head>
<body>
<div class="container my-5">
    <div class="card">
        <div class="card-header text-center">
            <h1 class="h4">Verificação de E-mail</h1>
        </div>
        <div class="card-body">
            <p>Olá, <strong>{{ $user->name }}</strong>,</p>
            <p>Obrigado por se registrar! Clique no botão abaixo para verificar seu e-mail e confirmar seu cadastro:</p>
            <form method="POST" action="{{ route('confirm.email', ['id' => $user->id, 'token' => $user->verification_token]) }}">
                @csrf
                @method('PUT')
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Confirmar Cadastro</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">
            <small>&copy; {{ date('Y') }} - Virtual BookShelf. Todos os direitos reservados.</small>
        </div>
    </div>
</div>
</body>
</html>
