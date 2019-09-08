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
                <p className="msgAlert">Nenhum fornecedor encontrado!
                    <button className="close" onClick={fecharAlerta} aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </p>
            </div>
        );
    }
}

function exibirAlerta(quant) {
    if (quant == 0) {
        let elemento = (
            <Alert/>
        )

        ReactDOM.render(
            elemento,
            document.getElementById("compReact")
        )
    }
}

function fecharAlerta() {
    ReactDOM.unmountComponentAtNode(document.getElementById("compReact"));
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

class TableLinha extends React.Component{
    render(){
        return(
          <tr>
              <td>{this.props.id}</td>
              <td>{this.props.nome}</td>
              <td> <button onClick={()=>{setFornecedor(this.props.nome)}} className="btn btn-success btn-sm">Selecionar</button></td>
          </tr>
        );
    }
}

class TableFornecedores extends React.Component{
    render(){
        let cont = 0;
        let lista =this.props.fornecedores.map((item)=>{
            return(
                <TableLinha key={item.id} id={item.id} nome={item.nome}/>
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
                            <button onClick={fecharAlerta} className="btn btn-secondary">Sair</button>
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

    fecharAlerta();
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
              <td>{this.props.vlComp}</td>
              <td>{this.props.vlVend}</td>
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
                  <a className="page-link" href="javascript:;" onClick={()=>{paginaProduto(pagina, pagina, 1)}}>{pagina}</a>
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