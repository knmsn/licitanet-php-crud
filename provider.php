<?php
include_once('./requeriments/connector.php');
include_once('./requeriments/Navbar.php');
session_start();

$popula_tabela = $conexao_pdo->prepare("SELECT * FROM LICITANET_PROVIDER where id_company=" . $_SESSION['id_company'] . ";");
$popula_tabela->execute();
$popula_tabela_result = $popula_tabela->rowCount();

if (isset($_POST['search'])) {
    $busca = $_POST['search']; 
    $popula_tabela = $conexao_pdo->prepare("SELECT * FROM LICITANET_PROVIDER where id_company=" . $_SESSION['id_company'] . " AND name LIKE '$busca' OR cpf_cnpj LIKE '$busca';");
    $popula_tabela->execute();
    $popula_tabela_result = $popula_tabela->rowCount();
}

// FUNÇÃO DE DELETAR FORNECEDOR
if (isset($_POST['delete'])) {
    $id_deletar = $_POST['delete'];
    $deletar_provider = $conexao_pdo->prepare("DELETE FROM LICITANET_PROVIDER where id='" . $id_deletar . "';");
    $deletar_provider->execute();
    header("Refresh:0");
}

// FUNÇÃO DE CRIAR FORNECEDOR
if (isset($_POST['name']) && isset($_POST['cpf']) && isset($_POST['rg']) && isset($_POST['idade']) && isset($_POST['birthdate']) && isset($_POST['telephone'])) {

    if (($_SESSION['uf_company'] = 'PA' && $_POST['idade'] <= 17) || (isset($_POST['pessoa_fisica']) && (!$_POST['rg'] || !$_POST['birthdate']))) {
        echo '<script>alert("Não foi possivel criar o fornecedor!");</script>';
    } else {
        if (!$_POST['rg']) {
            $rg = 'null';
        } else {
            $rg = $_POST['rg'];
        }
        if (!$_POST['birthdate']) {
            $birthdate = 'null';
        } else {
            $birthdate = $_POST['birthdate'];
        }

        $id_company = $_SESSION['id_company'];
        $name = $_POST['name'];
        $cpf = $_POST['cpf'];
        $idade = $_POST['idade'];
        $telephone = $_POST['telephone'];

        $criar_fornecedor = $conexao_pdo->prepare("INSERT INTO LICITANET_PROVIDER (id_company,name,cpf_cnpj,rg,idade,birthdate,telephone)
        values(
        $id_company,
        '$name',
        '$cpf',
        '$rg',
        '$idade',
        '$birthdate',
        '$telephone'
        );
        ");
        $criar_fornecedor->execute();
        header("Refresh:0");
    }
}

?>

<div style="margin:50px;align-items:center;">
    <h1><span class="iconify" data-icon="map:moving-company" data-inline="false"></span> Fornecedores </h1>

    <div style="display: flex; justify-content: flex-end">
        <form class="form-inline" method="POST">
            <div class="form-group mb-2">
                <label for="staticEmail2" class="sr-only">Email</label>
                <input type="text" name="search" readonly class="form-control-plaintext" id="staticEmail2" value="">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="inputPassword2" class="sr-only">Password</label>
                <input type="text" name="search" class="form-control" id="inputPassword2" placeholder="Busca nome/cpf">
            </div>
            <button type="submit" class="btn btn-primary mb-2 mr-2">Buscar</button>  <button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-success mb-2">Criar Fornecedor</button>
        </form>

    </div>
    <table class="m-4 table table-sm table-dark mt-4">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">CPF/CNPJ</th>
                <th scope="col">RG</th>
                <th scope="col">Telefone</th>
                <th scope="col">Idade</th>
                <th scope="col">Data de nascimento</th>
                <th scope="col">Data/hora</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($fetch = $popula_tabela->fetch()) {
                echo '<tr>
                    <th scope="row">' . $fetch['name'] . '</th>
                    <td>' . $fetch['cpf_cnpj'] . '</td>
                    <td>' . $fetch['rg'] . '</td>
                    <td>' . $fetch['idade'] . '</td>
                    <td>' . $fetch['telephone'] . '</td>
                    <td>' . $fetch['birthdate'] . '</td>
                    <td>' . $fetch['created_at'] . '</td>
                    <td>
                    <form method="POST">
                    <button name="delete" value=' . $fetch['id'] . ' class="btn btn-danger mb-2">Deletar</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Criação de Fornecedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Nome</label>
                        <input name="name" type="text" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">CPF/CNPJ</label>
                        <input name="cpf" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">RG</label>
                        <input name="rg" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Idade</label>
                        <input name="idade" type="number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Data de nascimento</label>
                        <input name="birthdate" type="date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Telefone</label>
                        <input name="telephone" type="text" class="form-control" required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="pessoa_fisica" class="form-check-input">
                        <label class="form-check-label">Pessoa Fisica</label>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar fornecedor</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
include_once('./requeriments/Footer.php')
?>