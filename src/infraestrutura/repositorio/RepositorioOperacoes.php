<?php

namespace Deivz\CalculadoraIr\infraestrutura\repositorio;

abstract class RepositorioOperacoes
{
    public function listarOperacoes()
    {
        $arquivo = '../src/infraestrutura/persistencia/negociacoes.txt';
        $stream = fopen($arquivo, 'r');
        $i = 0;  
        while (!feof($stream)) {
            $negociacao = json_decode(fgets($stream));
            if ($negociacao->Usuario === $_SESSION['cpf']) {
                $data = explode('-', $negociacao->Data);
                $data = "{$data[2]}/{$data[1]}/{$data[0]}";

                $aplicacao = $negociacao->Aplicacao;

                $ativos = $negociacao->Ativos;
                $operacoes = $negociacao->Operacoes;
                $quantidades = $negociacao->Quantidades;
                $precos = $negociacao->Precos;
                $taxas = $negociacao->Taxas;

                $_SESSION["negociacoes"][$i] = compact('data', 'aplicacao', 'ativos', 'operacoes', 'quantidades', 'precos', 'taxas');

                $i++;
            }
        }
        fclose($stream);
        $_SESSION['quantidadeDados'] = $i;
    }
}