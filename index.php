<?php
include_once('./connector.php');
include_once('./Navbar.php');
session_start();

$popula_tabela = $conexao_pdo->prepare("SELECT * FROM LICITANET_COMPANY");
$popula_tabela->execute();
$popula_tabela_result = $popula_tabela->rowCount();

// FUNÇÃO DE DELETAR EMPRESA
if (isset($_POST['delete'])) {
    $id_deletar = $_POST['deletar'];
    $deletar_company = $conexao_pdo->prepare("DELETE FROM LICITANET_COMPANY where id=" . $id_deletar . ";");
    $deletar_company->execute();
    header("Refresh:0");
}
// FUNÇÃO DE CRIAR EMPRESA
if (isset($_POST['fantasy_name']) && isset($_POST['uf']) && isset($_POST['cnpj'])) {
    $deletar_company = $conexao_pdo->prepare('INSERT INTO LICITANET_COMPANY (uf,fantasy_name,cnpj) values ("' . $_POST['uf'] . '","' . $_POST['fantasy_name'] . '","' . $_POST['cnpj'] . '");');
    $deletar_company->execute();
    header("Refresh:0");
}

// FUNÇÃO DE LISTAGEM DE PROVIDERS
if (isset($_POST['providers'])) {
    $buscar_empresa = $conexao_pdo->prepare('SELECT * FROM LICITANET_COMPANY WHERE id=' . $_POST['providers'] . ';');
    $buscar_empresa->execute();

    while ($fetch = $buscar_empresa->fetch()) {
        $_SESSION['id_company'] = $_POST['providers'];
        $_SESSION['uf_company'] = $fetch['uf'];
    }
    header('Location: provider.php');
}


?>

<div style="margin:50px;align-items:center;">
    <h1><span class="iconify" data-icon="carbon:location-company-filled" data-inline="false"></span>Empresas</h1>

    <div style="display: flex; justify-content: flex-end">
        <form>
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                        Criar empresa
                    </button>
                </div>
            </div>
        </form>
    </div>
    <table class="m-4 table table-sm table-dark mt-4">
        <thead>
            <tr>
                <th scope="col">UF</th>
                <th scope="col">Nome Fantasia</th>
                <th scope="col">CNPJ</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($fetch = $popula_tabela->fetch()) {
                echo '<tr>
                    <th scope="row">' . $fetch['uf'] . '</th>
                    <td>' . $fetch['fantasy_name'] . '</td>
                    <td>' . $fetch['cnpj'] . '</td>
                    <td>
                    <form method="POST">
                    <button name="delete" value=' . $fetch['id'] . ' class="btn btn-danger mb-2">Deletar</button>
                    <button name="providers" value=' . $fetch['id'] . ' class="btn mb-2">Ver Fornecedores</button></td>
                    </form>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Criação de Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Nome fantasia</label>
                        <input name="fantasy_name" type="text" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">UF</label>
                        <input name="uf" type="text" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">CNPJ</label>
                        <input name="cnpj" type="text" class="form-control" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar usuário</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
include_once('./Footer.php')
?>