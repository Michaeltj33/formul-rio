<?php
include_once 'superServidor.php';

//$pgThanks = $_SESSION['thanks'];
//header("Location:$pgThanks");


try {
    $pegasite = $_SESSION['abrirsite'];
    if (empty($pegasite)) {
        $pegasite = true;
    }
} catch (Exception $e) {
    $erroLog = new classe();
    $erroLog->cadastrarLog(pegarData(), pegarHora(), $e->getMessage());
}

function removeE($dt)
{
    $dt = trim($dt); //remove espaço no inicio e fim
    return $dt;
}
function minusculo($dt1)
{
    $dt1 = removeE($dt1);
    $dt1 = strtolower($dt1); //deixa todas as letras minúscula
    return $dt1;
}
function deixarNumero($string)
{
    $string = removeE($string);
    $string = preg_replace("/[^0-9]/", "", $string); //remove tudo, deixando apenas números   
    $string = strval($string);
    return $string;
}
function numeroI($ni)
{
    $ni = intval($ni);
    $ni = removeE($ni);
    $ni = strval($ni);
    return $ni;
}

function pegarData()
{
    date_default_timezone_set('America/Sao_Paulo');
    return date('d-m-Y');
}

function pegarHora()
{
    date_default_timezone_set('America/Sao_Paulo');
    return date('H:i:s');
}

function teste($number)
{
    if ($number == 1) {
        throw new Exception("Apenas teste");
    }
}

try {
    $podeEnviar = true;
    $obtqr = $_SESSION['pagina']; //aqui pega o ID
    $nome = removeE($_POST['nome']);
    $email = minusculo($_POST['email1']);
    $celular = deixarNumero($_POST['celular']);
    $rendaFamiliar = numeroI($_POST['rendaFamiliar']);
} catch (Exception $e) {
    $erroLog = new classe();
    $erroLog->cadastrarLog(pegarData(), pegarHora(), $e->getMessage());
}

try {
    if (strlen($obtqr) > 3) {
        $obtqr = 0;
    }

    if (strlen($celular) == 10) {
        $pegaV = "";
        for ($x = 0; $x < strlen($celular); $x++) {
            if ($x == 2) {
                $pegaV .= 9;
                $pegaV .= $celular[$x];
            } else {
                $pegaV .= $celular[$x];
            }
        }
        $celular = $pegaV;
    }

    if (strlen($celular) == 11) {
        if ($celular[2] != 9) {
            $celular[2] = 9;
        }
    }
} catch (Exception $e) {
    $erroLog = new classe();
    $erroLog->cadastrarLog(pegarData(), pegarHora(), $e->getMessage());
}

try {
    $request = [
        'id' => 0,
        'nome' => $nome,
        'cpf' => $_POST['cpf'],
        'email' => $email,
        'celular' => $celular,
        'rendaFamiliar' => $rendaFamiliar,
        'idEmpreendimento' => $obtqr,
        'idOrigem' => "4"
    ];


    $curl = curl_init();
    curl_setopt_array($curl, array(
	//https://incdigital-integrationserver.archpelago.com:18092/api/prospects
        CURLOPT_URL => '/localhost',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($request),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic SW5jRGlnOk1oOGZ6ZnZM',
            'Content-Type: application/json'
        ),
    ));	
    $response = curl_exec($curl);    
		
		
    if(!is_dir('Logs')){
        mkdir('Logs',777,true);
    }
    if(!is_dir('Logs/'.pegarData())){
        mkdir('Logs/'.pegarData(),777,true);
    }
    
    $pegData = explode("-", pegarData());
    file_put_contents('Logs/'.pegarData() . '/' . 'Data ' .  $pegData[0] . '.log', $response . PHP_EOL, FILE_APPEND);
	
	echo "<pre>";
	print_r($request);
	echo "</pre>";
    curl_close($curl);
		
} catch (Exception $e) {
    $erroLog = new classe();
    $erroLog->cadastrarLog(pegarData(), pegarHora(), $e->getMessage());
}