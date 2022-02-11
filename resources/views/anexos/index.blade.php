@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('anexos.index') }}">Lista de Arquivos Anexados</a></li>
    </ol>
  </nav>
  <div class="btn-group py-1" role="group" aria-label="Opções">
    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFilter"><i class="bi bi-funnel"></i> Filtrar</button>
  </div>
    @if ( !$anexos->isEmpty() ) 
    <div class="table-responsive">
      <table class="table table-striped">
          <thead>
              <tr>
                  <th scope="col">Data</th>
                  <th scope="col">Hora</th>
                  <th scope="col">Arquivo</th>
                  <th scope="col">Enviado por</th>
                  <th scope="col">Setor</th>
                  <th scope="col">N° Protocolo</th>
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
                  <td>{{ $anexo->user->setor->descricao }}</td>
                  <td>{{ $anexo->protocolo->id }}</td>
                  <td>
                    <a href="{{route('anexos.show', $anexo->id)}}" class="btn btn-primary btn-sm" role="button"><i class="bi bi-eye"></i></a>
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
  <p class="text-center">Página {{ $anexos->currentPage() }} de {{ $anexos->lastPage() }}. Total de registros: {{ $anexos->total() }}.</p>
  <div class="container-fluid">
      {{ $anexos->links() }}
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i></i> Fechar</button>
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
        
        window.open("{{ route('anexos.index') }}" + "?perpage=" + perpage,"_self");
    });
}); 
</script>
@endsection