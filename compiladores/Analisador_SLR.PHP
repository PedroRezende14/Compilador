<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
require('analisador_semantico.php');

define('NAO_TERMINAIS', [
    1 => 'PROGRAMA',
    2 => 'LISTA_VAR', 
    3 => 'VAR', 
    4 => 'TIPO',
    5 => 'LISTA_COMANDOS',
    6 => 'ENQUANTO',
    7 => 'SE', 
    8 => 'PARA',
    9 => 'ATRIBUTO',
    10 => 'COMANDOS',
    11 => 'ATRBUICAO',
    12 => 'LEITURA',
    13 => 'INCREMENTO',
    14 => 'IMPRESSAO',
    15 => 'OPERACAO',
    16 => 'OP_INCREMENTO',
    17 => 'OPERADOR',
    18 => 'COMPARADOR',
    19 => 'SENAO',
    20 => 'COMPARACAO',
    21 => 'DEC_VAR',
    22 => 'FUNCAO'
]);

class SLR{

    private $afd;
    public $historico = [];
    public $lexico;
    private $as;



    public function __construct() {
        $this->afd = array(
        0 => ['ACTION' => ['programa' => 'S 2'],
            'GOTO' => [1 => ['$' => 1]]],
        1 => ['ACTION' => ['$' => 'ACC'], 'GOTO' => []],
        2 => ['ACTION' => ['id' => 'S 3'], 'GOTO' => []],
        3 => ['ACTION' => ['ap' => 'S 4'], 'GOTO' => []],
        4 => ['ACTION' => ['int' => 'S 5', 'char' => 'S 6', 'array' => 'S 7', 'string' => 'S 8', 'fp' => 'R 0 2'], //lista_var
            'GOTO' => [4 => ['id' => 9], 3 => ['int' => 11, 'char' => 11, 'string' => 11, 'array' => 11, 'fp' => 13], 2 => ['fp' => 13]]],
        5 => ['ACTION' => ['id' => 'R 1 4'], 'GOTO' => []],
        6 => ['ACTION' => ['id' => 'R 1 4'], 'GOTO' => []],
        7 => ['ACTION' => ['id' => 'R 1 4'], 'GOTO' => []],
        8 => ['ACTION' => ['id' => 'R 1 4'], 'GOTO' => []],
        9 => ['ACTION' => ['id' => 'S 10'], 'GOTO' => []],
        10=> ['ACTION' => ['fp' => 'R 2 3', 'int' => 'R 2 3', 'char' => 'R 2 3', 'array' => 'R 2 3', 'string' => 'R 2 3'], 'GOTO' => []],
        11=> ['ACTION' => ['int' => 'S 5', 'char' => 'S 6', 'array' => 'S 7', 'string' => 'S 8', 'fp' => 'R 0 2'], 
            'GOTO' => [2 => ['fp' => 12]]],
        12=> ['ACTION' => ['fp' => 'R 3 2'], 'GOTO' => []],
        13=> ['ACTION' => ['fp' => 'S 14'], 'GOTO' => []],
        14=> ['ACTION' => ['ac' => 'S 15'], 'GOTO' => []],
        15=> ['ACTION' => ['fc' => 'R 0 5', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'int' => 'S 5', 'char'=>'S 6', 'array'=> 'S 7', 'string'=> 'S 8','funcao' => 'S 202', 'id' => 'S 87'],
            'GOTO' => [5 => ['id'=> 216,'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 69, 'funcao' => 215, 'int' => 210, 'char' => 210, 'string' => 210, 'array' => 210],  //lista_comandos
                       6 => ['id'=> 61,'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 69, 'funcao' => 61,  'int' => 61,  'char' => 61,  'string' => 61,  'array' => 61],  //enquanto
                       7 => ['id'=> 63,'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 69, 'funcao' => 63,  'int' => 63,  'char' => 63,  'string' => 63,  'array' => 63],  //se
                       8 => ['id'=> 64,'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 69, 'funcao' => 64,  'int' => 64,  'char' => 64,  'string' => 64,  'array' => 64],  //para
                       19 =>['id'=> 62,'enquanto' => 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 69, 'funcao' => 62,  'int' => 62,  'char' => 62,  'string' => 62,  'array' => 62],  //senao
                       21 =>['id'=> 210,'enquanto' => 210,'se' => 210,'senao' => 210,'para' => 210,'fc' => 69, 'funcao' => 210, 'int' => 210, 'char' => 210, 'string' => 210, 'array' => 210],//dec_var
                       22 =>['id'=> 215,'enquanto' => 215,'se' => 215,'senao' => 215,'para' => 215,'fc' => 69, 'funcao' => 215, 'int' => 215, 'char' => 215, 'string' => 215, 'array' => 215], //funcao
                       11=> ['id'=> 216,'enquanto' => 216,'se' => 216,'senao' => 216,'para' => 216,'fc' => 69, 'funcao' => 216, 'int' => 216, 'char' => 216, 'string' => 216, 'array' => 216], //atribuicao
                       4 => ['id' => 211]]], // TIPO -> <tipo> . id pv
        16=> ['ACTION' => ['ap' => 'S 17'], 'GOTO' => []],
        17=> ['ACTION' => ['id' => 'S 18'], 'GOTO' => []],
        18=> ['ACTION' => ['maior' => 'S 19', 'menor' => 'S 20', 'maiorIgual' => 'S 22', 'menorIgual' => 'S 23', 'igual' => 'S 21', 'diferente' => 'S 24'],
            'GOTO' => [18 => ['const' => 83, 'caracter' => 83, 'string' => 83, 'id' => 83]]],
        19=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        20=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        21=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        22=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        23=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        24=> ['ACTION' => ['id' => 'R 1 18', 'const' => 'R 1 18', 'caracter' => 'R 1 18', 'string' => 'R 1 18'], 'GOTO' => []],
        25=> ['ACTION' => ['fp' => 'R 1 9'], 'GOTO' => []], //antes era fp
        26=> ['ACTION' => ['fp' => 'R 1 9'], 'GOTO' => []],
        27=> ['ACTION' => ['fp' => 'R 1 9'], 'GOTO' => []],
        28=> ['ACTION' => ['fp' => 'R 1 9'], 'GOTO' => []],
        100=> ['ACTION' => ['pv' => 'R 1 9'], 'GOTO' => []], //novos estados
        101=> ['ACTION' => ['pv' => 'R 1 9'], 'GOTO' => []],
        102=> ['ACTION' => ['pv' => 'R 1 9'], 'GOTO' => []],
        103=> ['ACTION' => ['pv' => 'R 1 9'], 'GOTO' => []],
        29=> ['ACTION' => ['ap' => 'S 30'], 'GOTO' => []],
        30=> ['ACTION' => ['id' => 'S 31'], 'GOTO' => []],
        31=> ['ACTION' => ['maior' => 'S 19', 'menor' => 'S 20', 'maiorIgual' => 'S 22', 'menorIgual' => 'S 23', 'igual' => 'S 21', 'diferente' => 'S 24'],
            'GOTO' => [18 => ['const' => 32, 'caracter' => 32, 'string' => 32, 'id' => 32]]],
        32=> ['ACTION' => ['id' => 'S 25', 'const' => 'S 26', 'caracter' => 'S 27', 'string' => 'S 28'],
            'GOTO' => [9 => ['fp' => 33]]],
        33=> ['ACTION' => ['fp' => 'S 34'], 'GOTO' => []],
        34=> ['ACTION' => ['ac' => 'S 35'], 'GOTO' => []],
        35=> ['ACTION' => ['id' => 'S 87', 'leia' => 'S 40', 'imprima' => 'S 50'],
            'GOTO' => [11 => ['fc' => 93], 12 => ['fc' => 93], 14 => ['fc' => 93], 15 => ['fc' => 93], 13 => ['fc' => 93]]],
        37=> ['ACTION' => ['id' => 'S 100', 'const' => 'S 101', 'caracter' => 'S 102', 'string' => 'S 103'],
            'GOTO' => [9 => ['pv' => 38]]],
        38=> ['ACTION' => ['pv' => 'S 39'], 'GOTO' => []],
        39=> ['ACTION' => ['fc' => 'R 4 11', 'leia' => 'R 4 11', 'imprima' => 'R 4 11', 'enquanto' => 'R 4 11', 'senao' => 'R 4 11', 'se' => 'R 4 11', 'para' => 'R 4 11', 'id' => 'R 4 11', 'funcao' => 'R 4 11', 'string' => 'R 4 11', 'int' => 'R 4 11', 'char' => 'R 4 11', 'array' => 'R 4 11'], 'GOTO' => []], //adicionei o id pois no para vai usar 
        40=> ['ACTION' => ['ap' => 'S 41'], 'GOTO' => []],
        41=> ['ACTION' => ['id' => 'S 25', 'const' => 'S 26', 'caracter' => 'S 27', 'string' => 'S 28'],
            'GOTO' => [9 => ['fp' => 42]]],
        42=> ['ACTION' => ['fp' => 'S 43'], 'GOTO' => []],
        43=> ['ACTION' => ['pv' => 'S 44'], 'GOTO' => []],
        44=> ['ACTION' => ['leia' => 'R 5 12', 'imprima' => 'R 5 12', 'enquanto' => 'R 5 12', 'senao' => 'R 5 12', 'se' => 'R 5 12', 'para' => 'R 5 12', 'fc' => 'R 5 12'], 'GOTO' => []],
        46=> ['ACTION' => ['pv' => 'R 1 16', 'fp' => 'R 1 16'], 'GOTO' => []],
        47=> ['ACTION' => ['pv' => 'R 1 16', 'fp' => 'R 1 16'], 'GOTO' => []],
        48=> ['ACTION' => ['pv' => 'S 49'], 'GOTO' => []],
        49=> ['ACTION' => ['fp' => 'R 3 13', 'fc' => 'R 3 13', 'funcao' => 'R 3 13', 'leia' => 'R 3 13','imprima' => 'R 3 13','enquanto' => 'R 3 13', 'se' => 'R 3 13', 'senao' => 'R 3 13', 'para'  => 'R 3 13', ], 'GOTO' => []],
        50=> ['ACTION' => ['ap' => 'S 51'], 'GOTO' => []],
        51=> ['ACTION' => ['id' => 'S 25', 'const' => 'S 26', 'caracter' => 'S 27', 'string' => 'S 28'],
            'GOTO' => [9 => ['fp'=> 52]]],
        52=> ['ACTION' => ['fp' => 'S 53'], 'GOTO' => []],
        53=> ['ACTION' => ['pv' => 'S 54'], 'GOTO' => []],
        54=> ['ACTION' => ['leia' => 'R 5 14', 'imprima' => 'R 5 14', 'enquanto' => 'R 5 14', 'senao' => 'R 5 14', 'se' => 'R 5 14', 'para' => 'R 5 14', 'fc' => 'R 5 14'], 'GOTO'=> []],
        56=> ['ACTION' => ['id' => 'S 25', 'const' => 'S 26', 'caracter' => 'S 27', 'string' => 'S 28'],
            'GOTO' => [9 => ['fp' => 57]]],
        57=> ['ACTION' => ['fp' => 'S 58'], 'GOTO' => []],
        58=> ['ACTION' => ['pv' => 'S 59'], 'GOTO' => []],
        59=> ['ACTION' => ['leia' => 'R 5 15', 'imprima' => 'R 5 15', 'enquanto' => 'R 5 15', 'senao' => 'R 5 15', 'se' => 'R 5 15', 'para' => 'R 5 15', 'fc' => 'R 5 15', 'funcao' => 'R 5 15'], 'GOTO' => []],
        60=> ['ACTION' => ['ac' => 'S 95'], 'GOTO' => []],

        61=> ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
            'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                      6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                      7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                      8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                      19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                      21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                      22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                      11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
            4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]

        62=> ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]

        63=> ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]

        64=> ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]

        210 => ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]
          
                          
        69=> ['ACTION' => ['fc' => 'S 70'], 'GOTO' => []], // dupliquei aqui, que INFERNO 
        250 => ['ACTION' => ['fc' => 'R 2 5'], 'GOTO' =>[]],

        70=> ['ACTION' => ['$' => 'R 8 1'], 'GOTO' => []],
        71=> ['ACTION' => ['ap' => 'S 72'], 'GOTO' => []],
        72=> ['ACTION' => ['id' => 'S 87'],
            'GOTO' => [11 => ['id' => 73]]],
        73=> ['ACTION' => ['id' => 'S 80'],
            'GOTO' => [20 => ['id' => 74]]],
        74=> ['ACTION' => ['id' => 'S 201'],
            'GOTO' => [16 => ['fp' => 75]]],
        75=> ['ACTION' => ['fp' => 'S 76'],  'GOTO' => []],
        76=> ['ACTION' => ['ac' => 'S 77'], 'GOTO' => []],
        77=> ['ACTION' => ['id' => 'S 87', 'leia' => 'S 40', 'imprima' => 'S 50'],
            'GOTO' => [11 => ['fp' => 78, 'fc' => 78], 12 => ['fp' => 78, 'fc' => 78], 14 => ['fp' => 78, 'fc' => 78], 15 => ['fp' => 78, 'fc' => 78], 16 => ['fp' => 78, 'fc' => 78]]],
        78=> ['ACTION' => ['fc' => 'S 79'], 'GOTO' => []],
        79=> ['ACTION' => ['id' => 'S 87', 'leia' => 'R 10 8', 'imprima' => 'R 10 8', 'enquanto' => 'R 10 8', 'senao' => 'R 10 8', 'se' => 'R 10 8', 'para' => 'R 10 8', 'fc' => 'R 10 8', 'int' => 'R 10 8', 'char' => 'R 10 8', 'array' => 'R 10 8', 'string' => 'R 10 8', 'funcao' => 'R 10 8'], 'GOTO' => []],
        80=> ['ACTION' => ['maior' => 'S 19', 'menor' => 'S 20', 'maiorIgual' => 'S 22', 'menorIgual' => 'S 23', 'igual' => 'S 21', 'diferente' => 'S 24'],
            'GOTO' => [18 => ['id' => 81, 'const' => 81, 'caracter' => 81, 'string' => 81 ]]],
        81=> ['ACTION' => ['id' => 'S 100', 'const' => 'S 101', 'caracter' => 'S 102', 'string' => 'S 103'],
            'GOTO' => [9 => ['pv' => 960]]],
        83=> ['ACTION' => ['id' => 'S 25', 'const' => 'S 26', 'caracter' => 'S 27', 'string' => 'S 28'],
            'GOTO' => [9 => ['fp' => 84]]] ,
        84=> ['ACTION' => ['fp' => 'S 85'], 'GOTO' => []],
        85=> ['ACTION' => ['ac' => 'S 86'], 'GOTO' => []],
        86=> ['ACTION' => ['id' => 'S 87', 'leia' => 'S 40', 'imprima' => 'S 50'],
            'GOTO' => [11 => ['fc' => 92], 12 => ['fc' => 92], 14 => ['fc' => 92], 15 => ['fc' => 92], 13 => ['fc' => 92]]],
        87=> ['ACTION' => ['mais' => 'S 88', 'menos' => 'S 89', 'div' => 'S 90', 'mult' => 'S 91', 'igual' => 'S 37', 'menos_m' => 'S 46', 'mais_m' => 'S 47'],
            'GOTO' => [16 => ['pv' => 48], 17 => ['id' => 56, 'const' => 56, 'caracter' => 56, 'string' => 56]]],
        88=> ['ACTION' => ['id' => 'R 1 17', 'const' => 'R 1 17', 'caracter' => 'R 1 17', 'string' => 'R 1 17'], 'GOTO' => []],
        89=> ['ACTION' => ['id' => 'R 1 17', 'const' => 'R 1 17', 'caracter' => 'R 1 17', 'string' => 'R 1 17'], 'GOTO' => []],
        90=> ['ACTION' => ['id' => 'R 1 17', 'const' => 'R 1 17', 'caracter' => 'R 1 17', 'string' => 'R 1 17'], 'GOTO' => []],
        91=> ['ACTION' => ['id' => 'R 1 17', 'const' => 'R 1 17', 'caracter' => 'R 1 17', 'string' => 'R 1 17'], 'GOTO' => []],
        92=> ['ACTION' => ['fc' => 'S 98'], 'GOTO' => []],
        93=> ['ACTION' => ['fc' => 'S 94'], 'GOTO' => []],
        94=> ['ACTION' => ['leia' => 'R 9 7', 'imprima' => 'R 9 7', 'enquanto' => 'R 9 7', 'senao' => 'R 9 7', 'se' => 'R 9 7', 'para' => 'R 9 7', 'fc' => 'R 9 7', 'int' => 'R 9 7', 'char' => 'R 9 7', 'string' => 'R 9 7', 'array' => 'R 9 7',], 'GOTO' => []],
        95=> ['ACTION' => ['id' => 'S 87', 'leia' => 'S 40', 'imprima' => 'S 50'],
            'GOTO' => [11 => ['fc' => 96], 12 => ['fc'=>96], 14 => ['fc'=>96], 15 => ['fc'=>96], 13=>['fc'=>96]]],
        96=> ['ACTION' => ['fc' => 'S 97'], 'GOTO' => []],
        960=> ['ACTION' => ['pv' => 'S 970'], 'GOTO' => []],
        970=> ['ACTION' => ['id' => 'R 4 20'], 'GOTO' =>[]],

        97=> ['ACTION' => ['leia' => 'R 4 19', 'imprima' => 'R 4 19', 'enquanto' => 'R 4 19', 'senao' => 'R 4 19', 'se' => 'R 4 19', 'para' => 'R 4 19', 'fc' => 'R 4 19', 'int' => 'R 4 19', 'char' => 'R 4 19', 'array' => 'R 4 19', 'string' => 'R 4 19','funcao' => 'R 4 19'], 'GOTO' => []], 
        98=> ['ACTION' => ['leia' => 'R 9 6', 'imprima' => 'R 9 6', 'enquanto' => 'R 9 6', 'senao' => 'R 9 6', 'se' => 'R 9 6', 'para' => 'R 9 6', 'fc' => 'R 9 6', 'int' => 'R 9 6','char' => 'R 9 6', 'string' => 'R 9 6','array' => 'R 9 6'], 'GOTO' => []],
        201 => ['ACTION' => ['mais_m' => 'S 47', 'menos_m' => 'S 46'], 'GOTO' => [16 => ['fp' => 75]]],
        211 => ['ACTION' => ['id' => 'S 212'], 'GOTO' =>[]],
        212 => ['ACTION' => ['pv' => 'S 213'], 'GOTO' => []],
        213 => ['ACTION' => ['id' => 'R 3 21', 'leia' => 'R 3 21', 'imprima' => 'R 3 21', 'fc' => 'R 3 21', 'int' => 'R 3 21', 'char' => 'R 3 21', 'array' => 'R 3 21', 'string' => 'R 3 21', 'enquanto' => 'R 3 21', 'senao' => 'R 3 21', 'se' => 'R 3 21', 'para' => 'R 3 21', 'funcao' => 'R 3 21'], 'GOTO'=>[]],
        202 => ['ACTION' => ['id' => 'S 203'], 'GOTO' => []],
        203 => ['ACTION' => ['ap' => 'S 204'], 'GOTO' => []],
        204 => ['ACTION' => ['int' => 'S 5', 'char' => 'S 6', 'array'=>'S 7', 'string'=>'S 8', 'fp' => 'R 0 2'], 
                'GOTO' => [4 => ['id' => 9], 3 => ['int' => 11, 'char' => 11, 'string' => 11, 'array' => 11, 'fp' => 205], 2 => ['fp' => 205]]],

        207 => ['ACTION' => ['int' => 'S 5', 'char' => 'S 6', 'array'=>'S 7', 'string'=>'S 8', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se'=>'S 29', 'para' => 'S 71', 'fp' => 'S 205', 'fc' => 'R 0 5', 'id' => 'S 87'], 
                'GOTO' => [5 => ['id' => 216,'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 209, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210],  //lista_comandos
                            6 => ['id' => 210 ,'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 209, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61],  //enquanto
                            7 => ['id' => 210 ,'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 209, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63],  //se
                            8 => ['id' => 210 ,'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 209, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64],  //para
                            19 => ['id' => 210 ,'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 209, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62],  //senao
                            21 => ['id' => 210 ,'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 209, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210],//dec_var
                            11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 209, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216],
                            4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]

        205 => ['ACTION' => ['fp' => 'S 206'], 'GOTO' => []],
        206 => ['ACTION' => ['ac' => 'S 207'], 'GOTO' => []],

        209 => ['ACTION' => ['fc' => 'S 214'], 'GOTO' => []],
        214 => ['ACTION' => ['id' => 'R 8 22', 'leia' => 'R 8 22','imprima' => 'R 8 22','fc' => 'R 8 22', 'int' => 'R 8 22', 'char' => 'R 8 22', 'array' => 'R 8 22', 'string' => 'R 8 22', 'enquanto' => 'R 8 22', 'se' => 'R 8 22', 'senao' => 'R 8 22', 'para' => 'R 8 22', 'funcao' => 'R 8 22'], 'GOTO' => []],
        215 => ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]


        216 => ['ACTION' => ['leia' => 'S 40', 'imprima' => 'S 50', 'enquanto' => 'S 16', 'senao' => 'S 60', 'se' => 'S 29', 'para' => 'S 71', 'fc' => 'R 0 5', 'int' => 'S 5', 'char'=>'S 6', 'array'=>'S 7', 'string' => 'S 8', 'funcao' => 'S 202', 'id' => 'S 87'],
        'GOTO' => [5 => ['id'=> 216, 'enquanto' => 61, 'se' => 63, 'senao' => 62, 'para' => 64, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 215],  //lista_comandos
                  6 =>  ['id'=> 61, 'enquanto' => 61, 'se' => 61, 'senao' => 61, 'para' => 61, 'fc' => 250, 'int' => 61, 'string' => 61, 'char' => 61, 'array' => 61, 'funcao' => 61],  //enquanto
                  7 =>  ['id'=> 63, 'enquanto' => 63, 'se' => 63, 'senao' => 63, 'para' => 63, 'fc' => 250, 'int' => 63, 'string' => 63, 'char' => 63, 'array' => 63, 'funcao' => 63],  //se
                  8 =>  ['id'=> 64, 'enquanto' => 64, 'se' => 64, 'senao' => 64, 'para' => 64, 'fc' => 250, 'int' => 64, 'string' => 64, 'char' => 64, 'array' => 64, 'funcao' => 64],  //para
                  19 => ['id'=> 62, 'enquanto'=> 62, 'se' => 62, 'senao' => 62, 'para' => 62, 'fc' => 250, 'int' => 62, 'string' => 62, 'char' => 62, 'array' => 62, 'funcao' => 62],  //senao
                  21 => ['id'=> 210, 'enquanto'=> 210, 'se' => 210, 'senao' => 210, 'para' => 210, 'fc' => 250, 'int' => 210, 'string' => 210, 'char' => 210, 'array' => 210, 'funcao' => 210],//dec_var
                  22 => ['id'=> 215, 'enquanto'=> 215, 'se' => 215, 'senao' => 215, 'para' => 215, 'fc' => 250, 'int' => 215, 'string' => 215, 'char' => 215, 'array' => 215, 'funcao' => 215], // funcao
                  11 => ['id'=> 216, 'enquanto'=> 216, 'se' => 216, 'senao' => 216, 'para' => 216, 'fc' => 250, 'int' => 216, 'string' => 216, 'char' => 216, 'array' => 216, 'funcao' => 216],
        4 =>  ['id' => 211]]], // TIPO -> <tipo> . id pv]
        );

        $this->as = new analisador_semantico();
    }

    public function parser($entrada){
        $pilha = array();
        array_push($pilha, 0);
        // echo "\nPilha: " . implode(' ', $pilha);
    
        $i = 0;
        while ($i < count($entrada)) {
            $tokenAtual = $entrada[$i]->tok;
    
            // Verifica se o token existe no estado atual
            if (!array_key_exists($tokenAtual, $this->afd[end($pilha)]['ACTION'])) {
                // Caso não exista, emite um erro e retorna falso
                $this->historico[] = [
                    'pilha' => implode(' ', $pilha),
                    'acao' => 'Erro',
                    'token' => $tokenAtual
                ];
                // echo "\nErro: Token '{$tokenAtual}' não encontrado no estado " . end($pilha);
                return false;
            }
    
            $move = $this->afd[end($pilha)]['ACTION'][$tokenAtual];
            $acao = explode(' ', $move);
            // echo " | Ação: " . $move;
    
            // Registra o histórico da ação
            $this->historico[] = [
                'pilha' => implode(' ', $pilha),
                'acao' => $move,
                'token' => $tokenAtual
            ];
    
            switch($acao[0]){
                case 'S': // Shift - Empilha e avança o ponteiro
                    array_push($pilha, $acao[1]);
                    $i++;
                    break;
                case 'R': // Reduce - Desempilha e Desvia (para indicar a redução)
                    for ($j = 0; $j < $acao[1]; $j++) {
                        array_pop($pilha);
                    }
                    // echo ' | Reduziu para ' . NAO_TERMINAIS[$acao[2]];                    
                    $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][$entrada[$i]->tok];
                    array_push($pilha, $desvio);
                    break;
                case 'ACC': // Accept
                    // echo ' Sintático Ok';
                    // $this->historico[] = [
                    //     'pilha' => implode(' ', $pilha),
                    //     'acao' => 'Accept',
                    //     'token' => 'EOF'
                    // ];
                    return true;
                default:
                    $this->historico[] = [
                        'pilha' => implode(' ', $pilha),
                        'acao' => 'Erro',
                        'token' => $entrada[$i]->tok
                    ];
                    return false;
            }
    
            // Exibe o estado atual da pilha
            // echo "\nPilha: " . implode(' ', $pilha);
            // echo " Token: " . $entrada[$i]->tok;

            
            $this->as->analiseSemantica(end($pilha), $entrada[$i]);
            
        }
        
        
        return false; // Caso o loop termine sem aceitar
    }
    

    public function getPilha() {
        return array_column($this->historico, 'pilha');
    }

    // Retorna o histórico das ações
    public function getAcao() {
        return array_column($this->historico, 'acao');
    }

    // Retorna o histórico dos tokens
    public function getToken() {
        return array_column($this->historico, 'token');
    }
    
    

}


?>