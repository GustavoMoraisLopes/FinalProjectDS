<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} - Recuperação de Palavra‑passe</title>
    <style>
        /* Layout base (compatível com emails) */
        .wrapper { width: 100%; background: #f5f7fb; padding: 24px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
        .header { background: #367aa3; color: #ffffff; padding: 24px; }
        .brand { font-size: 22px; font-weight: 700; letter-spacing: 0.4px; display: flex; align-items: center; gap: 12px; }
        .brand svg { flex: 0 0 auto; }
        .content { padding: 24px; color: #2c3e50; font-family: -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        .greeting { font-size: 18px; font-weight: 600; margin: 0 0 12px; }
        .text { font-size: 15px; line-height: 1.6; margin: 0 0 16px; color: #34495e; }
        .button { display: inline-block; padding: 12px 18px; border-radius: 999px; background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 15px; }
        .note { font-size: 13px; color: #7f8c8d; margin-top: 16px; }
        .divider { height: 1px; background: #ecf0f1; margin: 24px 0; }
        .footer { padding: 18px 24px; background: #fafbfc; color: #7f8c8d; font-size: 12px; }
        .muted { color: #95a5a6; }
        .center { text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <div class="brand">
                <!-- Inline logo inspirado no ícone "caixa" usado no login -->
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 2l8 4-8 4-8-4 8-4z" fill="rgba(255,255,255,0.9)"/>
                    <path d="M4 8l8 4v10L4 18V8z" fill="rgba(255,255,255,0.7)"/>
                    <path d="M20 8l-8 4v10l8-4V8z" fill="rgba(255,255,255,0.85)"/>
                </svg>
                {{ $appName }}
            </div>
        </div>
        <div class="content">
            <p class="greeting">Olá {{ $name }}!</p>
            <p class="text">Recebemos um pedido para recuperar a sua palavra‑passe. Para concluir, clique no botão abaixo e siga as instruções.</p>

            <p class="center" style="margin: 20px 0;">
                <a class="button" href="{{ $resetUrl }}" target="_blank" rel="noopener">Recuperar Palavra‑passe</a>
            </p>

            <p class="text">Este link é válido por 60 minutos. Se não solicitou esta recuperação, pode ignorar este email.</p>

            <div class="divider"></div>
            <p class="note">Este é um email automático enviado por <strong>{{ $appName }}</strong>. Não responda a esta mensagem (remetente: {{ $supportEmail }}).</p>
        </div>
        <div class="footer center">
            <p>&copy; {{ date('Y') }} {{ $appName }}. Todos os direitos reservados.</p>
            <p class="muted">Envio realizado em ambiente seguro via Mailtrap Sandbox.</p>
        </div>
    </div>
</div>
</body>
</html>
