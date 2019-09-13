class Alert extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            x: 1
        }
    }

    render(){
        return(
            <div className="alert alert-warning fade show" role="alert">
                <p className="msgAlert">Nenhum {this.props.tipo} encontrado!
                    <button className="close" onClick={fecharCompReact} aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </p>
            </div>
        );
    }
}

function exibirAlerta(quant, tipo) {
    if (quant == 0) {
        let elemento = (
            <Alert tipo={tipo}/>
        )

        ReactDOM.render(
            elemento,
            document.getElementById("compReact")
        )
    }
}

function fecharCompReact() {
    ReactDOM.unmountComponentAtNode(document.getElementById("compReact"));
}

function fecharCompR() {
    ReactDOM.unmountComponentAtNode(document.getElementById("compR"));
}


class TableHead extends React.Component {
    render() {
        return (
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Opção</th>
            </tr>
            </thead>
        );
    }
}

class BotaoSelecionar extends React.Component{
    render(){
        let elBtn;

        if (this.props.tipo === "fornecedor"){
            elBtn = (
                <button onClick={()=>{setFornecedor(this.props.nome)}} className="btn btn-success btn-sm">Selecionar</button>
            )
        }else if (this.props.tipo === "produtoAjuste"){
            elBtn = (
                <button onClick={()=>{setProdutoAjuste(this.props.id)}} className="btn btn-success btn-sm">Selecionar</button>
            )
        }else if (this.props.tipo === "produtoConvert"){
            elBtn = (
                <button onClick={()=>{setProdutoConvert(this.props.id, this.props.local)}} className="btn btn-success btn-sm">Selecionar</button>
            )
        }

        return elBtn;
    }

}

class TableLinha extends React.Component{
    render(){
        return(
          <tr>
              <td>{this.props.id}</td>
              <td>{this.props.nome}</td>
              <td> <BotaoSelecionar id={this.props.id} nome={this.props.nome} tipo={this.props.tipo} local={this.props.local}/></td>
          </tr>
        );
    }
}

class TableFornecedores extends React.Component{
    render(){
        let cont = 0;
        let lista =this.props.fornecedores.map((item)=>{
            return(
                <TableLinha key={item.id} id={item.id} nome={item.nome} tipo="fornecedor"/>
            );
        });

        return(
            <table className="table table-hover">
                <TableHead/>
                <tbody>
                {lista}
                </tbody>
            </table>
        );
    }
}

class ModalFornecedor extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            forn:[]
        }
    }

    componentDidMount(){
        this.state.forn = this.setState(this.props.fornecedores);
    }

    render(){
        return(
            <div>
              <div className="modal mostrar" id="janelaFornecedor">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5>Escolha um fornecedor</h5>
                        </div>
                        <div className="modal-body">
                            <TableFornecedores fornecedores={this.props.fornecedores}/>
                        </div>
                        <div className="modal-footer">
                            <button onClick={fecharCompReact} className="btn btn-secondary">Sair</button>
                        </div>
                    </div>
                </div>
              </div>
                <div className="modal-backdrop"></div>
            </div>
        );
    }
}

function exibirListaFornecedores(lista) {
    let elemento = (
        <ModalFornecedor fornecedores={lista}/>
    );

    ReactDOM.render(
        elemento,
        document.getElementById("compReact")
    );
}
function setFornecedor(nome) {
    var campoFornecedor = document.getElementById("fornecedor");

    campoFornecedor.value = nome;

    fecharCompReact();
}

//Pagina de Produtos

class LinhaProduto extends React.Component{
    render(){
        return(
          <tr>
              <td>{this.props.id}</td>
              <td>{this.props.nomeP}</td>
              <td>{this.props.barra}</td>
              <td>{this.props.nomeF}</td>
              <td>R${this.props.vlComp}</td>
              <td>R${this.props.vlVend}</td>
              <td><img src="/resourse/imagens/editar.png" alt="editarProduto" title="Editar" className="btnListUser" onClick={()=>{window.location='editarProduto.php?produto='+this.props.id}}/></td>
          </tr>
        );
    }
}

class TabelaBody extends React.Component{
    render(){
        let lista = this.props.lista.map((item)=>{
            return(
              <LinhaProduto
                  key={item.id_produto}
                  id={item.id_produto}
                  nomeP={item.nome_produto}
                  barra={item.barra}
                  nomeF={item.nome_fornecedor}
                  vlComp={item.vlCompra}
                  vlVend={item.vlVenda}
              />
            );
        });

        return(
          <tbody>
          {lista}
          </tbody>
        );
    }
}

