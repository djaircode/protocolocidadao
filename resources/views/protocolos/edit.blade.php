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
  <div class="container-fluid">
    <div class="container-fluid bg-info text-dark">
      <p class="text-center"><strong>Tramitações</strong></p>
    </div>
  </div>
  @if(Session::has('create_protocolotramitacao'))
  <div class="container">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Info!</strong>  {{ session('create_protocolotramitacao') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
  @endif
  @if ($protocolo->concluido === 'n')
  <div class="container text-center">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalTramitarProtocolo">
    <i class="bi bi-arrow-clockwise"></i> Realizar a Tramitação desse Protocolo 
  </button>    
  </div>
  <br>
  @endif
  @if ( !$tramitacoes->isEmpty() )
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
              @foreach($tramitacoes as $tramitacao)
              <tr>
                  <td>
                    {{$tramitacao->created_at->format('d/m/Y')}}
                  </td>
                  <td>
                    {{ $tramitacao->created_at->format('H:i') }}
                  </td>
                  <td>
                    {{$tramitacao->created_at->diffForHumans()}}
                  </td>
                  <td class="text-right">
                    <strong>{{$tramitacao->protocolo->id}}</strong>
                  </td>
                  <td>
                    @if (Auth::user()->id == $tramitacao->userOrigem->id)
                    <strong class="text-warning"> {{$tramitacao->userOrigem->name}} </strong>
                    @else
                      {{$tramitacao->userOrigem->name}}
                    @endif
                  </td>
                  <td>
                    {{$tramitacao->setorOrigem->descricao}}
                  </td>
                  <td>
                    @if (Auth::user()->id == $tramitacao->userDestino->id)
                    <strong class="text-warning"><i class="fas fa-hand-point-right"></i> {{$tramitacao->userDestino->name}} </strong>
                    @else
                      <i class="fas fa-hand-point-right"></i> {{$tramitacao->userDestino->name}}
                    @endif
                  </td>
                  <td>
                    {{$tramitacao->setorDestino->descricao}}
                  </td>
                  @if($tramitacao->recebido == 's')
                  <td>
                    <h5><span class="badge badge-success"><i class="bi bi-hand-thumbs-up-fill"></i> {{$tramitacao->recebido_em->format('d/m/Y')}}</span></h5>
                  </td>
                  @else
                  <td>
                    <h5><span class="badge badge-danger"><i class="bi bi-hand-thumbs-down-fill"></i>Não Recebido</span></h5>
                  </td>
                  @endif
                  <td>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTramitacao" data-tramitacao-id="{{ $tramitacao->id}}"><i class="bi bi-eye"></i></button>
                  </td>
              </tr>    
              @endforeach                                                 
          </tbody>
      </table>
    </div>
    <div class="container p-2">
    <p class="text-center">Esse protocolo foi tramitado {{ $tramitacoes->count() }} veze(s)</p>    
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
  <div class="container py-3">
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

@if ( !$tramitacoes->isEmpty() )
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
                limit: 7,
                templates: {
                  empty: [
                    '<div class="empty-message">',
                      '<p class="text-center font-weight-bold text-warning">Não foi encontrado nenhum funcionário com o texto digitado.</p>',
                    '</div>'
                  ].join('\n'),
                  suggestion: function(data) {
                      return '<div class="text-dark"><div>' + data.text + ' - ' + data.setor + '</div></div>';
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