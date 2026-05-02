<?php
require_once 'config/config.php';
requireLogin();

$user = getCurrentUser();
$db = Database::getInstance()->getConnection();

// Dados para o dashboard
$stats = [];

if ($_SESSION['user_type'] === 'admin') {
    // Estatísticas para Admin
    $stmt = $db->query("SELECT COUNT(*) as total FROM chamados");
    $stats['total_chamados'] = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM chamados WHERE status = 'pendente' OR status = 'em_atendimento'");
    $stats['chamados_abertos'] = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM chamados WHERE status = 'finalizado'");
    $stats['chamados_finalizados'] = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM empresas WHERE ativo = 1");
    $stats['total_empresas'] = $stmt->fetch()['total'];
    
    // Chamados por mês (últimos 6 meses)
    $stmt = $db->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total 
        FROM chamados 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY mes 
        ORDER BY mes ASC
    ");
    $chamados_por_mes = $stmt->fetchAll();
    
    // Chamados por empresa
    $stmt = $db->query("
        SELECT e.nome, COUNT(c.id) as total 
        FROM empresas e 
        LEFT JOIN chamados c ON e.id = c.empresa_id 
        WHERE e.ativo = 1
        GROUP BY e.id 
        ORDER BY total DESC 
        LIMIT 10
    ");
    $chamados_por_empresa = $stmt->fetchAll();
    
} elseif ($_SESSION['user_type'] === 'tecnico') {
    // Estatísticas para Técnico
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE tecnico_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['meus_chamados'] = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM chamados WHERE status = 'pendente'");
    $stats['chamados_pendentes'] = $stmt->fetch()['total'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE tecnico_id = ? AND status = 'finalizado'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['chamados_finalizados'] = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM chamados WHERE status = 'em_atendimento'");
    $stats['em_atendimento'] = $stmt->fetch()['total'];
    
    // Chamados por mês
    $stmt = $db->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total 
        FROM chamados 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY mes 
        ORDER BY mes ASC
    ");
    $chamados_por_mes = $stmt->fetchAll();
    
    // Chamados por empresa
    $stmt = $db->query("
        SELECT e.nome, COUNT(c.id) as total 
        FROM empresas e 
        LEFT JOIN chamados c ON e.id = c.empresa_id 
        WHERE e.ativo = 1
        GROUP BY e.id 
        ORDER BY total DESC 
        LIMIT 10
    ");
    $chamados_por_empresa = $stmt->fetchAll();
    
} else {
    // Estatísticas para Funcionário
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE funcionario_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['meus_chamados'] = $stmt->fetch()['total'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE funcionario_id = ? AND (status = 'pendente' OR status = 'em_atendimento' OR status = 'reaberto')");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['chamados_abertos'] = $stmt->fetch()['total'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE funcionario_id = ? AND status = 'finalizado'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['chamados_finalizados'] = $stmt->fetch()['total'];
    
    // Chamados da empresa
    if ($_SESSION['user_empresa_id']) {
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM chamados WHERE empresa_id = ?");
        $stmt->execute([$_SESSION['user_empresa_id']]);
        $stats['chamados_empresa'] = $stmt->fetch()['total'];
    }
    
    // Chamados por mês
    $stmt = $db->prepare("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total 
        FROM chamados 
        WHERE funcionario_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY mes 
        ORDER BY mes ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $chamados_por_mes = $stmt->fetchAll();
}

include 'includes/header.php';
?>

<div class="dashboard">
    <h1>Dashboard</h1>
    
    <!-- Cards de Estatísticas -->
    <div class="stats-grid">
        <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_chamados']; ?></h3>
                    <p>Total de Chamados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_abertos']; ?></h3>
                    <p>Chamados Abertos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_finalizados']; ?></h3>
                    <p>Chamados Finalizados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-building"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_empresas']; ?></h3>
                    <p>Empresas Cadastradas</p>
                </div>
            </div>
        <?php elseif ($_SESSION['user_type'] === 'tecnico'): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['meus_chamados']; ?></h3>
                    <p>Meus Chamados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_pendentes']; ?></h3>
                    <p>Pendentes</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-cog"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['em_atendimento']; ?></h3>
                    <p>Em Atendimento</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_finalizados']; ?></h3>
                    <p>Finalizados</p>
                </div>
            </div>
        <?php else: ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['meus_chamados']; ?></h3>
                    <p>Meus Chamados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_abertos']; ?></h3>
                    <p>Chamados Abertos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_finalizados']; ?></h3>
                    <p>Finalizados</p>
                </div>
            </div>
            <?php if (isset($stats['chamados_empresa'])): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-building"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['chamados_empresa']; ?></h3>
                    <p>Chamados da Empresa</p>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Gráficos -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3>Chamados por Mês</h3>
            <canvas id="chartChamadosMes"></canvas>
        </div>
        
        <?php if ($_SESSION['user_type'] !== 'funcionario'): ?>
        <div class="chart-card">
            <h3>Chamados por Empresa</h3>
            <canvas id="chartChamadosEmpresa"></canvas>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dados para os gráficos
const chamadosPorMes = <?php echo json_encode($chamados_por_mes ?? []); ?>;
<?php if (isset($chamados_por_empresa)): ?>
const chamadosPorEmpresa = <?php echo json_encode($chamados_por_empresa ?? []); ?>;
<?php endif; ?>

// Gráfico de Chamados por Mês
const ctx1 = document.getElementById('chartChamadosMes').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: chamadosPorMes.map(item => {
            const [year, month] = item.mes.split('-');
            const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            return months[parseInt(month) - 1] + '/' + year;
        }),
        datasets: [{
            label: 'Chamados',
            data: chamadosPorMes.map(item => item.total),
            borderColor: '#0066cc',
            backgroundColor: 'rgba(0, 102, 204, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

<?php if (isset($chamados_por_empresa) && $_SESSION['user_type'] !== 'funcionario'): ?>
// Gráfico de Chamados por Empresa
const ctx2 = document.getElementById('chartChamadosEmpresa').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: chamadosPorEmpresa.map(item => item.nome),
        datasets: [{
            label: 'Chamados',
            data: chamadosPorEmpresa.map(item => item.total),
            backgroundColor: '#0066cc'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>

