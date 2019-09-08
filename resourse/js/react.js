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