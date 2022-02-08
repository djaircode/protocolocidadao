@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('tramitacoes.index') }}">Lista de Tramitações</a></li>
    </ol>
  </nav>


  <div class="btn-group py-1" role="group" aria-label="Opções">
    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFilter"><i class="bi bi-funnel"></i> Filtrar</button>
    <div class="btn-group" role="group">
      <button id="btnGroupDropOptions" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-print"></i> Relatórios
      </button>
      <div class="dropdown-menu" aria-labelledby="btnGroupDropOptions">
        <a class="dropdown-item" href="#" id="btnExportarCSV"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Exportar Planilha</a>
        <a class="dropdown-item" href="#" id="btnExportarPDF"><i class="bi bi-file-pdf-fill"></i> Exportar PDF</a>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Data</th>
                <th></th>
                <th scope="col" class="text-right">N° Prot.</th>
                <th scope="col">Situação</th>
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
                  {{$tramitacao->created_at->diffForHumans()}}
                </td>
                <td class="text-right">
                  <strong>{{$tramitacao->protocolo->id}}</strong>
                </td>

                @switch($tramitacao->protocolo->protocoloSituacao->id)
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
                  {{$tramitacao->recebido_em->format('d/m/Y')}}
                </td>
                @else
                <td>
                  <h5><span class="badge badge-danger">Não Recebido</span></h5>
                </td>
                @endif
                <td>
                  <div class="btn-group" role="group">
                    <a href="{{route('tramitacoes.edit', $tramitacao->id)}}" class="btn btn-info btn-sm" role="button"><i class="bi bi-eye"></i></a>
                  </div>
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <p class="text-center">Página {{ $tramitacoes->currentPage() }} de {{ $tramitacoes->lastPage() }}. Total de registros: {{ $tramitacoes->total() }}.</p>
  <div class="container-fluid">
      {{ $tramitacoes->links() }}
  </div>
  <!-- Janela de filtragem da consulta -->
  <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-funnel"></i> Filtro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Filtragem dos dados -->
          <form method="GET" action="{{ route('tramitacoes.index') }}">
            <div class="form-group">
              <select class="form-control" id="filtro_tram" name="filtro_tram">
                <option value="1" {{ (request()->input('filtro_tram') == '1') ? 'selected' : ''}}>Exibir todas tramitações</option>
                <option value="2" {{ (request()->input('filtro_tram') == '2') ? 'selected' : ''}}>Exibir apenas minhas tramitações criadas</option>
                <option value="3" {{ (request()->input('filtro_tram') == '3') ? 'selected' : ''}}>Exibir apenas as tramitações enviadas para mim</option>
              </select>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="filtro_ab" name="filtro_ab" value="S" {{ (request()->input('filtro_ab') == 'S' ? 'checked' : '' ) }}>
                <label class="custom-control-label" for="filtro_ab">Exibir apenas protocolos em tramitação ou abertos</label> 
              </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="filtro_n_recb" name="filtro_n_recb" value="S" {{ (request()->input('filtro_n_recb') == 'S' ? 'checked' : '' ) }}>
                <label class="custom-control-label" for="filtro_n_recb">Exibir apenas as tramitações de protocolos não recebidos</label>
              </div>
            </div>
            <div class="form-group">
                <label for="filtro_func">Tramitado para o funcionário</label>
                <input type="text" class="form-control" id="filtro_func" name="filtro_func" value="{{request()->input('filtro_func')}}">  
            </div>
            <div class="form-group">
                <label for="filtro_set">Tramitado para o setor</label>
                <input type="text" class="form-control" id="filtro_set" name="filtro_set" value="{{request()->input('filtro_set')}}">  
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Pesquisar</button>
              <a href="{{ route('tramitacoes.index') }}" class="btn btn-primary btn-sm" role="button">Limpar</a>
            </div>   
          </form>  
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
<script>
$(document).ready(function(){
    $('#perpage').on('change', function() {
        perpage = $(this).find(":selected").val(); 
        
        window.open("{{ route('tramitacoes.index') }}" + "?perpage=" + perpage,"_self");
    });

    $('#btnExportarCSV').on('click', function(){
        var filtro_name = $('input[name="codigo"]').val();
        var filtro_email = $('input[name="descricao"]').val();
        window.open("{{ route('tramitacoes.export.csv') }}" + "?codigo=" + filtro_name + "&descricao=" + filtro_email,"_self");
    });

    $('#btnExportarPDF').on('click', function(){
        var filtro_name = $('input[name="codigo"]').val();
        var filtro_email = $('input[name="descricao"]').val();
        window.open("{{ route('tramitacoes.export.pdf') }}" + "?codigo=" + filtro_name + "&descricao=" + filtro_email,"_self");
    });
}); 
</script>
@endsection