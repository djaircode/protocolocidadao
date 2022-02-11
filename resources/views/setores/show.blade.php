@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('setores.index') }}">Lista de Setores</a></li>
      <li class="breadcrumb-item active" aria-current="page">Exibir Registro</li>
    </ol>
  </nav>
</div>

<div class="container">
  <div class="card">
    <div class="card-header">
      Setores
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Código: {{$setor->codigo}}</li>
        <li class="list-group-item">Descrição: {{$setor->descricao}}</li>
        <li class="list-group-item">Contato: {{$setor->contato}}</li>
      </ul>
    </div>
    <div class="card-footer text-right">
      @can('setor-delete', Auth::user())
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalLixeira"><i class="bi bi-trash"></i> Enviar para Lixeira</button>
      @endcan
      <a href="{{ route('setores.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>      
    </div>
  </div>
</div>    

<br>

<div class="modal fade" id="modalLixeira" tabindex="-1" role="dialog" aria-labelledby="JanelaProfissional" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-patch-question"></i> Apagar Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          <p><strong>Atenção!</strong> Ao excluir esse registro todo e qualquer vínculo que ele tiver com outros dados será excluído.</p>
          <h2>Confirma?</h2>
        </div>
        <form method="post" action="{{route('setores.destroy', $setor->id)}}">
          @csrf
          @method('DELETE')
          <div class="form-group">
            <label for="motivo">Motivo</label>  
            <input type="text" class="form-control" name="motivo" id="motivo" value="">
          </div>
          <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Apagar Registro</button>
        </form>
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
      </div>
    </div>
  </div>
</div>


@endsection
