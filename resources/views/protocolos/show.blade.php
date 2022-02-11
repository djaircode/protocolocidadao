
@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('protocolos.index') }}">Lista de Protocolos</a></li>
      <li class="breadcrumb-item active" aria-current="page">Exibir Registro</li>
    </ol>
  </nav>
</div>
<div class="container">  
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
    <div class="row">
      <div class="col-sm">
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
            <p>Nenhum anexo</p>
          @endif
        </div>
      </div> 
    </div>        
   </div>
  <div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Tramitações</strong></p>
    </div>
  </div>
  <br>
  @foreach($tramitacoes as $tramitacao)
  <div class="container py-2">   
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <strong class="align-middle"> Tramitado em {{$tramitacao->created_at->format('d/m/Y')}} {{$tramitacao->created_at->diffForHumans()}}</strong>   
          </div>
          <div class="col text-right">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseTramitacao{{$tramitacao->id}}" aria-expanded="false" aria-controls="collapseExample"><i class="bi bi-plus-square"></i> Informações</button>  
          </div>
        </div>   
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="container">
              <div class="row">           
                <div class="col">
                  <h4><i class="bi bi-arrow-right"></i> {{$tramitacao->userDestino->name}}</h4> 
                </div>
                <div class="col">
                  <strong>No Setor:</strong> {{$tramitacao->setorDestino->descricao}}
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">          
            <div class="container">
              <div class="row">
                <div class="col">
                  @if ($tramitacao->recebido == 's')
                    <strong>Recebido em {{$tramitacao->recebido_em->format('d/m/Y')}}</strong>
                  @else
                    <span class="badge badge-danger">Não Recebido</span>
                  @endif
                </div>
                <div class="col">
                  @if ($tramitacao->tramitado == 's')
                    <strong>Tramitado em {{$tramitacao->tramitado_em->format('d/m/Y')}}</strong>
                  @else
                    <span class="badge badge-warning">Não Tramitado</span>
                  @endif
                </div>
              </div>
            </div>
          </li>
          </ul>
          <div class="collapse" id="collapseTramitacao{{$tramitacao->id}}">
            <div class="card card-body">
              @if ( strlen($tramitacao->mensagem) > 0 )
              <p><strong>Mensagem:</strong> {{ $tramitacao->mensagem }}</p>
              @endif
              @if ( strlen($tramitacao->mensagemRecebido) > 0 )
              <p><strong>Mensagem de recebimento:</strong> {{ $tramitacao->mensagemRecebido }}</p>
              @endif
              <div class="container">
                <div class="row">
                  <div class="col">
                    <strong>Funcionário de Origem:</strong> {{$tramitacao->userOrigem->name}}  
                  </div>
                  <div class="col">
                    <strong>Setor de Origem:</strong> {{$tramitacao->setorOrigem->descricao}}  
                  </div>
                </div>
              </div>
            </div>
          </div>            
        </div>  
      </div>  
    </div>
    @endforeach
  <br>
  <div class="container">
    <div class="float-right">
      <a href="{{ url('/') }}" class="btn btn-secondary" role="button"><i class="bi bi-clipboard-data"></i> Painel de Controle</a>
      <a href="{{ route('protocolos.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a> 
    </div>
  </div>
</div>
@endsection