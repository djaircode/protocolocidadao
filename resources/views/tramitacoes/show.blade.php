@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('tramitacoes.index') }}">Lista de Tramitações</a></li>
      <li class="breadcrumb-item active" aria-current="page">Exibir Registro</li>
    </ol>
  </nav>
</div>
<div class="container py-2" id="tramitacao">
  <div class="card">
      <div class="card-header">
        <div class="row">
          <strong class="align-middle"> Tramitado em {{$tramitacao->created_at->format('d/m/Y')}} {{$tramitacao->created_at->diffForHumans()}}</strong>
        </div>   
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="container p-3 mb-2 bg-info text-white">
              <div class="row">           
                <div class="col">
                  <label for="tramitado_para"><i class="bi bi-arrow-right"></i> Tramitado para o funcionário:</label>
                  <h3 id="tramitado_para">{{$tramitacao->userDestino->name}}</h3> 
                </div>
                <div class="col">
                  <label for="no_setor"><i class="bi bi-arrow-right"></i> Do setor:</label>
                  <h3 id="no_setor">{{$tramitacao->setorDestino->descricao}}</h3> 
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="container">
              <div class="row">           
                <div class="col">
                  <label for="funcionario_origem">Funcionário de Origem:</label>
                  <h4 id="funcionario_origem">{{$tramitacao->userOrigem->name}} </h4> 
                </div>
                <div class="col">
                  <label for="setor_origem">Setor de Origem:</label>  
                  <h4 id="setor_origem">{{$tramitacao->setorOrigem->descricao}}  </h4> 
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">          
            <div class="container">
              <div class="row">
                <div class="col">
                  @if ($tramitacao->recebido == 's')
                    <h4 class="p-3 mb-2 bg-success text-white text-center">Recebido em {{$tramitacao->recebido_em->format('d/m/Y')}}</h4>
                  @else
                    <h4><span class="badge badge-danger">Não Recebido</span></h4>
                  @endif
                </div>
                <div class="col">
                  @if ($tramitacao->tramitado == 's')
                    <h4 class="p-3 mb-2 bg-success text-white text-center">Tramitado em {{$tramitacao->tramitado_em->format('d/m/Y')}}</h4>
                  @else
                    <h4><span class="badge badge-warning">Não Tramitado</span></h4>
                  @endif
                </div>
              </div>
            </div>
          </li>
          @if ( strlen($tramitacao->mensagem) > 0 )
          <li class="list-group-item">
            <div class="container">       
              <p class=" p-2 mb-2 bg-light text-dark"><strong>Mensagem:</strong> {{ $tramitacao->mensagem }}</p>
            </div>
          </li>
          @endif
          @if ( strlen($tramitacao->mensagemRecebido) > 0 )
          <li class="list-group-item">
            <div class="container">       
              <p class=" p-2 mb-2 bg-light text-dark"><strong>Mensagem de recebimento:</strong> {{ $tramitacao->mensagemRecebido }}</p>
            </div>
          </li>
          @endif
        </ul>
      </div>  
    </div>
