<?php
require("token.php");
require("Analisador_lexico.php");
require("Analisador_SLR.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada = str_replace(["\r", "\n"], ' ', $_POST["inputString"]);

    $analisador = new Analisador_lexico();
    $analisador->analisa($entrada);

    $asc = new SLR();
    $asc->parser($analisador->tokens);
    $historico = $asc->historico;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compilador SLR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }
        header {
            background: #0d6efd;
            color: #fff;
            padding: 15px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }
        .btn-analisar {
            background: #0d6efd;
            color: #fff;
        }
        .CodeMirror {
            width: 100% !important;
            height: 280px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        .token-cell {
            font-size: 14px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>

<header class="text-center">
    <h2 class="m-0">Compilador SLR</h2>
</header>

<div class="container py-4">

    <!-- Editor -->
    <div class="card p-3 mb-4">
        <h5 class="mb-3">Código Fonte</h5>
        <form method="POST" onsubmit="updateTextarea()">
            <textarea name="inputString" id="editor"><?php echo isset($_POST['inputString']) ? htmlspecialchars($_POST['inputString']) : ''; ?></textarea>
            <button type="submit" class="btn btn-analisar mt-3">Analisar</button>
        </form>
    </div>

    <!-- Tokens -->
    <?php if (!empty($analisador->tokens)): ?>
    <div class="card p-3 mb-4">
        <h5 class="mb-3">Tokens</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <tbody>
                    <tr>
                    <?php 
                        $count = 0;
                        foreach ($analisador->tokens as $item) {
                            echo "<td class='token-cell'>
                                    &lt;<strong>{$item->tok}, {$item->valor}</strong>&gt;
                                  </td>";
                            $count++;
                            if ($count % 5 == 0) echo "</tr><tr>";
                        }
                    ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Histórico -->
    <?php if (!empty($historico)): ?>
    <div class="card p-3">
        <h5 class="mb-3">Histórico de Análise</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Pilha</th>
                        <th>Ação</th>
                        <th>Token</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $item): ?>
                        <tr>
                            <td><?php echo $item['pilha']; ?></td>
                            <td><?php echo $item['acao']; ?></td>
                            <td><?php echo $item['token']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
        mode: "javascript",
        lineNumbers: true,
        theme: "default"
    });
    function updateTextarea() {
        document.getElementById("editor").value = editor.getValue();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
