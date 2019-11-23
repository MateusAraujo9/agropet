class Alert extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            x: 1
        }
    }

    render(){
        let elBtn = "";
        if(this.props.tipo === "Cliente"){
            elBtn = (
                <button className="close" onClick={fecharPesquisaReact} aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            )
        }else{
            elBtn = (
                <button className="close" onClick={fecharCompReact} aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            )
        }
        return(
            <div className="alert alert-warning fade show" role="alert">
                <p className="msgAlert">Nenhum {this.props.tipo} encontrado!
                    {elBtn}
                </p>
            </div>
        );
    }
}

function exibirAlerta(quant, tipo) {
    if (quant === 0) {
        let elemento = (
            <Alert tipo={tipo}/>
        );

        if (tipo === "Cliente"){
            ReactDOM.render(
                elemento,
                document.getElementById("pesquisaReact")
            )
        }else{
            ReactDOM.render(
                elemento,
                document.getElementById("compReact")
            )
        }
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
        }else if(this.props.tipo === "produtoEntrada"){
            elBtn = (
                <button onClick={()=>{setProdutoEntrada(this.props.id)}} className="btn btn-success btn-sm">Selecionar</button>
            )
        }else if (this.props.tipo === "produtoCaixa"){
            elBtn = (
                <button onClick={()=>{setProdutoCaixa(this.props.id)}} className="btn btn-success btn-sm">Selecionar</button>
            )
        }else if (this.props.tipo === "cliente") {
            elBtn = (
                <button onClick={()=>{setClienteVenda(this.props.id)}} className="btn btn-success btn-sm">Selecionar</button>
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

class ListaProdutoEntrada extends React.Component{
     render(){
        let lista = this.props.listaP.map((item)=>{
            return(
              <LinhaProdutoEntrada
                  key={item.idProduto}
                  id={item.idProduto}
                  nome={item.nome}
                  custoU={item.custoU}
                  custoT={item.custoT}
                  quantidade={item.quantidade}
              />
            );
        });

        return (
            <table className="table table-hover">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Custo Unitário</th>
                    <th scope="col">Custo Total</th>
                    <th scope="col">Quantidade</th>
                </tr>
                </thead>
                <tbody>
                {lista}
                </tbody>
            </table>
            );
    }

}

class LinhaProdutoEntrada extends React.Component{
    render(){
        return(
            <tr>
                <td>{this.props.id}</td>
                <td>{this.props.nome}</td>
                <td>{this.props.custoU}</td>
                <td>{this.props.custoT}</td>
                <td>{this.props.quantidade}</td>
            </tr>
        );
    }
}

function TabelaProdutosEntrada(listaProdutos) {
    let elLista = (
        <ListaProdutoEntrada listaP={listaProdutos}/>
    );

    ReactDOM.render(
        elLista,
        document.getElementById("compR")
    )
}

class ListaProdutoCaixa extends React.Component{
    render(){
        return(
            <div>
                <div className="modal mostrar tamanhoModal" id="janelaProduto">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Escolha um Produto</h5>
                            </div>
                            <div className="modal-body scroll">
                                <TProdutoCaixa produtos={this.props.lista}/>
                            </div>
                            <div className="modal-footer">
                                <button onClick={fecharCompReact} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        )
    }
}

class TProdutoCaixa extends React.Component{
    render(){
        let lista =this.props.produtos.map((item)=>{
            return(
                <TLinhaCaixa key={item.id} id={item.id} nome={item.nome} vlVen={item.valor_venda}/>
            );
        });

        return(
            <table className="table table-hover">
                <THeadCaixa/>
                <tbody>
                {lista}
                </tbody>
            </table>
        );
    }
}

class TLinhaCaixa extends React.Component{
    render(){
        return(
            <tr>
                <td>{this.props.id}</td>
                <td>{this.props.nome}</td>
                <td>{this.props.vlVen}</td>
                <td> <BotaoSelecionar id={this.props.id}  tipo="produtoCaixa"/></td>
            </tr>
        );
    }
}

class THeadCaixa extends React.Component {
    render() {
        return (
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Vl. Venda</th>
                <th scope="col">Opção</th>
            </tr>
            </thead>
        );
    }
}

function listaProdutosCaixa(lista) {
    let elLista = (
      <ListaProdutoCaixa lista={lista}/>
    );

    ReactDOM.render(
        elLista,
        document.getElementById("compReact")
    )
}

//Lista caixa cupom

class TabelaProdutoCaixa extends React.Component{
    render(){
        let lista = this.props.listaP.map((item)=>{
            return(
                <LinhaProdutoCaixa
                    key={item.id}
                    nome={item.nome}
                    quant={item.quantidade}
                    desc={item.desconto}
                    vlLiquido={item.vlLiquido}
                    vlTotal={item.vlTotal}
                />
            );
        });

        return (
            <table className="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Vl.Unitario</th>
                    <th scope="col">Desconto</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>
                <tbody>
                {lista}
                </tbody>
            </table>
        );
    }
}

class LinhaProdutoCaixa extends React.Component{
    render(){
        return(
            <tr>
                <td>{this.props.nome}</td>
                <td>{this.props.quant}</td>
                <td>{this.props.vlLiquido}</td>
                <td>{this.props.desc}</td>
                <td>{this.props.vlTotal}</td>
            </tr>
        );
    }
}

function atualizarListaCaixa(lista) {
    let elLista = (
        <TabelaProdutoCaixa listaP={lista}/>
    );

    ReactDOM.render(
        elLista,
        document.getElementById("componentR")
    )
}

class ModalAbreCaixa extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            vlAbre: 0,
            vlMoeda: 0,
        }

        this.valorTrocou = this.valorTrocou.bind(this);
        this.abrirCaixa = this.abrirCaixa.bind(this);
        this.valorMoeda = this.valorMoeda.bind(this);
    }

    valorTrocou(e){
        let novoValor = e.target.value.replace(".", "").replace(",", ".");
        this.setState({vlAbre:novoValor});
    }

    valorMoeda(e){
        let novoValor = e.target.value.replace(".", "").replace(",", ".");
        this.setState({vlMoeda:novoValor});
    }

    abrirCaixa(){
        abrirCaixaUser(this.state.vlAbre, this.state.vlMoeda);
        fecharCompReact();
    }

    render(){
        return(
            <div>
                <div className="modal mostrar" id="janelaProduto">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Abrir Caixa</h5>
                            </div>
                            <div className="modal-body">
                                <div className="form-group">
                                    <label className="col-form-label" htmlFor="vlAbre">Valor Abertura (Cédula)</label>
                                    <input type="text" className="form-control valor" id="vlAbre" onKeyUp={this.valorTrocou} maxLength="8"/>
                                </div>
                                <div className="form-group">
                                    <label className="col-form-label" htmlFor="vlMoeda">Valor Abertura (Moeda)</label>
                                    <input type="text" className="form-control valor" id="vlMoeda" onKeyUp={this.valorMoeda} maxLength="8"/>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button onClick={this.abrirCaixa} className="btn btn-success">Confirmar</button>
                                <button onClick={fecharCompReact} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        )
    }
}