</div>
<div class="container" id="protocolo">
  <div class="container">
    <form>
      <div class="form-row">
        <div class="form-group col-md-3">
          <div class="p-3 bg-primary text-white text-right h2">Nº {{ $protocolo->id }}</div>    
        </div>
        <div class="form-group col-md-2">
          <label for="dia">Data</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->created_at->format('d/m/Y') }}" readonly>
        </div>
        <div class="form-group col-md-2">
          <label for="hora">Hora</label>
          <input type="text" class="form-control" name="hora" value="{{ $protocolo->created_at->format('H:i') }}" readonly>
        </div>
        <div class="form-group col-md-5 text-right">
          <div class="btn-group" role="group">
            <button id="btnGroupDropOptions" type="button" class="btn btn-secondary dropdown-toggle btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-printer"></i>Relatórios
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDropOptions">
              <a class="dropdown-item" href="{{ route('protocolos.export.pdf.completo', $protocolo->id) }}" id="btnExportarPDF"><i class="bi bi-file-pdf-fill"></i> Exportar PDF Completo</a>
              <a class="dropdown-item" href="{{ route('protocolos.export.pdf.simples', $protocolo->id) }}" id="btnExportarPDF"><i class="bi bi-file-pdf-fill"></i> Exportar PDF Simples</a>
            </div>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="dia">Funcionário Responsável pelo Protocolo</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->user->name }}" readonly tabindex="-1">
        </div>
        <div class="form-group col-md-6">
          <label for="dia">Setor de Origem</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->user->setor->descricao  }}" readonly tabindex="-1">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="referencia">N° do Processo/Referência</label>
          <input type="text" class="form-control{{ $errors->has('referencia') ? ' is-invalid' : '' }}" name="referencia" value="{{ old('referencia') ?? $protocolo->referencia }}" readonly>
          @if ($errors->has('referencia'))
          <div class="invalid-feedback">
          {{ $errors->first('referencia') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label for="protocolo_tipo">Tipo do Protocolo</label>
          <input type="text" class="form-control" name="protocolo_tipo" value="{{ $protocolo->protocoloTipo->descricao }}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="conteudo">Conteúdo/Descrição</label>
        <textarea class="form-control" name="conteudo" rows="5" readonly>{{ $protocolo->conteudo }}</textarea>    
      </div>
      @if ($protocolo->concluido === 'n')
        <div class="form-group">
          <label for="situacao">Situação</label>
          <input type="text" class="form-control" name="situacao" value="{{ $protocolo->protocoloSituacao->descricao }}" readonly style="font-weight: bold;">
        </div>
      @else
      <div class="form-row">
          <div class="form-group col-sm-6">
            <label for="situacao">Situação</label>
            <input type="text" class="form-control" name="situacao" value="{{ $protocolo->protocoloSituacao->descricao }}" readonly style="font-weight: bold;">
          </div>
          <div class="form-group col-sm-3">
            <label for="protocolo_data_conclusao">Data(conclusão)</label>
            <input type="text" class="form-control" name="protocolo_data_conclusao" value="{{ $protocolo->concluido_em->format('d/m/Y') }} " readonly>
          </div>
          <div class="form-group col-sm-3">
            <label for="protocolo_hora_conclusao">Hora(conclusão)</label>
            <input type="text" class="form-control" name="protocolo_hora_conclusao" value="{{ $protocolo->concluido_em->format('H:i')  }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="conteudo">Notas de conclusão</label>
          <textarea class="form-control" name="conteudo" rows="3" readonly>{{ $protocolo->concluido_mensagem }}</textarea>
        </div>
      @endif
    </form>
  </div>
  <div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Anexos</strong></p>
    </div>
    <div class="container">
      @if ( !$anexos->isEmpty() ) 
      <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Arquivo</th>
                    <th scope="col">Enviado por</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anexos as $anexo)
                <tr>
                    <td>{{ $anexo->created_at->format('d/m/Y')  }}</td>
                    <td>{{ $anexo->created_at->format('H:i') }}</td>
                    <td><a href="{{ route('anexos.download', $anexo->codigoAnexoPublico) }}">{{ $anexo->arquivoNome }}</a></td>
                    <td>{{ $anexo->user->name }}</td>                       
                </tr>    
                @endforeach                                                 
            </tbody>
        </table>
      </div>
      @else
        <p>Nenhum anexo encontrado</p>
      @endif
    </div>  
  </div>
</div>

<div class="container-fluid">
  <div class="container-fluid bg-info text-dark">
    <p class="text-center"><strong>Tramitações</strong></p>
  </div>
</div>

 @if ( !$protocolo->tramitacaos->isEmpty() )
<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Data</th>
                <th scope="col">Hora</th>
                <th></th>
                <th scope="col" class="text-right">N° Prot.</th>
                <th scope="col">Funcionário de Origem</th>
                <th scope="col">Setor de Origem</th>
                <th scope="col">Tramitado Para</th>
                <th scope="col">No Setor</th>
                <th scope="col">Recebido</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($protocolo->tramitacaos->sortByDesc('id') as $tramitacao_temp)
            <tr>
                <td>
                  {{$tramitacao_temp->created_at->format('d/m/Y')}}
                </td>
                <td>
                  {{ $tramitacao_temp->created_at->format('H:i') }}
                </td>
                <td>
                  {{$tramitacao_temp->created_at->diffForHumans()}}
                </td>
                <td class="text-right">
                  <strong>{{$tramitacao_temp->protocolo->id}}</strong>
                </td>
                <td>
                  @if (Auth::user()->id == $tramitacao_temp->userOrigem->id)
                  <strong class="text-warning"> {{$tramitacao_temp->userOrigem->name}} </strong>
                  @else
                    {{$tramitacao_temp->userOrigem->name}}
                  @endif
                </td>
                <td>
                  {{$tramitacao_temp->setorOrigem->descricao}}
                </td>
                <td>
                  @if (Auth::user()->id == $tramitacao_temp->userDestino->id)
                  <strong class="text-warning"><i class="fas fa-hand-point-right"></i> {{$tramitacao_temp->userDestino->name}} </strong>
                  @else
                    <i class="fas fa-hand-point-right"></i> {{$tramitacao_temp->userDestino->name}}
                  @endif
                </td>
                <td>
                  {{$tramitacao_temp->setorDestino->descricao}}
                </td>
                @if($tramitacao_temp->recebido == 's')
                <td>
                  <h5><span class="badge badge-success"><i class="bi bi-hand-thumbs-up-fill"></i> {{$tramitacao_temp->recebido_em->format('d/m/Y')}}</span></h5>
                </td>
                @else
                <td>
                  <h5><span class="badge badge-danger"><i class="bi bi-hand-thumbs-down-fill"></i>Não Recebido</span></h5>
                </td>
                @endif
                <td>
                  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTramitacao" data-tramitacao-id="{{ $tramitacao_temp->id}}"><i class="bi bi-eye"></i></button>
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <div class="container p-2">
  <p class="text-center">Esse protocolo foi tramitado {{ $protocolo->tramitacaos->count() }} veze(s)</p>    
</div>
</div>
@else
  <p class="p-3 text-center">Esse protocolo não foi tramitado</p>
@endif  
<br>

<div class="container">
  <div class="container bg-primary text-light">
    <p class="text-center"><strong>Funcionários com Acesso a esse Protocolo</strong></p>
  </div>
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Funcionário</th>
                <th scope="col">Setor</th>
            </tr>
        </thead>
        <tbody>
          @foreach($protocolo->users->sortBy('name') as $user)
          <tr>
              <td>{{ $user->name  }}</td>
              <td>{{ $user->setor->descricao }}</td>
          </tr>    
          @endforeach
        </tbody>
    </table>
  </div>
</div>

@if ( !$protocolo->tramitacaos->isEmpty() )
<div class="modal fade" id="modalTramitacao" tabindex="-1" role="dialog" aria-labelledby="JanelaProfissional" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-eye"></i> Informações da Tramitação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="container">
          <span class="text-center" id="quando_text"></span>  
        </div>
        <div class="container bg-info text-white">
          <div class="row">           
            <div class="col">
              <label for="tramitado_para_text"><i class="bi bi-arrow-right"></i> Tramitado para o funcionário:</label>
              <h5 id="tramitado_para_text"></h5> 
            </div>
            <div class="col">
              <label for="no_setor_text"><i class="bi bi-arrow-right"></i> Do setor:</label>
              <h5 id="no_setor_text"></h5> 
            </div>
          </div>
        </div>  
        <div class="container">
          <div class="row">           
            <div class="col">
              <label for="funcionario_origem_text">Funcionário de Origem:</label>
              <h5 id="funcionario_origem_text"></h5> 
            </div>
            <div class="col">
              <label for="setor_origem_text">Setor de Origem:</label>  
              <h5 id="setor_origem_text"></h5> 
            </div>
          </div>
        </div>
        <div class="container">
          <h4 class="p-3 mb-2 bg-primary text-white text-center" id="recebida_text"></h4>
        </div>
        <div class="container">
          <label for="menagem_text">Mensagem Enviada:</label>      
          <p class=" p-2 mb-2 bg-light text-dark" id="menagem_text"></p>
        </div>
        <div class="container">
          <label for="menagem_recebimento_text">Mensagem de Recebimento:</label>       
          <p class=" p-2 mb-2 bg-light text-dark" id="menagem_recebimento_text"></p>
        </div>
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Fechar</button>
      </div>
    </div>
  </div>
</div>  
@endif


<div class="container">
  <div class="float-right">
    <a href="{{ url('/') }}" class="btn btn-secondary" role="button"><i class="bi bi-clipboard-data"></i> Painel de Controle</a>
    <a href="{{ route('tramitacoes.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
<br>
@endsection

@section('script-footer')
<script>
$(document).ready(function(){
    $(function () {
      $('[data-toggle="popover"]').popover()
    })

    $('#modalTramitacao').on('show.bs.modal', function(e) {
          var tramitacaoid = $(e.relatedTarget).data('tramitacao-id');

          $.ajax({
            dataType: "json",
            url: "{{url('/')}}" + "/tramitacoes/json/" + tramitacaoid,
            type: "GET",
            success: function(json) {
                    $("#quando_text").text(json['quando']);
                    $("#tramitado_para_text").text(json['funcionario_destino']);
                    $("#no_setor_text").text(json['setor_destino']);
                    $("#funcionario_origem_text").text(json['funcionario_origem']);
                    $("#setor_origem_text").text(json['setor_origem']);
                    $("#recebida_text").text(json['recebido']);
                    $("#menagem_text").text(json['mensagem']);
                    $("#menagem_recebimento_text").text(json['mensagemRecebido']);
            }
        });
      });   

});
</script>
@endsection