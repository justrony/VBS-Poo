<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Recuperação de Senha</title>
</head>
<body>
<div class="container my-5">
    <div class="card">
        <div class="card-header text-center">
            <h1 class="h4">Recuperação de Senha</h1>
        </div>
        <div class="card-body text-center">
            <p class="mb-3">Olá, <strong>{{ $user->name }}</strong>!</p>
            <p class="mb-3">Use o código abaixo para redefinir sua senha:</p>
            <div class="alert alert-secondary d-inline-block px-4 py-2">
                <strong>{{ $code }}</strong>
            </div>
            <p class="mt-4">Se você não solicitou essa recuperação, por favor, ignore este e-mail.</p>
            <p class="mb-4">O código é valido até <strong>{{ $formattedDate }}</strong> às <strong>{{ $formattedTime }}</strong>.</p>
        </div>
        <div class="card-footer text-center text-muted">
            <small>&copy; {{ date('Y') }} - Virtual BookShelf. Todos os direitos reservados.</small>
        </div>
    </div>
</div>
</body>
</html>
