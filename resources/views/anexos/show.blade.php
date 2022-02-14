@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('anexos.index') }}">Lista de Arquivos Anexados</a></li>
      <li class="breadcrumb-item active" aria-current="page">Exibir Registro</li>
    </ol>
  </nav>
</div>

<div class="container">
  <div class="card">
    <div class="card-header">
      Arquivo em Anexo
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Arquivo: <a href="{{ route('anexos.download', $anexo->codigoAnexoPublico) }}">{{ $anexo->arquivoNome }}</a></li>
        <li class="list-group-item">N° Protocolo: {{ $anexo->protocolo->id }}</li>
        <li class="list-group-item">Enviado por: {{ $anexo->user->name }}</li>
        <li class="list-group-item">Setor: {{ $anexo->user->setor->descricao }}</li>
        <li class="list-group-item">Data/Hora: {{ $anexo->created_at->format('d/m/Y H:i')  }}</li>
      </ul>
      <div class="container">
        <div class="container bg-primary text-light">
          <p class="text-center"><strong>Funcionário(s) com acesso a esse arquivo</strong></p>
        </div>
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
            @foreach($anexo->users as $user)
                    <tr>
                        <td>{{ $user->name  }}</td>
                        <td>{{ $user->setor->descricao }}</td>
                    </tr>    
                    @endforeach 
          </tbody>
      </table>
      </div>  
    </div>
    <div class="card-footer text-right">
      <a href="{{ route('anexos.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>      
    </div>
  </div>
</div>    

<br>


@endsection
