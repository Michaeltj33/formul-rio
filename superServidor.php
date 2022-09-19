<?php
session_start();
class classe
{
    private function pegarServ()
    {
        $servidor = "tincdig-rds-sp-db-mysql-wp-1c.cjl7axwc7ga5.sa-east-1.rds.amazonaws.com:3306";
        $usuario = "admin";
        $senha = "HDDyna1500";
        $bancodedados = "wordpress";

        $conn = new mysqli($servidor, $usuario, $senha, $bancodedados);

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        return $conn;
    }


    private function pegarID($emp)
    {
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " .$emp;
        $resultado = $conn->query($sql);
        return $resultado;
    }

    function obterQuery($pg)
    {
        $pegasite = $_SESSION['abrirsite'];
        $teste = "O Id do Site ";
        $recebe = "não encontrado";
        if ($pegasite == true) {
            $sql = "SELECT * FROM empreendimento";
        } else {
            $sql = "SELECT * FROM commDev";
        }
        $conn = $this->pegarServ();
        $resultado = $conn->query($sql);
        foreach ($resultado as $result) {
            if ($pg == $result['nome']) {
                $recebe = $result['id'];
                $teste = "";
                break;
            }
        }
        /*
        if ($teste == 'O Id do Site ') {
            echo "<hr>";
            echo $teste . $pg . " não foi encontrado, porem Será utlizado ID:0";
            echo "<hr>";
        }
        */
        return $recebe;
    }

    function capturaLeads($nome, $email, $celular, $cpf, $renda)
    {
        $conn = $this->pegarServ();
        $id = $this->pegarID("empreendimento");
        $sql = "SELECT * FROM empreendimento";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    function cadastrarLog($data, $hora, $log)
    {
        $conn = $this->pegarServ();
        $sql = "INSERT into logLead (data, hora, erroLog) values ('$data','$hora','$log');";
        $conn->query($sql);
    }
}
