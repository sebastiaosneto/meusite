<?php
require_once 'config/config.php';
requireLogin();
checkUserType(['admin', 'tecnico']);

$db = Database::getInstance()->getConnection();
$pageTitle = 'Relatório de Chamados';

// Filtros
$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';
$tipo_id = (int) ($_GET['tipo_atendimento_id'] ?? 0);
$empresa_id = (int) ($_GET['empresa_id'] ?? 0);

// Executar consulta apenas quando houver pelo menos um filtro preenchido
$filtroPreenchido = !empty($data_inicio) || !empty($data_fim) || $tipo_id > 0 || $empresa_id > 0;

$chamados = [];

if ($filtroPreenchido) {
    // Montar consulta – todos os filtros combinam com AND
    $where = [];
    $params = [];

    if (!empty($data_inicio)) {
        $where[] = "DATE(c.created_at) >= ?";
        $params[] = $data_inicio;
    }
    if (!empty($data_fim)) {
        $where[] = "DATE(c.created_at) <= ?";
        $params[] = $data_fim;
    }
    if ($tipo_id > 0) {
        $where[] = "c.tipo_atendimento_id = ?";
        $params[] = $tipo_id;
    }
    if ($empresa_id > 0) {
        $where[] = "c.empresa_id = ?";
        $params[] = $empresa_id;
    }

    // Técnico vê apenas chamados que atendeu ou pendentes
    if ($_SESSION['user_type'] === 'tecnico') {
        $where[] = "(c.tecnico_id = ? OR c.status IN ('pendente', 'reaberto'))";
        $params[] = $_SESSION['user_id'];
    }

    $whereClause = 'WHERE ' . implode(' AND ', $where);

    $sql = "
        SELECT c.*,
               e.nome as empresa_nome,
               u.nome as funcionario_nome,
               t.nome as tecnico_nome,
               ta.nome as tipo_nome
        FROM chamados c
        LEFT JOIN empresas e ON c.empresa_id = e.id
        LEFT JOIN usuarios u ON c.funcionario_id = u.id
        LEFT JOIN usuarios t ON c.tecnico_id = t.id
        LEFT JOIN tipos_atendimento ta ON c.tipo_atendimento_id = ta.id
        $whereClause
        ORDER BY c.created_at DESC
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $chamados = $stmt->fetchAll();
}

// Dados para filtros
$stmt = $db->query("SELECT id, nome FROM empresas WHERE ativo = 1 ORDER BY nome");
$empresas = $stmt->fetchAll();

$stmt = $db->query("SELECT id, nome FROM tipos_atendimento WHERE ativo = 1 ORDER BY nome");
$tipos_atendimento = $stmt->fetchAll();

$statusLabels = [
    'pendente' => 'Pendente',
    'em_atendimento' => 'Em atendimento',
    'finalizado' => 'Finalizado',
    'reaberto' => 'Reaberto',
    'cancelado' => 'Cancelado'
];

$prioridadeLabels = [
    'baixa' => 'Baixa',
    'media' => 'Média',
    'alta' => 'Alta',
    'urgente' => 'Urgente'
];

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Relatório de Chamados</h1>
</div>

