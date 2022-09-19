<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';

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

if (empty($_SESSION['pagina'])) {
    $_SESSION['pagina'] = "";
}

$pegaRef="//";
if (isset($_SERVER['HTTP_REFERER'])) {
    $pegaRef = $_SERVER['HTTP_REFERER'];
}

$explode1 = explode("/", $pegaRef);
$contagem = $explode1[count($explode1) - 2];
$pegandoId = new classe();

$abrirsite = true;

$_SESSION['abrirsite'] = $abrirsite;

/*
if ($abrirsite == false) {
    echo "<hr>MODO TESTE ATIVADO<hr>";
    echo "Nome do site: " . $contagem . " - ";
    echo "ID obtido: " . $pegandoId->obterQuery($contagem);
}
*/

$_SESSION['pagina'] = $pegandoId->obterQuery($contagem);

?>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="estilo.css" />

    <script>
        function abrirSite1() {
            telefone();
            cpfCnpj();
            soNome();
            btn = document.getElementById('submit');
            nome = document.getElementById('nome');
            cpf = document.getElementById('cpf');
            email = document.getElementById('email1');
            rendaFamiliar = document.getElementById('rendaFamiliar');
            celular = document.getElementById('celular');
            res = document.getElementById('resp');
            if (!nome.value == "" && !email.value == "" && !celular.value == "") {
                btn.style.opacity = "0";
                btn.value = "";
                res.innerHTML = "AGUARDE! ENVIANDO FORMULÁRIO...";
                res.style.color = "#43D1AF";
                btn.style.height = "0px";
                btn.style.width = "0px";
                btn.style.margin = "-100px";
            }
        }
    </script>
</head>

<body>
    <?php
    $st = 'https://tincdig.com.br/thanks/?' . $contagem;
    $_SESSION['thanks'] = $st;
    $verificarReq;
    if (isset($_POST['nome'])) {
        $verificarReq = true;
    } else {
        $verificarReq = false;
    }
    try {
    ?>


        <form action=json.php method="POST" class="form-style-6" name="form1" id="form1">
            <div>
                <label>Nome: *</label>
                <input type="text" id="nome" name="nome" onkeydown="soNome()" onkeyup="soNome()" placeholder="Nome Completo" required />
            </div>
            <div>
                <label>E-mail: *</label>
                <input type="email" id="email1" onchange="removeEspaco()" onmousedown="removeEspaco()" onmouseover="removeEspaco()" onkeydown="removeEspaco()" onkeyup="removeEspaco()" name="email1" placeholder="email@completo" required />
            </div>
            <div>
                <label>Celular: *</label>
                <input type="text" id="celular" onchange="telefone()" onmousedown="telefone()" onmouseover="telefone()" onkeydown="telefone()" onkeyup="telefone()" minlength="15" maxlength="15" value="" name="celular" placeholder="Número do Celular" required />
            </div>
            <div>
                <label>Cpf:</label>
                <input type="text" id="cpf" onchange="cpfCnpj()" onkeydown="cpfCnpj()" onkeyup="cpfCnpj()" minlength="14" maxlength="14" name="cpf" value="" placeholder="Número do cpf" />
            </div>
            <div class="input-group mb-3">
                <label>Renda Familia:R$</label>
                <input type="number" step="any" class="form-control" id="rendaFamiliar" name="rendaFamiliar">
            </div>
            <div>
                <p id="resp"></p>
                <input type="submit" class="form-style-6" id="submit" onClick="abrirSite1()">
            </div>
        </form>
    <?php

    } catch (Exception $e) {
        $erroLog = new classe();
        $erroLog->cadastrarLog(pegarData(), pegarHora(), $e->getMessage());
    }
    ?>
    <script>
        function somenteLetra(text) {
            return text.replace(/[^a-z àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇA-Z]/g, '');
        }

        function justNumbers(text) {
            var numbers = text.replace(/[^0-9]/g, '');
            return (numbers);
        }

        function soNome() {
            var n = document.getElementById('nome').value;
            n = somenteLetra(n);
            n = n.replace("  ", " ");
            document.getElementById('nome').value = n;
        }

        function removeEspaco() {
            var n = document.getElementById('email1').value;
            document.getElementById('email1').value = "";
            n = n.replace(" ", "");
            document.getElementById('email1').value = n;
        }

        function cpfCnpj() {
            var pegaTecla = event.keyCode;
            var tel = document.getElementById('cpf').value;
            if (pegaTecla == 8) {
                if (tel.length == 4) {
                    document.getElementById('cpf').value = tel[0] + tel[1] + tel[2];
                } else if (tel.length == 8) {
                    document.getElementById('cpf').value = tel[0] + tel[1] + tel[2] + "." + tel[4] + tel[5] + tel[6];
                } else if (tel.length == 12) {
                    document.getElementById('cpf').value = tel[0] + tel[1] + tel[2] + "." + tel[4] + tel[5] + tel[6] + "." + tel[8] + tel[9] + tel[10];
                }
            } else {
                tel = justNumbers(tel);
                document.getElementById('cpf').value = "";
                for (var x = 0; x < tel.length; x++) {
                    if (x == 2 || x == 5) {
                        document.getElementById('cpf').value += tel[x] + ".";
                    } else if (x == 8) {
                        document.getElementById('cpf').value += tel[x] + "-";
                    } else {
                        document.getElementById('cpf').value += tel[x];
                    }
                }


            } // esse else faz parte no caso não for digitado backSpace

        }

        function telefone() {
            var pegaTecla = event.keyCode;
            var tel = document.getElementById('celular').value;
            if (pegaTecla == 8) {
                if (tel.length == 1) {
                    document.getElementById('celular').value = "";
                } else if (tel.length == 3) {
                    document.getElementById('celular').value = "(" + tel[1];
                } else if (tel.length == 6) {
                    document.getElementById('celular').value = "(" + tel[1] + tel[2] + ")" + tel[4];
                } else if (tel.length == 13) {
                    document.getElementById('celular').value = "(" + tel[1] + tel[2] + ")" + tel[4] + "." + tel[6] + tel[7] + tel[8] + tel[9];
                }

            } else {
                tel = justNumbers(tel);
                document.getElementById('celular').value = "";
                if (tel.length > 11) {
                    tel = tel.replace("55", "");
                }

                for (var x = 0; x < tel.length; x++) {
                    if (x == 0) {
                        document.getElementById('celular').value += "(" + tel[x];
                    } else if (x == 1) {
                        document.getElementById('celular').value += tel[x] + ")";

                    } else if (x == 2) {
                        document.getElementById('celular').value += tel[x] + ".";
                    } else if (x == 6) {
                        document.getElementById('celular').value += tel[x] + "-";
                    } else {
                        document.getElementById('celular').value += tel[x];
                    }
                }

            }

        }
    </script>
</body>

</html>