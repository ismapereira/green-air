<?php
/**
 * Helper para Google reCAPTCHA v2 Invisible.
 * Fallback: se as chaves não estiverem configuradas, o CAPTCHA é ignorado.
 *
 * Fluxo invisible:
 * 1. Usuário clica em submit
 * 2. JS intercepta o submit e chama grecaptcha.execute()
 * 3. Google valida e dispara o callback com o token
 * 4. Callback faz o form.submit() nativo (pula a interceptação)
 * 5. Server verifica o token via API do Google
 */
class CaptchaHelper
{
    /**
     * Verifica se o CAPTCHA está habilitado (chaves configuradas).
     */
    public static function isEnabled(): bool
    {
        return defined('RECAPTCHA_SITE_KEY')
            && defined('RECAPTCHA_SECRET_KEY')
            && RECAPTCHA_SITE_KEY !== ''
            && RECAPTCHA_SECRET_KEY !== '';
    }

    /**
     * Retorna o script do reCAPTCHA para incluir no <head> ou antes do </body>.
     * Inclui CSS para esconder o badge padrão (permitido pelo Google se
     * incluir texto de atribuição via renderWidget).
     */
    public static function renderScript(): string
    {
        if (!self::isEnabled()) return '';
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>'
            . '<style>.grecaptcha-badge{visibility:hidden!important;}</style>';
    }

    /**
     * Retorna o widget invisível + JS de interceptação + texto de atribuição.
     * Deve ser incluído dentro do <form> (antes do botão submit).
     *
     * @param string $formId O atributo id="" do <form> (ex: "login-form")
     */
    public static function renderWidget(string $formId): string
    {
        if (!self::isEnabled()) return '';

        // Sanitiza hífens para gerar nomes válidos em JavaScript
        // Ex: "login-form" → "login_form" (hífens são operadores de subtração em JS)
        $jsId = str_replace('-', '_', $formId);
        $siteKey = htmlspecialchars(RECAPTCHA_SITE_KEY);

        return '
        <div id="ga_recaptcha_' . $jsId . '" class="g-recaptcha"
             data-sitekey="' . $siteKey . '"
             data-size="invisible"
             data-callback="onCaptchaSuccess_' . $jsId . '"></div>

        <p class="recaptcha-terms" style="font-size:0.72rem;color:rgba(255,255,255,0.45);text-align:center;margin:0.75rem 0 0;line-height:1.4;">
            Protegido pelo reCAPTCHA do Google —
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener"
               style="color:rgba(255,255,255,0.55);text-decoration:underline;">Privacidade</a> e
            <a href="https://policies.google.com/terms" target="_blank" rel="noopener"
               style="color:rgba(255,255,255,0.55);text-decoration:underline;">Termos</a>.
        </p>

        <script>
        /* Callback chamado pelo Google quando o token é gerado com sucesso */
        function onCaptchaSuccess_' . $jsId . '(token) {
            document.getElementById("' . $formId . '").submit();
        }

        /* Intercepta o submit para executar o reCAPTCHA invisible primeiro */
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("' . $formId . '");
            if (!form) return;

            form.addEventListener("submit", function(e) {
                /* Se já existe um token válido, deixa o submit acontecer */
                if (grecaptcha && grecaptcha.getResponse()) return;

                /* Caso contrário, para o submit e solicita verificação ao Google */
                e.preventDefault();
                grecaptcha.execute();
            });
        });
        </script>';
    }

    /**
     * Valida o token do reCAPTCHA via API do Google.
     * Retorna true se válido ou se o CAPTCHA não está habilitado.
     */
    public static function verify(?string $token = null): bool
    {
        if (!self::isEnabled()) return true; // Fallback: sem chaves = permitir

        $token = $token ?? ($_POST['g-recaptcha-response'] ?? '');
        if (empty($token)) return false;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 5
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            // Em caso de falha na requisição, permitir (evitar bloquear o usuário)
            return true;
        }

        $json = json_decode($result, true);
        return !empty($json['success']);
    }
}