class Paginacao extends React.Component{
    render(){
        let pagAnterior = this.props.pagina-1;
        let pagina = this.props.pagina;
        let pagSeguinte = this.props.pagina+1;
        let ultPagina = this.props.ultimaPagina;

        let proxP = "";
        if (this.props.ultimaPagina > 1 && this.props.pagina < this.props.ultimaPagina){
            proxP = (
                <li>
                    <a className="page-link" href="javascript:;" onClick={()=>{paginaProduto(pagSeguinte, ultPagina, 1)}}>{pagSeguinte}</a>
                </li>
            );
        }
        return(
          <ul className={"pagination"}>
              <li className="page-item">
                  <a className="page-link" href="javascript:;" onClick={()=>{paginaProduto(1, ultPagina.valueOf(), 1)}}>&laquo;</a>
              </li>
              {this.props.pagina>1?
                  <li className="page-item">
                      <a className="page-link" href="javascript:;" onClick={()=>{paginaProduto(pagAnterior, ultPagina, 1)}}>{pagAnterior}</a>
                  </li>:""
              }
              <li className="page-item active">
                  <a className="page-link" href="javascript:;" onClick={()=>{paginaProduto(pagina, ultPagina, 1)}}>{pagina}</a>
              </li>
              {proxP}
              <li className="page-item">
                  <a className="page-link" href="javascript:;"  onClick={()=>{paginaProduto(ultPagina, ultPagina, 1)}}>&raquo;</a>
              </li>
          </ul>
        );
    }
}

class TabelaHead extends React.Component{
    render(){
        return(
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cod Barra</th>
                    <th scope="col">Fornecedor</th>
                    <th scope="col">Vl Compra</th>
                    <th scope="col">Vl Venda</th>
                    <th scope="col">Opções</th>
                </tr>
            </thead>
        );
    }
}

class TabelaProduto extends  React.Component{
    render(){
        return(
          <table className="table table-hover" id="tabela">
              <TabelaHead/>
              <TabelaBody lista={this.props.lista}/>
          </table>
        );
    }
}

function mudarPaginaProduto(lista, pagina, ultimaPagina) {
    let elementoProdutos = (
        <TabelaProduto lista={lista}/>
    );

    ReactDOM.render(
      elementoProdutos,
      document.getElementById("proximaTable")
    );

    let elementoPagi = (
        <Paginacao pagina={pagina} ultimaPagina={ultimaPagina}/>
    );

    ReactDOM.render(
      elementoPagi,
      document.getElementById("pagi2")
    );
}

function removerComponentesPagination() {
    ReactDOM.unmountComponentAtNode(document.getElementById("tabela"));
    ReactDOM.unmountComponentAtNode(document.getElementById("pagi2"));
}

function ajustePrecoR(produto) {
    let elAjusteProduto = (
      <AjusteProduto produto={produto}/>
    );

    ReactDOM.render(
        elAjusteProduto,
        document.getElementById("compR")
    )
}

class AjusteProduto extends React.Component{
    constructor(props){
        super(props);
        this.state={
            vlComp: this.props.produto[0].valor_compra,
            vlVen: this.props.produto[0].valor_venda
        };
        this.trocarVlComp = this.trocarVlComp.bind(this);
        this.trocarvlVen = this.trocarvlVen.bind(this);
    }

    trocarVlComp(e){
       let novoValor = e.target.value;

       this.setState({vlComp:novoValor});
    }

    trocarvlVen(e){
        let novoValor = e.target.value;

        this.setState({vlVen:novoValor});
    }

    render(){
        return(
          <div className="form-group row grupoAjuste">
              <div className="col-md-5">
                  <label className="col-form-label" htmlFor="vlComp">Valor de Compra</label>
                  <div className="input-group-prepend">
                      <span className="input-group-text">R$</span>
                      <input type="text" className="form-control" id="vlComp" name="vlComp" value={this.state.vlComp} onChange={this.trocarVlComp}/>
                  </div>
              </div>
              <div className="col-md-5 elementoR">
                  <label className="col-form-label" htmlFor="vlVen">Valor de Venda</label>
                  <div className="input-group-prepend">
                      <span className="input-group-text">R$</span>
                      <input type="text" className="form-control" id="vlVen" name="vlVen" value={this.state.vlVen} onChange={this.trocarvlVen}/>
                  </div>
              </div>
          </div>
        );
    }
}

function exibirListaProdutos(produtos, tela, local) {
    let elLista = (
        <ModalProduto produtos={produtos} tela={tela} local={local}/>
    );

    ReactDOM.render(
        elLista,
        document.getElementById("compReact")
    )
}

class ModalProduto extends React.Component{
    render(){
        return(
            <div>
                <div className="modal mostrar" id="janelaProduto">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Escolha um Produto</h5>
                            </div>
                            <div className="modal-body">
                                <TProdutoLista produtos={this.props.produtos} tela={this.props.tela} local={this.props.local}/>
                            </div>
                            <div className="modal-footer">
                                <button onClick={fecharCompReact} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        );
    }
}

class TProdutoLista extends React.Component{
    render(){
        let lista =this.props.produtos.map((item)=>{
            return(
                <TableLinha key={item.id} id={item.id} nome={item.nome} tipo={this.props.tela} local={this.props.local}/>
            );
        });

        return(
            <table className="table table-hover">
                <TableHead/>
                <tbody>
                {lista}
                </tbody>
            </table>
        );
    }
}

function setProdutoAjuste(id) {
    fecharCompReact();
    ajustePreco(id);
}

function setProdutoConvert(id, local) {
    fecharCompReact();
    pesquisaProdutoConvert(id, local);
}
