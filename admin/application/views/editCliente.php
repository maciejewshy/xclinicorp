<!-- editCliente.php -->
<div class="container">
    <h2>Editar Cliente</h2>
    <form action="<?= base_url('clientes/updateClienteData') ?>" method="post">
        <input type="hidden" name="id_cliente" value="<?= $cliente[0]->id_cliente ?>">

        <div class="form-group">
            <label>Nome do Cliente</label>
            <input type="text" name="cliente" class="form-control" value="<?= $cliente[0]->cliente ?>" required>
        </div>
        <div class="form-group">
            <label>CNPJ</label>
            <input type="text" name="cnpj" class="form-control" value="<?= $cliente[0]->cnpj ?>">
        </div>
        <div class="form-group">
            <label>Host (API)</label>
            <input type="text" name="host" class="form-control" value="<?= $cliente[0]->host ?>">
        </div>
        <div class="form-group">
            <label>Token</label>
            <input type="text" name="token" class="form-control" value="<?= $cliente[0]->token ?>">
        </div>
        <div class="form-group">
            <label>Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="<?= $cliente[0]->matricula ?>">
        </div>
        <div class="form-group">
            <label>ID Assinante</label>
            <input type="text" name="idAssinante" class="form-control" value="<?= $cliente[0]->idAssinante ?>">
        </div>
        <div class="form-group">
            <label>Quantidade de Canais</label>
            <input type="number" name="canais" class="form-control" value="<?= $cliente[0]->canais ?>">
        </div>
        <div class="form-group">
            <label>Nova Senha (deixe em branco para não alterar)</label>
            <input type="password" name="Senha" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="<?= base_url('clientes') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>