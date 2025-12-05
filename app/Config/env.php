<?php
/**
 * Carrega variáveis de ambiente do arquivo .env
 * 
 * @param string $path Caminho para o arquivo .env
 * @return void
 */
function loadEnv($path = null) {
    if ($path === null) {
        $path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '.env';
    }
    
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse linha no formato KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove aspas se existirem
            if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                $value = $matches[2];
            }
            
            // Define variável de ambiente
            if (!array_key_exists($key, $_ENV)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

/**
 * Obtém valor de variável de ambiente com conversão de tipos
 * Wrapper seguro para env() do CakePHP
 * 
 * @param string $key Nome da variável
 * @param mixed $default Valor padrão se não encontrado
 * @return mixed
 */
function getEnvVar($key, $default = null) {
    // Usa a função env() nativa do CakePHP
    $value = env($key);
    
    if ($value === null || $value === false) {
        return $default;
    }
    
    // Converte strings para tipos apropriados
    if (is_string($value)) {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
    }
    
    return $value;
}
