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
          @if (($tramitacao->recebido == 'n') and ($tramitacao->protocolo->concluido == 'n'))
          <li class="list-group-item">  
            <div class="container py-2 text-center" id="opcoestramitacoes">             
              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalReceberTramitacao">
                <i class="bi bi-hand-thumbs-up"></i> Receber
              </button>
              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalTramitarProtocolo">
                <i class="bi bi-arrow-clockwise"></i> Tramitar 
              </button>    
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
<div class="container">
  <div class="float-right">
    <a href="{{ url('/') }}" class="btn btn-secondary" role="button"><i class="bi bi-clipboard-data"></i> Painel de Controle</a>
    <a href="{{ route('tramitacoes.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
<br>
@endsection
