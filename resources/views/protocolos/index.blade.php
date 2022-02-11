@extends('layouts.app')

@section('css-header')
<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('protocolos.index') }}">Lista de Protocolos</a></li>
    </ol>
  </nav>
  @if(Session::has('deleted_protocolo'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Info!</strong>  {{ session('deleted_protocolo') }}
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
  <div class="btn-group py-1" role="group" aria-label="Opções">
    <a href="{{ route('protocolos.create') }}" class="btn btn-secondary btn-sm" role="button"><i class="bi bi-plus-circle"></i> Novo Registro</a>
    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFilter"><i class="bi bi-funnel"></i> Filtrar</button>
  </div>
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nº</th>
                <th scope="col">Data</th>
                <th scope="col"></th>
                <th scope="col">N° do Processo/Referência</th>
                <th scope="col">Setor de Origem</th>
                <th scope="col">Funcionário Responsável</th>
                <th scope="col">Tipo</th>
                <th scope="col">Situação</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($protocolos as $protocolo)
              <tr>          
                <td><strong>{{$protocolo->id}}</strong></td>
                <td>{{$protocolo->created_at->format('d/m/Y')}}</td>
                <td>{{$protocolo->created_at->diffForHumans()}}</td>                
                <td>{{$protocolo->referencia}}</td>                
                <td>{{$protocolo->setor->descricao}}</td>
                <td>
                @if (Auth::user()->id == $protocolo->user->id)
                  <strong class="text-primary"> {{$protocolo->user->name}} </strong>
                @else
                  {{$protocolo->user->name}}
                @endif
                </td>
                <td>{{$protocolo->protocoloTipo->descricao}}</td>
                @switch($protocolo->protocoloSituacao->id)
                  @case(1)
                      <td><span class="badge badge-pill badge-warning">Aberto</span></td>
                      @break
                  @case(2)
                      <td><span class="badge badge-pill badge-primary">Em Tramitação</span></td>
                      @break
                  @case(3)
                      <td><span class="badge badge-pill badge-success">Concluido</span></td>
                      @break
                  @case(4)
                      <td><span class="badge badge-pill badge-danger">Cancelado</span></td>
                      @break        
                  @default
                      <td></td>
                @endswitch
                <td>
                  <div class="btn-group" role="group">
                    @if ($protocolo->user->id == Auth::user()->id)
                    <a href="{{route('protocolos.edit', $protocolo->id)}}" class="btn btn-info btn-sm" role="button"><i class="bi bi-pencil-square"></i></a>
                    @else
                    <a href="{{route('protocolos.show', $protocolo->id)}}" class="btn btn-primary btn-sm" role="button"><i class="bi bi-eye"></i></a>
                    @endif
                  </div>
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <p class="text-center">Página {{ $protocolos->currentPage() }} de {{ $protocolos->lastPage() }}. Total de registros: {{ $protocolos->total() }}.</p>
  <div class="container-fluid">
      {{ $protocolos->links() }}
  </div>
  <!-- Janela de filtragem da consulta -->
  <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-funnel"></i> Filtro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Filtragem dos dados -->
          <form method="GET" action="{{ route('protocolos.index') }}">
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="id_protocolo">Nº</label>
                <input type="text" class="form-control" id="id_protocolo" name="id_protocolo" value="{{request()->input('id_protocolo')}}">  
              </div>
              <div class="form-group col-md-4">
                <label for="referencia">N° do Processo/Referência</label>
                <input type="text" class="form-control" id="referencia" name="referencia" value="{{request()->input('referencia')}}">
              </div>
              <div class="form-group col-md-3">
                <label for="dtainicio">Data inicial</label>
                <input type="text" class="form-control" id="dtainicio" name="dtainicio" value="{{request()->input('dtainicio')}}" autocomplete="off">  
              </div>
              <div class="form-group col-md-3">
                <label for="dtafinal">Data final</label>
                <input type="text" class="form-control" id="dtafinal" name="dtafinal" value="{{request()->input('dtafinal')}}" autocomplete="off">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="setor_id">Setor de Origem</label>
                <select class="form-control" name="setor_id" id="setor_id">
                  <option value="">Mostrar todos</option>
                  @foreach($setores as $setor)
                  <option value="{{$setor->id}}" {{ ($setor->id == request()->input('setor_id')) ? ' selected' : '' }} >{{$setor->descricao}}</option>
                  @endforeach
                </select>       
              </div>
              <div class="form-group col-md-6">
                <label for="funcionario">Funcionário Responsável</label>
                <input type="text" class="form-control" id="funcionario" name="funcionario" value="{{request()->input('funcionario')}}">    
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="protocolo_tipo_id">Tipo</label>
                <select class="form-control" name="protocolo_tipo_id" id="protocolo_tipo_id">
                  <option value="">Mostrar todos</option>
                  @foreach($protocolotipos as $protocolotipo)
                  <option value="{{$protocolotipo->id}}" {{ ($protocolotipo->id == request()->input('protocolo_tipo_id')) ? ' selected' : '' }} >{{$protocolotipo->descricao}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="protocolo_situacao_id">Situação</label>
                <select class="form-control" name="protocolo_situacao_id" id="protocolo_situacao_id">
                  <option value="">Mostrar todos</option>
                  @foreach($protocolosituacoes as $protocolosituacao)
                  <option value="{{$protocolosituacao->id}}" {{ ($protocolosituacao->id == request()->input('protocolo_situacao_id')) ? ' selected' : '' }} >{{$protocolosituacao->descricao}}</option>
                  @endforeach
                </select>  
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Pesquisar</button>
            <a href="{{ route('protocolos.index') }}" class="btn btn-primary btn-sm" role="button">Limpar</a>
          </form>
          <br>  
          <!-- Seleção de número de resultados por página -->
          <div class="form-group">
            <select class="form-control" name="perpage" id="perpage">
              @foreach($perpages as $perpage)
              <option value="{{$perpage->valor}}"  {{($perpage->valor == session('perPage')) ? 'selected' : ''}}>{{$perpage->nome}}</option>
              @endforeach
            </select>
          </div>
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Fechar</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script-footer')
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('locales/bootstrap-datepicker.pt-BR.min.js') }}"></script>
<script>
$(document).ready(function(){
    $('#perpage').on('change', function() {
        perpage = $(this).find(":selected").val(); 
        
        window.open("{{ route('protocolos.index') }}" + "?perpage=" + perpage,"_self");
    });

  $('#dtainicio').datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    clearBtn: true,
    language: "pt-BR",
    autoclose: true,
    todayHighlight: true
  });

  $('#dtafinal').datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    clearBtn: true,
    language: "pt-BR",
    autoclose: true,
    todayHighlight: true
  });
}); 
</script>
@endsection