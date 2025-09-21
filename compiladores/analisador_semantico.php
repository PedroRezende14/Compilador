<?php

include('Variavel.php');

class analisador_semantico {

    private $tabelaTok = array();
    private $tabelaSimbolos = array(); // Tabela de símbolos
    private $tipoAux; 
    private $aux;      
    private $declarado = false;
    public $as;

    public function __construct(){
        // A tabela de símbolos começa vazia
        $this->tabelaSimbolos = array();
        $this->tabelaTok = [];
    }

    public function analiseSemantica($estadoAtual, $tokenAtual){
        $elemento = $tokenAtual->tok;  
        $valor = $tokenAtual->valor;

        // echo 'ESTADO ('.$estadoAtual.'), VALOR: ('.$valor.') </br>';

        if(($estadoAtual == 5 && $elemento == 'id') || ($estadoAtual == 8 && $elemento == 'id')) {

            if(!in_array($valor, $this->tabelaSimbolos)) {
                $this->tabelaSimbolos[] = $valor;
            } else {
                echo 'VÁRIAVEL (' . $valor . ') JÁ DECLARADA! </br>';
            }


        } else if ($estadoAtual == 15 && ($elemento == 'int' || $elemento == 'string')) { //esse vem primeiro 
            $this->tabelaTok[] = $elemento;
        } 



        // foreach($this->tabelaTok as $valor) {
        //     echo 'TABELA ('.$valor.')';
        // }

        // if (in_array($el, ['int', 'string', 'float', 'char'])) {
        //     $this->tipoAux = $el;  
        //     echo "Tipo da variável definido: " . $this->tipoAux . "</br>";
        //     $this->declarado = false; // Variável ainda não foi declarada
        // }
        // elseif (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $el)) {
        //     $this->aux = $el; // Armazena o nome da variável
        //     echo "ID da variável detectado: " . $this->aux . "</br>";

        //     $variavelEncontrada = false;
        //     foreach ($this->tabelaSimbolos as $variavel) {
        //         if ($variavel->id === $this->aux) {
        //             $variavelEncontrada = true;
        //             break;
        //         }
        //     }

        // }
        

 
    //     elseif ($el === "=") {
    //         // Verifica se a variável foi declarada
    //         $this->declararVariavel($this->aux);
    //         // Atribuição do valor à variável
    //         echo "Atribuindo valor à variável '{$this->aux}'</br>";
    //     }
    //     // Caso o token seja algo inesperado
    //     else {
    //         echo "Erro: Token inesperado '{$el}'</br>";
    //     }
    // }

           
}

}