function abreCaixa() {
    let elAbreCaixa = (
        <ModalAbreCaixa/>
    );

    ReactDOM.render(
        elAbreCaixa,
        document.getElementById("compReact")
    )
}

function removerComponentR() {
    ReactDOM.unmountComponentAtNode(document.getElementById("componentR"));
}

function removerComponentesCaixa() {
    ReactDOM.unmountComponentAtNode(document.getElementById("footer"));
    limparFooterRodapeCaixa();
    $('#selectCaixa')[0].value = "";
}

function preencheFooterCaixa(caixa) {
    let elFooterCaixa=(
      <p id="pFooterCaixa">CAIXA ABERTO - Nº Caixa: {caixa.id} - Usuario: {caixa.nome}</p>
    );

    ReactDOM.render(
        elFooterCaixa,
        document.getElementById("footer")
    )
}

class ModalFinalizaVenda extends React.Component{
    constructor(props){
        super(props);
        this.state ={
            total: this.props.subtotal,
            troco: 0
        }
        this.ajuste = this.ajuste.bind(this);
        this.troco = this.troco.bind(this);
        this.habilitaData = this.habilitaData.bind(this);
    }

    ajuste(nr, casas) {
        const og = Math.pow(10, casas)
        return Math.floor(nr * og) / og;
    }

    troco(e){
        let novoValor = e.target.value.replace(".", "").replace(",", ".");
        novoValor = this.ajuste((novoValor - this.ajuste(this.props.subtotal, 2)), 2);
        this.setState({troco:novoValor});
    }

