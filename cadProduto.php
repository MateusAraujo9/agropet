<?php
    require "DAO/conection.php";

    //Pegar unidade
    $sqlUnidades = "SELECT * FROM unidade";

    try{
        $sqlUnidades = $pdo->query($sqlUnidades);

        $sqlUnidades = $sqlUnidades->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar grupo
    $sqlGrupo = "SELECT * FROM grupo";

    try{
        $sqlGrupo = $pdo->query($sqlGrupo);

        $sqlGrupo = $sqlGrupo->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar Classe
    $sqlClasse = "SELECT * FROM classe";

    try{
        $sqlClasse = $pdo->query($sqlClasse);

        $sqlClasse = $sqlClasse->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }

    //Pegar Subclasse
    $sqlSubClasse = "SELECT * FROM subclasse";

    try{
        $sqlSubClasse = $pdo->query($sqlSubClasse);

        $sqlSubClasse = $sqlSubClasse->fetchAll();
    }catch (PDOException $e){
        echo "Erro: ".$e->getMessage();
    }
?>
<div class="containerInt" id="containerInt">
    <div class="containerCenter2">
        <!--Alert inicio -->
        <div id="compReact"></div>
        <!--Alert fim-->
        <h3 class="titulo">Novo Produto</h3>
        <form action="DAO/cadProduto.php" method="post">
            <div class="form-group">
                <label class="col-form-label" for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome">
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label" for="barra">Cod Barra</label>
                    <input type="text" class="form-control" id="barra" name="barra">
                </div>
                <div class="col-md-3">
                    <label class="col-form-label" for="qtd">Quantidade</label>
                    <input type="text" class="form-control" id="qtd" name="qtd">
                </div>
                <div class="col-md-2">
                    <label class="col-form-label" for="unidade">Unidade</label>
                    <select class="form-control" id="unidade" name="unidade">
                        <option value="0" selected></option>
                        <?php
                            foreach ($sqlUnidades as $un){
                                echo "<option value=\"".$un['id']."\">".$un['nome']." - ".$un['sigla']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label" for="grupo">Grupo</label>
                    <select class="form-control" id="grupo" name="grupo">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlGrupo as $gr){
                            echo "<option value=\"".$gr['id']."\">".$gr['nome']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label" for="classe">Classe</label>
                    <select class="form-control" id="classe" name="classe">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlClasse as $cl){
                            echo "<option value=\"".$cl['id']."\">".$cl['nome']."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label" for="subCl">Subclasse</label>
                    <select class="form-control" id="subCl" name="subCl">
                        <option value="0" selected></option>
                        <?php
                        foreach ($sqlSubClasse as $sb){
                            echo "<option value=\"".$sb['id']."\">".$sb['nome']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="col-form-label" for="fornecedor">Fornecedor</label>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="fornecedor" name="fornecedor">
                            <div class="input-group-append">
                                <span class="input-group-text button" onclick="pesquisaFornecedor()"><img src="resourse/imagens/lupa.png" alt="lupa" title="Pesquisar"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label" for="vlComp">Valor de Compra</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control" id="vlComp" name="vlComp">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label" for="vlVen">Valor de Venda</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control" id="vlVen" name="vlVen">
                    </div>

                </div>
            </div>
            <input type="submit" value="Cadastrar" class="btn btn-primary btn-lg btn-block btnCadastro">
        </form>
    </div>
</div>