<div class="card relatorio-filtros">
    <h3>Filtros</h3>
    <form method="GET" class="relatorio-form">
        <div class="filtros-grid">
            <div class="form-group">
                <label for="data_inicio">Data inicial</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo htmlspecialchars($data_inicio); ?>">
            </div>
            <div class="form-group">
                <label for="data_fim">Data final</label>
                <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?php echo htmlspecialchars($data_fim); ?>">
            </div>
            <div class="form-group">
                <label for="tipo_atendimento_id">Tipo de atendimento</label>
                <select id="tipo_atendimento_id" name="tipo_atendimento_id" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($tipos_atendimento as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>" <?php echo $tipo_id === (int)$tipo['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="empresa_id">Empresa</label>
                <select id="empresa_id" name="empresa_id" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa['id']; ?>" <?php echo $empresa_id === (int)$empresa['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empresa['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="filtros-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
            <a href="relatorios.php" class="btn btn-secondary"><i class="fas fa-times"></i> Limpar</a>
        </div>
    </form>
</div>

<?php if ($filtroPreenchido): ?>
<div class="card relatorio-resultado">
    <div class="relatorio-header">
        <h3>Resultado (<?php echo count($chamados); ?> chamado(s))</h3>
        <button type="button" class="btn btn-primary" onclick="window.print()" id="btnImprimir">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <div id="relatorio-conteudo">
        <?php if (empty($chamados)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Nenhum chamado encontrado</h3>
                <p>Ajuste os filtros e tente novamente.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table table-relatorio">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Empresa</th>
                            <th>Funcionário</th>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamados as $chamado): ?>
                            <tr>
                                <td>#<?php echo $chamado['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($chamado['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($chamado['empresa_nome'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($chamado['funcionario_nome'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($chamado['tipo_nome'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($chamado['titulo']); ?></td>
                                <td><span class="badge badge-<?php echo $chamado['prioridade'] === 'urgente' ? 'danger' : ($chamado['prioridade'] === 'alta' ? 'warning' : 'info'); ?>"><?php echo $prioridadeLabels[$chamado['prioridade']] ?? $chamado['prioridade']; ?></span></td>
                                <td><span class="badge badge-<?php echo $chamado['status'] === 'finalizado' ? 'success' : ($chamado['status'] === 'em_atendimento' ? 'primary' : 'secondary'); ?>"><?php echo $statusLabels[$chamado['status']] ?? $chamado['status']; ?></span></td>
                                <td><?php echo htmlspecialchars($chamado['tecnico_nome'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<div class="card relatorio-resultado">
    <div class="empty-state">
        <i class="fas fa-filter"></i>
        <h3>Preencha pelo menos um filtro</h3>
        <p>Selecione data, tipo de atendimento ou empresa para gerar o relatório. Os filtros podem ser usados em conjunto.</p>
    </div>
</div>
<?php endif; ?>

<!-- Área de impressão com layout próprio -->
<?php if ($filtroPreenchido): ?>
<div id="impressao-relatorio" class="impressao-only">
    <div class="impressao-header">
        <h1>Relatório de Chamados - Sistema T.I.</h1>
        <p>Emitido em: <?php echo date('d/m/Y \à\s H:i'); ?></p>
        <?php if (!empty($data_inicio) || !empty($data_fim) || $tipo_id > 0 || $empresa_id > 0): ?>
        <p class="impressao-filtros">
            Filtros: 
            <?php 
            $filtros = [];
            if (!empty($data_inicio)) $filtros[] = 'De ' . date('d/m/Y', strtotime($data_inicio));
            if (!empty($data_fim)) $filtros[] = 'até ' . date('d/m/Y', strtotime($data_fim));
            if ($tipo_id > 0) {
                $nomeTipo = '';
                foreach ($tipos_atendimento as $t) { if ((int)$t['id'] === $tipo_id) { $nomeTipo = $t['nome']; break; } }
                $filtros[] = 'Tipo: ' . $nomeTipo;
            }
            if ($empresa_id > 0) {
                $nomeEmp = '';
                foreach ($empresas as $e) { if ((int)$e['id'] === $empresa_id) { $nomeEmp = $e['nome']; break; } }
                $filtros[] = 'Empresa: ' . $nomeEmp;
            }
            echo implode(' | ', $filtros);
            ?>
        </p>
        <?php endif; ?>
    </div>
    <?php if (!empty($chamados)): ?>
    <table class="impressao-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Empresa</th>
                <th>Funcionário</th>
                <th>Tipo</th>
                <th>Título</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Técnico</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chamados as $chamado): ?>
                <tr>
                    <td>#<?php echo $chamado['id']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($chamado['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($chamado['empresa_nome'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($chamado['funcionario_nome'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($chamado['tipo_nome'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($chamado['titulo']); ?></td>
                    <td><?php echo $prioridadeLabels[$chamado['prioridade']] ?? $chamado['prioridade']; ?></td>
                    <td><?php echo $statusLabels[$chamado['status']] ?? $chamado['status']; ?></td>
                    <td><?php echo htmlspecialchars($chamado['tecnico_nome'] ?? '-'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="impressao-total">Total: <?php echo count($chamados); ?> chamado(s)</p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
