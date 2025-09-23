<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definição dos não terminais
define('NAO_TERMINAIS', [
    1 => 'PROGRAMA',
    2 => 'DECLARACOES', 
    3 => 'DECLARACAO',
    4 => 'FUNCAO',
    5 => 'PARAMETROS',
    6 => 'COMANDOS',
    7 => 'COMANDO',
    8 => 'ATRIBUICAO',
    9 => 'EXPRESSAO',
    10 => 'IF',
    11 => 'WHILE',
    12 => 'FOR',
    13 => 'TIPO',
    14 => 'COMPARADOR'
]);

class Token {
    public $tok;
    public $valor;
    
    public function __construct($tok, $valor) {
        $this->tok = $tok;
        $this->valor = $valor;
    }
}

class AnalisadorSemantico {
    private $tabelaSimbolos = [];
    private $funcoes = [];
    private $escopoAtual = 'global';
    
    public function __construct() {
        $this->tabelaSimbolos = [];
        $this->funcoes = [];
    }
    
    public function declararVariavel($nome, $tipo) {
        $chave = $this->escopoAtual . '.' . $nome;
        if (isset($this->tabelaSimbolos[$chave])) {
            echo "ERRO: Variável '$nome' já declarada no escopo atual<br>";
            return false;
        }
        $this->tabelaSimbolos[$chave] = $tipo;
        echo "Variável '$nome' do tipo '$tipo' declarada no escopo '$this->escopoAtual'<br>";
        return true;
    }
    
    public function declararFuncao($nome) {
        if (isset($this->funcoes[$nome])) {
            echo "ERRO: Função '$nome' já declarada<br>";
            return false;
        }
        $this->funcoes[$nome] = true;
        $this->escopoAtual = $nome;
        echo "Função '$nome' declarada<br>";
        return true;
    }
    
    public function verificarVariavel($nome) {
        $chaveLocal = $this->escopoAtual . '.' . $nome;
        $chaveGlobal = 'global.' . $nome;
        
        if (isset($this->tabelaSimbolos[$chaveLocal]) || isset($this->tabelaSimbolos[$chaveGlobal])) {
            return true;
        }
        echo "ERRO: Variável '$nome' não declarada<br>";
        return false;
    }
    
    public function sairEscopo() {
        $this->escopoAtual = 'global';
    }
}
 // odeio esse inferno 
class SLR {
    private $afd;
    public $historico = [];
    private $as;
    
    public function __construct() {
        $this->as = new AnalisadorSemantico();
        
        // Tabela SLR simplificada
        $this->afd = [
            // Estado 0 - Inicial
            0 => [
                'ACTION' => ['function' => 'S 1', 'int' => 'S 2', 'float' => 'S 2', 'if' => 'S 3', 'while' => 'S 4', 'for' => 'S 5', 'id' => 'S 6', 'read' => 'S 7', 'write' => 'S 8'],
                'GOTO' => [1 => ['$' => 999]]
            ],
            
            // Estado 1 - function
            1 => [
                'ACTION' => ['id' => 'S 10'],
                'GOTO' => []
            ],
            
            // Estado 2 - tipos (int, float)
            2 => [
                'ACTION' => ['id' => 'S 11'],
                'GOTO' => []
            ],
            
            // Estado 3 - if
            3 => [
                'ACTION' => ['(' => 'S 12'],
                'GOTO' => []
            ],
            
            // Estado 4 - while
            4 => [
                'ACTION' => ['(' => 'S 13'],
                'GOTO' => []
            ],
            
            // Estado 5 - for
            5 => [
                'ACTION' => ['(' => 'S 14'],
                'GOTO' => []
            ],
            
            // Estado 6 - id (pode ser atribuição ou chamada de função)
            6 => [
                'ACTION' => ['=' => 'S 15', '(' => 'S 16'],
                'GOTO' => []
            ],
            
            // Estado 7 - read
            7 => [
                'ACTION' => ['(' => 'S 17'],
                'GOTO' => []
            ],
            
            // Estado 8 - write
            8 => [
                'ACTION' => ['(' => 'S 18'],
                'GOTO' => []
            ],
            
            // Estado 10 - function id
            10 => [
                'ACTION' => ['(' => 'S 20'],
                'GOTO' => []
            ],
            
            // Estado 11 - tipo id (declaração de variável)
            11 => [
                'ACTION' => [';' => 'S 21'],
                'GOTO' => []
            ],
            
            // Estado 12 - if (
            12 => [
                'ACTION' => ['id' => 'S 22', 'num' => 'S 23'],
                'GOTO' => []
            ],
            
            // Estado 13 - while (
            13 => [
                'ACTION' => ['id' => 'S 24', 'num' => 'S 25'],
                'GOTO' => []
            ],
            
            // Estado 14 - for (
            14 => [
                'ACTION' => ['id' => 'S 26'],
                'GOTO' => []
            ],
            
            // Estado 15 - id =
            15 => [
                'ACTION' => ['id' => 'S 27', 'num' => 'S 28'],
                'GOTO' => []
            ],
            
            // Estado 16 - id ( (chamada de função)
            16 => [
                'ACTION' => [')' => 'S 29', 'id' => 'S 30', 'num' => 'S 31'],
                'GOTO' => []
            ],
            
            // Estado 17 - read (
            17 => [
                'ACTION' => ['id' => 'S 32'],
                'GOTO' => []
            ],
            
            // Estado 18 - write (
            18 => [
                'ACTION' => ['id' => 'S 33', 'num' => 'S 34', 'string' => 'S 35'],
                'GOTO' => []
            ],
            
            // Estados de aceitação e reduções
            20 => ['ACTION' => [')' => 'S 40'], 'GOTO' => []],
            21 => ['ACTION' => ['$' => 'R 3 3'], 'GOTO' => []],
            22 => ['ACTION' => ['>' => 'S 41', '<' => 'S 42', '==' => 'S 43', '!=' => 'S 44'], 'GOTO' => []],
            29 => ['ACTION' => [';' => 'S 45'], 'GOTO' => []],
            32 => ['ACTION' => [')' => 'S 46'], 'GOTO' => []],
            40 => ['ACTION' => ['{' => 'S 47'], 'GOTO' => []],
            45 => ['ACTION' => ['$' => 'R 7 7'], 'GOTO' => []],
            46 => ['ACTION' => [';' => 'S 48'], 'GOTO' => []],
            47 => ['ACTION' => ['}' => 'S 49'], 'GOTO' => []],
            48 => ['ACTION' => ['$' => 'R 7 7'], 'GOTO' => []],
            49 => ['ACTION' => ['$' => 'R 4 4'], 'GOTO' => []],
            
            // Estado de aceitação final
            999 => ['ACTION' => ['$' => 'ACC'], 'GOTO' => []]
        ];
    }
    
