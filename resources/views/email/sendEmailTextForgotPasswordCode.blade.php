Olá, {{ $user->name }}!
Use o código abaixo para redefinir sua senha:
{{ $code }}

Se você não solicitou essa recuperação, por favor, ignore este e-mail.
O código é valido até <strong>{{ $formattedDate }}</strong> às <strong>{{ $formattedTime }}

    &copy; {{ date('Y') }} - Virtual BookShelf. Todos os direitos reservados.