    habilitaData(){
        if (document.getElementById("crediario").checked === true) {
            document.getElementById("vencimento").disabled = false;

            let today = new Date();
            today.setDate(today.getDate() + 30);
            let dia = today.getDate().toString();
            dia = (dia.length === 1) ? "0"+dia:dia;
            let mes = today.getMonth() + 1;
            mes = (mes.length === 1) ? "0"+mes:mes;
            let ano = today.getFullYear();
            let dataAtual = ano+"-"+mes+"-"+dia;
            console.log(dataAtual);

            document.getElementById("vencimento").value = dataAtual;
        }else{
            document.getElementById("vencimento").disabled = true;
        }
    }

    render(){
        let total = parseFloat(this.props.subtotal);
        return(
            <div>
                <div className="modal mostrar" id="janelaProduto">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Finalizar Venda</h5>
                            </div>
                            <div id="pesquisaReact"></div>
                            <div>
                                <div className="modal-body">
                                    <div className="form-group">
                                        <label className="col-form-label" htmlFor="cliente">Cliente</label>
                                        <div className="form-group">
                                            <div className="input-group mb-3">
                                                <input type="text" className="form-control" id="cliente"
                                                       name="cliente"/>
                                                    <div className="input-group-append">
                                                        <span className="input-group-text button"
                                                              onClick={()=>{pesquisaCliente(0);}}><img src="resourse/imagens/lupa.png" alt="lupa" title="Pesquisar" />
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span>Forma de Pagamento</span>
                                    <div className="form-group especieLinha">
                                        <div className="custom-control custom-radio especieItem">
                                            <input type="radio" id="dinheiro" name="especie"
                                                   className="custom-control-input" value="dinheiro" onClick={this.habilitaData}/>
                                                <label className="custom-control-label" htmlFor="dinheiro">Dinheiro</label>
                                        </div>
                                        <div className="custom-control custom-radio especieItem">
                                            <input type="radio" id="debito" name="especie"
                                                   className="custom-control-input" value="debito" onClick={this.habilitaData}/>
                                                <label className="custom-control-label" htmlFor="debito">Débito</label>
                                        </div>
                                        <div className="custom-control custom-radio especieItem">
                                            <input type="radio" id="credito" name="especie"
                                                   className="custom-control-input" value="credito" onClick={this.habilitaData}/>
                                            <label className="custom-control-label" htmlFor="credito">Crédito</label>
                                        </div>
                                        <div className="custom-control custom-radio especieItem">
                                            <input type="radio" id="crediario" name="especie"
                                                   className="custom-control-input" value="crediario" onClick={this.habilitaData}/>
                                                <label className="custom-control-label" htmlFor="crediario">Crediário</label>
                                        </div>
                                    </div>
                                    <div className="form-group col-md-6">
                                        <label className="col-form-label" htmlFor="vencimento">Vencimento</label>
                                        <input type="date" className="form-control" id="vencimento" disabled/>
                                    </div>
                                    <div className="form-group camposCaixa">
                                        <label className="col-form-label col-form-label-lg"
                                               htmlFor="subtotal">Subtotal</label>
                                        <input className="form-control form-control-lg" type="text"
                                               id="subtotal" name="subtotal" readOnly value={this.ajuste(total, 2)}/>
                                    </div>
                                    <div className="especieLinha">
                                        <div className="form-group">
                                            <label htmlFor="vlPago" className="col-form-label">Dinheiro</label>
                                            <input type="text" className="form-control valor" id="vlPago" onKeyUp={this.troco}/>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="troco" className="col-form-label">Troco</label>
                                            <input type="text" className="form-control valor" id="troco" readOnly value={this.state.troco}/>
                                        </div>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button onClick={()=>{finalizarVenda("V")}} className="btn btn-success">Confirmar</button>
                                    <button onClick={fecharCompReact} className="btn btn-secondary">Sair</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        );
    }
}

function abrirModalFinalizarVenda(lista, tipo, total) {
    let elModalFinalizaVenda = (
        <ModalFinalizaVenda lista={lista} tipo={tipo} subtotal={total}/>
    );
    if (listaPCaixa.length >0){
        ReactDOM.render(
            elModalFinalizaVenda,
            document.getElementById("compReact")
        )
    }
}

