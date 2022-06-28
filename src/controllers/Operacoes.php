<?php

namespace Deivz\CalculadoraIr\controllers;

use Deivz\CalculadoraIr\helpers\TVerificarErros;
use Deivz\CalculadoraIr\interfaces\IRequisicao;
use Deivz\CalculadoraIr\models\Negociacao;



class Operacoes extends Renderizador implements IRequisicao
{
    
    use TVerificarErros;

    function processarRequisicao(): void
    {
        echo $this->renderizarPagina('/operacoes');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->realizarEnvio(filter_input(INPUT_POST, 'quantidadeOperacoes', FILTER_SANITIZE_NUMBER_INT));
        }
    }

    private function realizarEnvio($quantidadeOperacoes)
    {
        if($quantidadeOperacoes === "" || $quantidadeOperacoes === null){
            $this->mostrarMensagensDeErro('Escolha a quantidade de operações a serem lançadas.');
            header('Location: /operacoes');
            return;
        }else{
            header('Location: /operacoes');
        }

        $_SESSION['quantidadeOperacoes'] = $quantidadeOperacoes;
        $ativos = [];
        $operacoes = [];
        $quantidades = [];
        $precos = [];
        $taxas = [];

        for ($i = 0; $i < $quantidadeOperacoes; $i++) {
            if(isset($_POST["ativo{$i}"])){
                $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS);
                $aplicacao = filter_input(INPUT_POST, 'aplicacao', FILTER_SANITIZE_SPECIAL_CHARS); 
                array_push($ativos, filter_input(INPUT_POST, "ativo{$i}", FILTER_SANITIZE_SPECIAL_CHARS));
                array_push($operacoes, filter_input(INPUT_POST, "operacao{$i}", FILTER_SANITIZE_SPECIAL_CHARS));
                array_push($quantidades, filter_input(INPUT_POST, "quantidade{$i}", FILTER_SANITIZE_NUMBER_INT));
                array_push($precos, filter_input(INPUT_POST, "preco{$i}", FILTER_SANITIZE_SPECIAL_CHARS));
                array_push($taxas, filter_input(INPUT_POST, "taxa{$i}", FILTER_SANITIZE_SPECIAL_CHARS));
            }
        }

        $negociacao = new Negociacao($data, $aplicacao, $ativos, $operacoes, $quantidades, $precos, $taxas);

        $req = [
            'ID' => md5(uniqid(rand(), true)),
            'Usuario' => $_SESSION['cpf'],
            'Data' => $negociacao->data,
            'Aplicacao' => $negociacao->aplicacao,
            'Ativos' => $negociacao->ativos,
            'Operacoes' => $negociacao->operacoes,
            'Quantidades' => $negociacao->quantidades,
            'Precos' => $negociacao->precos,
            'Taxas' => $negociacao->taxas
        ];

        $dados = "\n" . json_encode($req, JSON_UNESCAPED_UNICODE);
        $arquivo = fopen('../src/infraestrutura/persistencia/negociacoes.txt', 'a');
        fwrite($arquivo, $dados);
        fclose($arquivo);
        $_SESSION['sucesso'] = 'Negociação inserida com sucesso!';
        unset($_SESSION['dadosNegociacao']);
        unset($_SESSION['quantidadeOperacoes']);
        header('Location: /operacoes');
    }
}