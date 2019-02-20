<?php

class Calendario{

    public $feriados = array (
        "01/01" => "Ano Novo",
        "21/04" => "Tiradente",
        "01/05" => "Dia do Trabalhador",
        "07/09" => "independência do Brasil",
        "18/10" => "Dia das Crianças",
        "15/11" => "Proclamação da República",
        "25/12" => "Natal",
        "30/11" => "Dia do Síndico"
        
       );


       public function __construct($ano = NULL)
    {
        $ano = ($ano == NULL) ? date("Y") : $ano;
        
        //adição de feriados móveis ao calendário
        
        //Páscoa
        $data_pascoa = date ("d/m/Y", easter_date($ano));
        list ($dia_pascoa, $mes_pascoa, $ano_pascoa) = explode ("/", $data_pascoa);
        $this->feriados[$dia_pascoa . "/" . $mes_pascoa] = "Páscoa";
        
        //carnaval => Páscoa - 47 dias
        $data_carnaval = $this->SubtrairDias ($data_pascoa, 47);
        list ($carnaval_dia, $carnaval_mes, $carnaval_ano) = explode ("/", $data_carnaval);
        $this->feriados[$carnaval_dia . "/" . $carnaval_mes] = "Carnaval";
    
        //Corpus Christi => Páscoa + 60 dias
        $data_corpus = $this->SomarDias ($data_pascoa, 60);
        list ($corpus_dia, $corpus_mes, $corpus_ano) = explode ("/", $data_corpus);
        $this->feriados[$corpus_dia . "/" . $corpus_mes] = "Corpus Christi";
        
        //Paixão de Cristo (Sexta-feira Santa) => Páscoa - 2 dias
        $data_paixao_cristo = $this->SubtrairDias ($data_pascoa, 2);
        list ($paixao_cristo_dia, $paixao_cristo_mes, $paixao_cristo_ano) = explode ("/", $data_paixao_cristo);
        $this->feriados[$paixao_cristo_dia . "/" . $paixao_cristo_mes] = "Paixão de Cristo";
        
        /*
           O cálculo da data do Dia das Mães está como comentário, por não se tratar de um feriado. Caso queira que ele seja exibido no caléndário, basta descomentar esta parte do código,retirando o '\/*' da linha 71 e o '*\/' da linha 85. 
        */
        
        
        //Dia Das Mães => Segundo Domingo de Maio
        //para encontrar a data do Dia Das mães, procura-se o primeiro domingo de maio e soma-se 7 unidades ao dia encontrado. 
        
        for ($m = 1; $m <= 15; $m++)
        {
             if (date ("w", mktime (0, 0, 0, 5, $m, $ano)) == 0)
            {
                //soma 7 dias ao primeiro domingo
                $dia_maes = $m + 7;
                break;
            }
        }
        $this->feriados[$dia_maes . "/05"] = "Dia das Mães";
        
        
        
        /*
           O cálculo da data do Dia dos Pais está como comentário, por não se tratar de um feriado. Caso queira que ele seja exibido no caléndário, basta descomentar esta parte do código,retirando o '\/*' da linha 92 e o '*\/' da linha 106. 
        */
        
        
        //Dia Dos Pais => Segundo Domingo de Agosto
        //para encontrar a data do Dia Dos Pais, procura-se o primeiro domingo de agosto e soma-se 7 unidades ao dia encontrado. 
        
        for ($m = 1; $m <= 15; $m++)
        {
             if (date ("w", mktime (0, 0, 0, 8, $m, $ano)) == 0)
            {
                //soma 7 dias ao primeiro domingo
                $dia_pais = $m + 7;
                break;
            }
        }
        $this->feriados[$dia_pais . "/08"] = "Dia dos Pais";
        
        
    }//fim da função __construct()

    public function SomarDias ($data, $n_dias, $forma = "pt")
    {
        if (!is_int ($n_dias))
        {
            echo "<p>Função <strong>". __FUNCTION__ ."</strong>: o argumento \"n_dias\" deve ser um número inteiro.</p>";
            return false;
        }
        
        $forma = strtolower ($forma);
        if ($forma != "en" AND $forma != "pt")
            $forma = "pt";
        
        if (preg_match ("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $data))
            list ($dia, $mês, $ano) = explode ("/", $data);
        elseif (preg_match ("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $data))
            list ($ano, $mês, $dia) = explode ("-", $data);
        else
        {
            echo "<p>Função <strong>". __FUNCTION__ ."</strong>: Formato de data inválido (". $data .").</p>";
            return false;
        }
        
        //transforma $n_dias em segundos
        //86400 = 60 * 60 * 24
        $segs_n_dias = $n_dias * 86400;
        
        // tranforma $data em timestamp
        $segs_data = strtotime ($ano . "-" . $mês . "-" . $dia);
        
        $segs_nova_data = $segs_data + $segs_n_dias;
        
        $nova_data = ($forma == "pt") ? date("d/m/Y", $segs_nova_data) : date("Y-m-d", $segs_nova_data);
        
        return $nova_data;
        
        
    }
    
    
    
    /*
       Função SubtrairDias()
       Usada para calcular a data de feriados móveis.
       Esta função subtrai 'n_dias' da data 'data', passado como argumento, a qual deve estar no formato dd/mm/yyyy ou yyyy-mm-dd.
       O argumento 'forma' serve para especificar o formato da data retornada. Ele pode conter os seguintes valores:
       
       "pt" => Retornará a data no formato DD/MM/YYYY
       "en" => Retornará a data no formato YYYY-MM-DD
       
       Se 'forma' não for especificada, adotar-se-á a forma brasileira (pt).
    */
    public function SubtrairDias ($data, $n_dias, $forma = "pt")
    {
        if (!is_int ($n_dias))
        {
            echo "<p>Função <strong>". __FUNCTION__ ."</strong>: O argumento \"n_dias\" deve ser um número inteiro.</p>";
            return false;
        }
        
        $forma = strtolower ($forma);
        if ($forma != "en" AND $forma != "pt")
            $forma = "pt";
        
        if (preg_match ("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $data))
            list ($dia, $mês, $ano) = explode ("/", $data);
        elseif (preg_match ("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $data))
            list ($ano, $mês, $dia) = explode ("-", $data);
        else
        {
            echo "<p>Função <strong>". __FUNCTION__ ."</strong>: Formato de data inválido (". $data .").</p>";
            return false;
        }
        
        //transforma $n_dias em segundos
        //86400 = 60 * 60 * 24
        $segs_n_dias = $n_dias * 86400;
        
        // tranforma $data em timestamp
        $segs_data = strtotime ($ano . "-" . $mês . "-" . $dia);
        
        $segs_nova_data = $segs_data - $segs_n_dias;
        
        $nova_data = ($forma == "pt") ? date("d/m/Y", $segs_nova_data) : date("Y-m-d", $segs_nova_data);
        
        return $nova_data;
        
        
    }
    
}

        //Função que identifica se o dia de hoje é um feriado 

        class Calcular extends Calendario{

        public function dataFestiva(){


        $ts = strtotime("now");

        $dataAtual = date("d/m", $ts);


            foreach ($this->feriados as $data_feriado => $nome_feriado)
            
            if($dataAtual == $data_feriado){

                return $nome_feriado;
                
            }

            }
        }

            //Fim da função
        
        
        



$r = new Calcular(2019);

echo  $r->dataFestiva();

//var_dump($r->dataFestiva());












?>