class TableCliente extends React.Component{
    render(){
        let lista =this.props.clientes.map((item)=>{
            return(
                <TableLinha key={item.id} id={item.id} nome={item.nome} tipo="cliente"/>
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

class ModalClientes extends React.Component{
    render(){
        return(
            <div>
                <div className="modal mostrar tamanhoModal" id="janelaFornecedor">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Escolha um Cliente</h5>
                            </div>
                            <div className="modal-body scroll">
                                <TableCliente clientes={this.props.clientes}/>
                            </div>
                            <div className="modal-footer">
                                <button onClick={fecharPesquisaReact} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        );
    }
}

function exibirListaClientes(lista) {
    let elemento = (
        <ModalClientes clientes={lista}/>
    );

    ReactDOM.render(
        elemento,
        document.getElementById("pesquisaReact")
    );
}

function fecharPesquisaReact() {
    ReactDOM.unmountComponentAtNode(document.getElementById("pesquisaReact"));
}

class AlertModal extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            x: 1
        }
    }

    render(){
        let elBtn = (
            <button className="close" onClick={fecharPesquisaReact} aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        );
        return(
            <div className="alert alert-warning fade show" role="alert">
                <p className="msgAlert">{this.props.msg}
                    {elBtn}
                </p>
            </div>
        );
    }
}

function exibirAlertaModal(tipo, mensagem) {
    let elemento = (
        <AlertModal tipo={tipo} msg={mensagem}/>
    );

    ReactDOM.render(
        elemento,
        document.getElementById("pesquisaReact")
    )
}

class ModalCrediario extends React.Component{
    constructor(props){
        super(props);

        this.formataValor = this.formataValor.bind(this);
    }

    formataValor(valor){
        let x = parseFloat(valor).toFixed(2).replace('.', ',');
        return x;
    }

    render(){
        let valorFormatado = this.formataValor(this.props.lista.valor_a_pagar);

        return(
            <div>
                <div className="modal mostrar tamanhoModal" id="janelaFornecedor">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Recebimento</h5>
                            </div>
                            <div className="modal-body scroll">
                                <div id="pesquisaReact"></div>
                                <div className="form-group">
                                    <label htmlFor="cliente" className="col-form-label">Cliente</label>
                                    <input type="text" className="form-control" id="cliente" readOnly value={this.props.lista.nome}/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="vlComprado" className="col-form-label">Valor da Conta</label>
                                    <input type="text" className="form-control" id="vlComprado" readOnly value={valorFormatado}/>
                                </div>
                                <span>Forma de Pagamento</span>
                                <div className="form-group especieLinha">
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="dinheiro" name="especie"
                                               className="custom-control-input" value="dinheiro"/>
                                        <label className="custom-control-label" htmlFor="dinheiro">Dinheiro</label>
                                    </div>
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="debito" name="especie"
                                               className="custom-control-input" value="debito"/>
                                        <label className="custom-control-label" htmlFor="debito">Débito</label>
                                    </div>
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="credito" name="especie"
                                               className="custom-control-input" value="credito"/>
                                        <label className="custom-control-label" htmlFor="credito">Crédito</label>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button onClick={()=>{baixarCrediario(this.props.lista.id_cliente, this.props.lista.id_crediario)}} className="btn btn-success">Pagar Conta</button>
                                <button onClick={fecharCompR} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        );
    }
}

function exibirModalCrediario(lista) {
   let elemento = (
        <ModalCrediario lista={lista}/>
    )

    ReactDOM.render(
        elemento,
        document.getElementById("compR")
    )
}

class LinhaGen extends React.Component{
    render(){
        let lista;
        let chave = Object.keys(this.props.header);
        let cont = 0;
        lista = chave.map((keyC)=>{
            cont++;
            if (keyC === 'id'){
                return null;
            } else{
                return (
                    <td key={cont}>{this.props.item[keyC]}</td>
                )
            }
        });
        return (
            <tr>
                {lista}
                <td><img src="resourse/imagens/list-item.png" alt="Ver Itens" title="Ver Itens" className="btnListUser" onClick={()=>{listarProdutosCrediario(this.props.item['id'])}}/></td>
            </tr>
        );
    }
}

class TbBody extends React.Component{
    render(){
        let cont = 0;
        let lista = this.props.lista.map((item)=>{
            cont++;
            return(
                <LinhaGen
                    key={cont}
                    item={item}
                    header={this.props.header}
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

class TbHeader extends React.Component{
    render(){
        let lista;
        let chave = Object.keys(this.props.header);
        let cont = 0;
        lista = chave.map((item)=>{
                cont++;
                if (item === "id") {
                    return null;
                }else{
                    return (
                        <th key={cont} scope="col">{this.props.header[item]}</th>
                    )
                }
            }
        );
        return(
            <thead>
                <tr>
                    {lista}
                    <th scope="col">Opção</th>
                </tr>
            </thead>
        )
    }
}

class TabelaGen extends React.Component{
    render(){
        return(
            <table className="table table-hover" id="tabela">
                <TbHeader header={this.props.header}/>
                <TbBody header={this.props.header} lista={this.props.lista}/>
            </table>
        )
    }
}

class PaginacaoCrediario extends React.Component{
    render(){
        let pagAnterior = this.props.pagina-1;
        let pagina = this.props.pagina;
        let pagSeguinte = this.props.pagina+1;
        let ultPagina = this.props.ultPagina;

        let proxP = "";
        if (this.props.ultPagina > 1 && this.props.pagina < this.props.ultPagina){
            proxP = (
                <li>
                    <a className="page-link" href="javascript:;" onClick={()=>{consultaCrediarioDao(this.props.tipo, this.props.dtIni, this.props.dtFim, this.props.cliente, pagSeguinte)}}>{pagSeguinte}</a>
                </li>
            );
        }
        return(
            <ul className={"pagination"}>
                <li className="page-item">
                    <a className="page-link" href="javascript:;" onClick={()=>{consultaCrediarioDao(this.props.tipo, this.props.dtIni, this.props.dtFim, this.props.cliente, 1)}}>&laquo;</a>
                </li>
                {this.props.pagina>1?
                    <li className="page-item">
                        <a className="page-link" href="javascript:;" onClick={()=>{consultaCrediarioDao(this.props.tipo, this.props.dtIni, this.props.dtFim, this.props.cliente, pagAnterior)}}>{pagAnterior}</a>
                    </li>:""
                }
                <li className="page-item active">
                    <a className="page-link" href="javascript:;" onClick={()=>{consultaCrediarioDao(this.props.tipo, this.props.dtIni, this.props.dtFim, this.props.cliente, pagina)}}>{pagina}</a>
                </li>
                {proxP}
                <li className="page-item">
                    <a className="page-link" href="javascript:;"  onClick={()=>{consultaCrediarioDao(this.props.tipo, this.props.dtIni, this.props.dtFim, this.props.cliente, ultPagina)}}>&raquo;</a>
                </li>
            </ul>
        );
    }
}

class TabelaCredAPgar extends React.Component{
    render(){
        // console.log(this.props.lista)
        let cont = 0;
        let lista = this.props.lista.map((item)=>{
            cont++;
            return(
                <tr key={cont}>
                    <td>{item['nome']}</td>
                    <td>{item['valor_a_pagar']}</td>
                    <td>{item['data_inclusao']}</td>
                    <td>{item['data_vencimento']}</td>
                    <td>
                        <img src="resourse/imagens/cifrao.png" alt="Receber" title="Receber Crediário" className="btnListUser" onClick={()=>{receberCrediarioUnico(item['id_cliente'], item['id_crediario'])}} />
                        <img src="resourse/imagens/list-item.png" alt="Ver Itens" title="Ver Itens" className="btnListUser" onClick={()=>{listarProdutosCrediario(item['id_crediario'])}}/>
                    </td>
                </tr>
            );
        });

        return(
            <table className="table table-hover" id="tabela">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Valor a Pagar</th>
                        <th>Dt Venda</th>
                        <th>Dt Vencimento</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                {lista}
                </tbody>
            </table>
        )
    }
}

class SelectRecebParcial extends React.Component {
    render(){
        return(
            <div className="form-group opcoesCaixa">
                <select name="menuReceb" className="custom-select" id="menuReceb">
                    <option value=""></option>
                    <option value="recebParcial" onClick={()=>{exibirModalRecebimentoParcial(this.props.cliente, this.props.dtIni, this.props.dtFim)}}>Pagamento Parcial</option>
                </select>
            </div>
        )
    }
}

//Ultimo item da lista é a paginação (pagina e ultima página), penultimo item é o cabeçalho da tabela
function exibirRelatorioCrediario(titulo, lista, tipo, dtIni, dtFim, cliente) {
    let header = lista[lista.length-2];
    let pagination = lista[lista.length-1];
    lista.splice((lista.length-2), 2);

    // console.log(pagination);
    let elemento = (
        <div>
            <div className="titleCaixa">
                <h3 className="titulo">{titulo}</h3>
                {(cliente !== '' && tipo === 'aPagar')? <SelectRecebParcial cliente={cliente} dtIni={dtIni} dtFim={dtFim}/>:null}
            </div>
            <button className="btn btn-outline-primary btn-sm" onClick={()=>{window.reload()}}>Voltar</button>
            {(cliente !== '' && tipo === 'aPagar')? <TabelaCredAPgar lista={lista} header={header}/>: <TabelaGen lista={lista} header={header}/>}
            <PaginacaoCrediario tipo={tipo} dtIni={dtIni} dtFim={dtFim} cliente={cliente} pagina={pagination['pagina']} ultPagina={pagination['qtdPaginas']}/>
        </div>
    );

    ReactDOM.render(
        elemento,
        document.getElementById("compReact")
    )
}

class ModalItensCrediario extends React.Component{
    constructor(props){
        super(props);

        this.formataValor = this.formataValor.bind(this);
    }

    formataValor(valor){
        let x = parseFloat(valor).toFixed(2).replace('.', ',');
        return x;
    }

    render(){
        let lista = this.props.listaItens.map((item)=>{
            // let quantidade = replace('.', ',', item['quantidade']);
            return (
                <tr key={item['id_item_venda']}>
                    <td>{item['id_produto']}</td>
                    <td>{item['nome']}</td>
                    <td>{item['quantidade'].replace('.', ',')}</td>
                    <td>{this.formataValor(item['valor_total'])}</td>
                </tr>
            )
        })
        return(
            <div>
                <div className="modal mostrar tamanhoModal" id="janelaFornecedor">
                    <div className="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Itens da venda nº {this.props.listaItens[0].id_venda}</h5>
                            </div>
                            <div className="modal-body">
                                <table className="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Código Item</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    {lista}
                                    </tbody>
                                </table>

                            </div>
                            <div className="modal-footer">
                                <button onClick={fecharCompR} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        )
    }
}

function exibirModalItensCrediario(listaItens) {
    let elemento = (
        <div>
            <ModalItensCrediario listaItens={listaItens}/>
        </div>
    )

    ReactDOM.render(
        elemento,
        document.getElementById("compR")
    )
}

class ModalRecebimentoParcial extends React.Component{
    render(){
        return (
            <div>
                <div className="modal mostrar tamanhoModal" id="janelaFornecedor">
                    <div className="modal-dialog modal-dialog-centered modal-md">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>Recebimento de Crediário Parcial</h5>
                            </div>
                            <div className="modal-body">
                                <div id="pesquisaReact"></div>
                                <div className="form-group">
                                    <label htmlFor="vlPago" className="col-form-label">Valor do Pagamento</label>
                                    <input type="text" className="form-control valor" maxLength="9" id="vlPago"/>
                                </div>
                                <span>Forma de Pagamento</span>
                                <div className="form-group especieLinha">
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="dinheiro" name="especie"
                                               className="custom-control-input" value="1"/>
                                        <label className="custom-control-label" htmlFor="dinheiro">Dinheiro</label>
                                    </div>
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="debito" name="especie"
                                               className="custom-control-input" value="2"/>
                                        <label className="custom-control-label" htmlFor="debito">Débito</label>
                                    </div>
                                    <div className="custom-control custom-radio especieItem">
                                        <input type="radio" id="credito" name="especie"
                                               className="custom-control-input" value="4"/>
                                        <label className="custom-control-label" htmlFor="credito">Crédito</label>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button  onClick={()=>{recebimentoParcial(this.props.cliente, this.props.dtIni, this.props.dtFim)}} className="btn btn-success">Receber</button>
                                <button onClick={fecharCompR} className="btn btn-secondary">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="modal-backdrop"></div>
            </div>
        )
    }
}

function exibirModalRecebimentoParcial(cliente, dataInicio, dataFim){
    let elemento = (
        <div>
            <ModalRecebimentoParcial cliente={cliente} dtIni={dataInicio} dtFim={dataFim}/>
        </div>
    )

    ReactDOM.render(
        elemento,
        document.getElementById("compR")
    )
}

class SelectCaixa extends React.Component{
    render(){
        let options = this.props.lista.map((item)=>{
            return (<option value={item.id} key={item.id}>{item.id+" - "+item.data_abertura}</option>)
        });
        return(
            <select className="custom-select col-md-5" id="selectCaixaList">
                <option value="-1">Escolha o caixa</option>
                {options}
            </select>
        )
    }
}

function exibirSelectCaixa(lista){
    let elemento = (
        <SelectCaixa lista={lista} />
    )

    ReactDOM.render(
        elemento,
        document.getElementById("selectCaixa")
    )
}

class TBodyEspecie extends React.Component{
    render(){
        let count = 0;
        let lista = "";
        if (Array.isArray(this.props.listaEspecie)){
            lista = this.props.listaEspecie.map((item)=>{
                count++;
                return (
                    <tr key={count}>
                        <td>{item['nome']}</td>
                        <td>{item['total_especie']}</td>
                    </tr>
                )
            })
        }
        return(
            <tbody>
            {lista}
            </tbody>
        )
    }
}

class TBodyCrediario extends React.Component{
    render(){
        let count =0;
        let lista = "";
        if (Array.isArray(this.props.listaCred)){
            lista = this.props.listaCred.map((item)=>{
                count++
                return(
                    <tr key={count}>
                        <td>{item['descricao']}</td>
                        <td>{item['vl_pago']}</td>
                    </tr>
                )
            })
        }

        return(
            <tbody>
            {lista}
            </tbody>
        )
    }
}

function exibirRelatorioCaixa(dados) {
    let elemento = (
        <div>
            <button className="btn btn-sm btn-outline-primary" onClick={reload}>Voltar</button>
            <h3>Informações do Caixa</h3>
            <table className="table table-hover">
                <thead>
                <tr className="table-primary">
                    <th>Nº Caixa</th>
                    <th>Data Abertura</th>
                    <th>Data Fechamento</th>
                    <th>Vl Abertura Cédula</th>
                    <th>Vl Abertura Moeda</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{dados[0].id}</th>
                        <th>{dados[0].dt_abertura}</th>
                        <th>{dados[0].dt_fechamento}</th>
                        <th>{dados[0].abertura_cedula}</th>
                        <th>{dados[0].abertura_moeda}</th>
                    </tr>
                </tbody>
            </table>

            <h3>Total por Espécie</h3>
            <table className="table table-hover">
                <thead>
                <tr className="table-primary">
                    <th>Espécie</th>
                    <th>Valor</th>
                </tr>
                </thead>
                <TBodyEspecie listaEspecie={dados[1]}/>
            </table>

            <h3>Crediário Recebido</h3>
            <table className="table table-hover">
                <thead>
                <tr className="table-primary">
                    <th>Descrição</th>
                    <th>Valor Pago</th>
                </tr>
                </thead>
                <TBodyCrediario listaCred={dados[2]}/>
            </table>

            <h3>Informações Gerenciais</h3>
            <table className="table table-hover">
                <thead>
                <tr className="table-primary">
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Ticket Médio</th>
                        <td>{dados[3].ticket_medio}</td>
                    </tr>
                    <tr>
                        <th>Quantidade de Clientes</th>
                        <td>{dados[4].qtdCli}</td>
                    </tr>
                    <tr>
                        <th>Valor do Caixa (vendido + abertura)</th>
                        <td>{dados[5].vl_total}</td>
                    </tr>
                    <tr>
                        <th>Desconto Fornecido</th>
                        <td>{dados[6].valor_desconto}</td>
                    </tr>
                </tbody>
            </table>
            <div className="space"></div>
        </div>
    )

    ReactDOM.render(
        elemento,
        document.getElementById("compReact")
    )
}

function setProdutoAjuste(id) {
    fecharCompReact();
    ajustePreco(id);
}

function setProdutoConvert(id, local) {
    fecharCompReact();
    pesquisaProdutoConvert(id, local);
}

function setProdutoEntrada(id) {
    fecharCompReact();
    entradaProduto(id);
}
function setProdutoCaixa(id) {
    fecharCompReact();
    pesquisaProdutoCaixa(id);
}

function setClienteVenda(id) {
    fecharPesquisaReact();
    pesquisaCliente(id);
}