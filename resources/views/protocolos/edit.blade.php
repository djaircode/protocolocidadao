@extends('layouts.app')

@section('css-header')
<style>
  .twitter-typeahead, .tt-hint, .tt-input, .tt-menu { width: 100%; }
  .tt-query, .tt-hint { outline: none;}
  .tt-query { box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);}
  .tt-hint {color: #999;}
  .tt-menu { 
      width: 100%;
      margin-top: 12px;
      padding: 8px 0;
      background-color: #fff;
      border: 1px solid #ccc;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      box-shadow: 0 5px 10px rgba(0,0,0,.2);
  }
  .tt-suggestion { padding: 3px 20px; }
  .tt-suggestion.tt-is-under-cursor { color: #fff; }
  .tt-suggestion p { margin: 0;}
</style>
@endsection

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('protocolos.index') }}">Lista de Protocolos</a></li>
      <li class="breadcrumb-item active" aria-current="page">Alterar Registro</li>
    </ol>
  </nav>
</div>
<div class="container">
  @if(Session::has('edited_protocolo'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Info!</strong>  {{ session('edited_protocolo') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if(Session::has('create_protocolo'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Info!</strong>  {{ session('create_protocolo') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if ($errors->has('funcionario_tramitacao_id'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Erro!</strong>  {{ $errors->first('funcionario_tramitacao_id') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if ($errors->has('setor_tramitacao_id'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Erro!</strong>  {{ $errors->first('setor_tramitacao_id') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if ($errors->has('protocolo_id'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Erro!</strong>  {{ $errors->first('protocolo_id') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="container">
    @if ($protocolo->concluido === 'n')
    <form method="POST" action="{{ route('protocolos.update', $protocolo->id) }}">
      @csrf
      @method('PUT')
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
          <label for="referencia">N° do Processo/Referência <strong class="text-warning">(Opcional)</strong></label>
          <input type="text" class="form-control{{ $errors->has('referencia') ? ' is-invalid' : '' }}" name="referencia" value="{{ old('referencia') ?? $protocolo->referencia }}">
          @if ($errors->has('referencia'))
          <div class="invalid-feedback">
          {{ $errors->first('referencia') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label for="protocolo_tipo_id">Tipo do Protocolo <strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('protocolo_tipo_id') ? ' is-invalid' : '' }}" name="protocolo_tipo_id" id="protocolo_tipo_id">
            <option value="{{$protocolo->protocolo_tipo_id}}" selected="true">&rarr; {{ $protocolo->protocoloTipo->descricao }}</option>        
            @foreach($protocolotipos as $protocolotipo)
            <option value="{{$protocolotipo->id}}">{{ $protocolotipo->descricao }}</option>
            @endforeach
            </select>
            @if ($errors->has('protocolo_tipo_id'))
            <div class="invalid-feedback">
            {{ $errors->first('protocolo_tipo_id') }}
            </div>
            @endif
        </div>
      </div>
      <div class="form-group">
        <label for="conteudo">Conteúdo/Descrição <strong  class="text-danger">(*)</strong></label>
        <textarea class="form-control" name="conteudo" rows="5">{{ $protocolo->conteudo }}</textarea>      
      </div>
      <div class="form-group">
        <label for="situacao">Situação</label>
        <input type="text" class="form-control" name="situacao" value="{{ $protocolo->protocoloSituacao->descricao }}" readonly style="font-weight: bold;">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Alterar</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalConcluirProtocolo">
          <i class="bi bi-hand-thumbs-up"></i> Concluir
        </button>
      </div>
    </form>
    @else
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
    </form>
    @endif
    </div>
  </div>
  <div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Anexos</strong></p>
    </div>
    @if ($protocolo->concluido === 'n')
    <div class="container">
      <form method="POST" action="{{ route('anexos.store') }}" class="form-inline" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="protocolo_id" name="protocolo_id" value="{{ $protocolo->id }}">
        <div class="form-group p-2">
          <label for="arquivos">Arquivos</label>
          <input type="file" class="form-control-file" id="arquivos" name="arquivos[]" multiple data-show-upload="true" data-show-caption="true">
        </div>
        <div class="form-group py-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> Anexar Arquivo</button>
          <button type="button" class="btn btn-secondary" data-toggle="popover" title="Informações sobre o arquivo" data-placement="right" data-content="Somente são aceitos os seguintes formatos para o arquivo: pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg ou rtf. Cada arquivo não pode ter mais de 10Mb. Você pode adicionar múltiplos arquivos de uma só vez."><i class="bi bi-info-square"></i> Info</button>
        </div>
      </form>  
    </div>
    @endif
    <div class="container">
      @if(Session::has('create_anexo'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Info!</strong>  {{ session('create_anexo') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if(Session::has('delete_anexo'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Info!</strong>  {{ session('delete_anexo') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if($errors->has('arquivos.*'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><b><i class="bi bi-exclamation-diamond"></i> Erro! </b></strong> {{ $errors->first('arquivos.*') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if ( !$anexos->isEmpty() ) 
      <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Arquivo</th>
                    <th scope="col">Enviado por</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($anexos as $anexo)
                <tr>
                    <td>{{ $anexo->created_at->format('d/m/Y')  }}</td>
                    <td>{{ $anexo->created_at->format('H:i') }}</td>
                    <td><a href="{{ route('anexos.download', $anexo->codigoAnexoPublico) }}">{{ $anexo->arquivoNome }}</a></td>
                    <td>{{ $anexo->user->name }}</td>
                    <td>
                      @if ($anexo->user->id === Auth::user()->id)
                        @if ($protocolo->concluido === 'n')
                        <form method="post" action="{{route('anexos.destroy', $anexo->id)}}"  onsubmit="return confirm('Você tem certeza que quer excluir esse arquivo?');">
                          @csrf
                          @method('DELETE')  
                          <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                      @endif  
                    </td>                        
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
  <div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Tramitações</strong></p>
    </div>
  </div>
  @if ($protocolo->concluido === 'n')
  <div class="container text-center">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalTramitarProtocolo">
    <i class="bi bi-arrow-clockwise"></i> Realizar a Tramitação desse Protocolo 
  </button>    
  </div>
  <br>
  @endif
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
  @if ($protocolo->concluido === 's')
  <div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Opções</strong></p>
    </div>
  </div>
  <br>
  <div class="container text-center">
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalReabrirprotocolo">
    <i class="bi bi-folder2-open"></i> Reabrir Protocolo</button>    
  </div>
  @endif
  <br>
  <div class="container">
    <div class="float-right">
      <a href="{{ url('/') }}" class="btn btn-secondary" role="button"><i class="bi bi-clipboard-data"></i> Painel de Controle</a>
      <a href="{{ route('protocolos.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
    </div>
  </div>
  <br>
  @if ($protocolo->concluido === 'n')
  <!-- Janela para concluir o protocolo -->
  <div class="modal fade" id="modalConcluirProtocolo" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-hand-thumbs-up"></i> Concluir Protocolo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('protocolos.concluir', $protocolo->id) }}">
            @csrf
            <div class="form-group">
              <label for="concluido_mensagem">Mensagem de conclusão: <strong class="text-warning">(Opcional)</strong></label>
              <textarea class="form-control" name="concluido_mensagem" rows="3"></textarea>      
            </div>
            <div class="form-group">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="protocolo_situacao_id" id="situacao_concluido" value="3" checked="true">
                <label class="form-check-label" for="situacao_concluido">Concluído</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="protocolo_situacao_id" id="situacao_cancelado" value="4">
                <label class="form-check-label" for="situacao_cancelado">Cancelado</label>
              </div>  
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary"><i class="bi bi-hand-thumbs-up"></i> Concluir?</button>
            </div>
          </form>
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if ($protocolo->concluido === 'n')
  <!-- Janela para tramitar o protocolo -->
  <div class="modal fade" id="modalTramitarProtocolo" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-arrow-clockwise"></i> Tramitar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('protocolostramitacoes.store') }}">
            @csrf
            <input type="hidden" id="protocolo_id" name="protocolo_id" value="{{ $protocolo->id }}">
            <div class="form-group">
              <label for="funcionario_tramitacao">Funcionário <strong  class="text-danger">(*)</strong></label>
              <input type="text" class="form-control typeahead" name="funcionario_tramitacao" id="funcionario_tramitacao" autocomplete="off">
              <input type="hidden" id="funcionario_tramitacao_id" name="funcionario_tramitacao_id">
              <input type="hidden" id="setor_tramitacao_id" name="setor_tramitacao_id">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="funcionario_tramitacao_setor">Setor</label>
                <input type="text" class="form-control" name="funcionario_tramitacao_setor" id="funcionario_tramitacao_setor" readonly tabIndex="-1" placeholder="">
              </div>
              <div class="form-group col-md-6">
                <label for="funcionario_tramitacao_email">E-mail</label>
                <input type="text" class="form-control" name="funcionario_tramitacao_email" id="funcionario_tramitacao_email" readonly tabIndex="-1" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label for="mensagem">Mensagem <strong class="text-warning">(Opcional)</strong></label>
              <textarea class="form-control" name="mensagem" rows="3"></textarea> 
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Tramitar</button>
          </form>  
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Fechar</button>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if ($protocolo->concluido === 's' )
  <div class="modal fade" id="modalReabrirprotocolo" tabindex="-1" role="dialog" aria-labelledby="janelaReabrirProtocolo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-folder2-open"></i> Reabrir Protocolo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('protocolos.reabrir', array($protocolo->id))}}">
            @csrf
            <div class="alert alert-primary" role="alert">
              <strong>Atenção!</strong> Ao reabrir um protocolo concluído será apagado a mensagem e data de conclusão.       
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-question-square"></i> Confirmar</button>
          </form>
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  @endif

@endsection
@section('script-footer')
<script src="{{ asset('js/typeahead.bundle.min.js') }}"></script>
<script>
$(document).ready(function(){

var funcionarios = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "{{route('users.autocomplete')}}?query=%QUERY",
            wildcard: '%QUERY'
        },
        limit: 10
    });
    funcionarios.initialize();

    $("#funcionario_tramitacao").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: "funcionarios",
                displayKey: "text",
                source: funcionarios.ttAdapter(),
                templates: {
                  empty: [
                    '<div class="empty-message">',
                      '<p class="text-center font-weight-bold text-warning">Não foi encontrado nenhum funcionário com o texto digitado.</p>',
                    '</div>'
                  ].join('\n'),
                  suggestion: function(data) {
                      return '<div class="text-dark"><div>' + data.text + ' - <strong>Setor:</strong> ' + data.setor + '</div></div>';
                    }
                }
        }).on("typeahead:selected", function(obj, datum, name) {
            console.log(datum);
            $(this).data("seletectedId", datum.value);
            $('#funcionario_tramitacao_id').val(datum.value);
            $('#funcionario_tramitacao_setor').val(datum.setor);
            $('#funcionario_tramitacao_email').val(datum.email);
            $('#setor_tramitacao_id').val(datum.setor_id);
        }).on('typeahead:autocompleted', function (e, datum) {
            console.log(datum);
            $(this).data("seletectedId", datum.value);
            $('#funcionario_tramitacao_id').val(datum.value);
            $('#funcionario_tramitacao_setor').val(datum.setor);
            $('#funcionario_tramitacao_email').val(datum.email);
            $('#setor_tramitacao_id').val(datum.setor_id);
    });

    $(function () {
      $('[data-toggle="popover"]').popover()
    })   

});
</script>
@endsection