    public function parser($entrada) {
        $pilha = [0];
        $i = 0;
        
        while ($i < count($entrada)) {
            $tokenAtual = $entrada[$i]->tok;
            $valorAtual = $entrada[$i]->valor;
            
            $estadoAtual = end($pilha);
            
            if (!isset($this->afd[$estadoAtual]['ACTION'][$tokenAtual])) {
                $this->historico[] = [
                    'pilha' => implode(' ', $pilha),
                    'acao' => 'ERRO',
                    'token' => $tokenAtual,
                    'valor' => $valorAtual
                ];
                echo "ERRO SINTÁTICO: Token '$tokenAtual' não esperado no estado $estadoAtual<br>";
                return false;
            }
            
            $acao = $this->afd[$estadoAtual]['ACTION'][$tokenAtual];
            $tipoAcao = explode(' ', $acao);
            
            $this->historico[] = [
                'pilha' => implode(' ', $pilha),
                'acao' => $acao,
                'token' => $tokenAtual,
                'valor' => $valorAtual
            ];
            
            switch ($tipoAcao[0]) {
                case 'S': // Shift
                    array_push($pilha, intval($tipoAcao[1]));
                    $this->analisar_semantica($estadoAtual, $entrada[$i]);
                    $i++;
                    break;
                    
                case 'R': // Reduce
                    $numPop = intval($tipoAcao[1]);
                    $naoTerminal = intval($tipoAcao[2]);
                    
                    for ($j = 0; $j < $numPop; $j++) {
                        array_pop($pilha);
                    }
                    
                    $novoEstado = end($pilha);
                    if (isset($this->afd[$novoEstado]['GOTO'][$naoTerminal])) {
                        $goto = $this->afd[$novoEstado]['GOTO'][$naoTerminal];
                        if (isset($goto[$tokenAtual])) {
                            array_push($pilha, $goto[$tokenAtual]);
                        }
                    }
                    break;
                    
                case 'ACC': // Accept
                    echo "ANÁLISE SINTÁTICA CONCLUÍDA COM SUCESSO!<br>";
                    return true;
                    
                default:
                    echo "ERRO: Ação desconhecida<br>";
                    return false;
            }
        }
        
        return false;
    }
    
    private function analisar_semantica($estado, $token) {
        $elemento = $token->tok;
        $valor = $token->valor;
        
        // Análise semântica baseada no estado e token
        switch ($estado) {
            case 1: // function
                if ($elemento == 'id') {
                    $this->as->declararFuncao($valor);
                }
                break;
                
            case 2: // tipo
                $this->tipoAtual = $valor;
                break;
                
            case 11: // declaração de variável
                if (isset($this->tipoAtual)) {
                    $this->as->declararVariavel($valor, $this->tipoAtual);
                    unset($this->tipoAtual);
                }
                break;
                
            case 6: // uso de variável
            case 15: // atribuição
            case 17: // read
            case 18: // write
                if ($elemento == 'id') {
                    $this->as->verificarVariavel($valor);
                }
                break;
        }
    }
    
    public function getHistorico() {
        return $this->historico;
    }
}

// Função para testar o analisador
function testarAnalisador() {
    $slr = new SLR();
    
    // Exemplo de entrada: function soma() { int x; x = 5; }
    $tokens = [
        new Token('function', 'function'),
        new Token('id', 'soma'),
        new Token('(', '('),
        new Token(')', ')'),
        new Token('{', '{'),
        new Token('int', 'int'),
        new Token('id', 'x'),
        new Token(';', ';'),
        new Token('id', 'x'),
        new Token('=', '='),
        new Token('num', '5'),
        new Token(';', ';'),
        new Token('}', '}'),
        new Token('$', '$') // EOF
    ];
    
    echo "<h3>Testando analisador com: function soma() { int x; x = 5; }</h3>";
    $resultado = $slr->parser($tokens);
    
    echo "<h4>Histórico de análise:</h4>";
    foreach ($slr->getHistorico() as $passo) {
        echo "Pilha: [{$passo['pilha']}] | Ação: {$passo['acao']} | Token: {$passo['token']} | Valor: {$passo['valor']}<br>";
    }
    
    return $resultado;
}

// Executar teste
testarAnalisador();

?>