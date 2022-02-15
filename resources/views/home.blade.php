@extends('layouts.app')

@section('content')
<div class="container-fluid p-3 mb-2 bg-secondary text-light text-center text-uppercase">
      <h4><i class="bi bi-folder2-open"></i> Meus Protocolos Em Tramitação</h4>
</div>
<div class="container">
  @if ( !$protocolos->isEmpty() ) 
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nº</th>
                <th scope="col">Data</th>
                <th scope="col"></th>
                <th scope="col">N° do Processo/Referência</th>
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
  <div class="container p-2">
    <p class="text-center">Você possui {{ $protocolos->count() }} protocolo(s) em tramitação</p>    
  </div>
  @else
    <p class="p-3 text-center">Nenhum Protocolo em Tramitação</p>
  @endif
  <div class="container py-2 text-center"> 
    <a href="{{ route('protocolos.create') }}" class="btn btn-primary btn-lg" role="button"><i class="bi bi-plus-circle"></i> Criar Novo Protocolo</a>
  </div>  
</div>

<div class="container-fluid p-3 mb-2 bg-secondary text-light text-center text-uppercase">
      <h4><i class="bi bi-hand-thumbs-up"></i> Minhas Tramitações a Receber</h4>
</div>
<div class="container">
  @if ( !$tramitacoes_a_receber->isEmpty() ) 
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
                <th scope="col">Recebido</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($tramitacoes_a_receber as $tramitacao_a_receber)
            <tr>
                <td>
                  {{$tramitacao_a_receber->created_at->format('d/m/Y')}}
                </td>
                <td>
                  {{$tramitacao_a_receber->created_at->diffForHumans()}}
                </td>
                <td class="text-right">
                  <strong>{{$tramitacao_a_receber->protocolo->id}}</strong>
                </td>

                @switch($tramitacao_a_receber->protocolo->protocoloSituacao->id)
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
                  @if (Auth::user()->id == $tramitacao_a_receber->userDestino->id)
                  <strong class="text-warning"><i class="fas fa-hand-point-right"></i> {{$tramitacao_a_receber->userDestino->name}} </strong>
                  @else
                    <i class="fas fa-hand-point-right"></i> {{$tramitacao_a_receber->userOrigem->name}}
                  @endif
                </td>
                <td>
                  {{$tramitacao_a_receber->setorOrigem->descricao}}
                </td>
                @if($tramitacao_a_receber->recebido == 's')
                <td>
                  {{$tramitacao_a_receber->recebido_em->format('d/m/Y')}}
                </td>
                @else
                <td>
                  <h5><span class="badge badge-danger">Não Recebido</span></h5>
                </td>
                @endif
                <td>
                  @if (Auth::user()->id == $tramitacao_a_receber->userDestino->id)
                  <div class="btn-group" role="group">
                    <a href="{{route('tramitacoes.edit', $tramitacao_a_receber->id)}}" class="btn btn-info btn-sm" role="button"><i class="bi bi-pencil-square"></i></a>
                  </div>
                  @endif
                  @if (Auth::user()->id == $tramitacao_a_receber->userOrigem->id)
                  <div class="btn-group" role="group">
                    <a href="{{route('tramitacoes.show', $tramitacao_a_receber->id)}}" class="btn btn-primary btn-sm" role="button"><i class="bi bi-eye"></i></a>
                  </div>
                  @endif
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <div class="container p-2">
    <p class="text-center">Você possui {{ $tramitacoes_a_receber->count() }} tramitações(ão) de protocolo(s) para receber</p>    
  </div>
  @else
    <p class="p-3 text-center">Você não possui nenhuma tramitação de protocolo para receber</p>
  @endif
</div>

<div class="container-fluid p-3 mb-2 bg-secondary text-light text-center text-uppercase">
      <h4><i class="bi bi-hand-index"></i> Minhas Tramitações Criadas</h4>
</div>
<div class="container">
  @if ( !$minhas_tramitacoes->isEmpty() ) 
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Data</th>
                <th></th>
                <th scope="col" class="text-right">N° Prot.</th>
                <th scope="col">Situação</th>
                <th scope="col">Tramitado Para</th>
                <th scope="col">No Setor</th>
                <th scope="col">Recebido</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($minhas_tramitacoes as $minha_tramitacao)
            <tr>
                <td>
                  {{$minha_tramitacao->created_at->format('d/m/Y')}}
                </td>
                <td>
                  {{$minha_tramitacao->created_at->diffForHumans()}}
                </td>
                <td class="text-right">
                  <strong>{{$minha_tramitacao->protocolo->id}}</strong>
                </td>

                @switch($minha_tramitacao->protocolo->protocoloSituacao->id)
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
                  @if (Auth::user()->id == $minha_tramitacao->userDestino->id)
                  <strong class="text-warning"><i class="fas fa-hand-point-right"></i> {{$minha_tramitacao->userDestino->name}} </strong>
                  @else
                    <i class="fas fa-hand-point-right"></i> {{$minha_tramitacao->userDestino->name}}
                  @endif
                </td>
                <td>
                  {{$minha_tramitacao->setorDestino->descricao}}
                </td>
                @if($minha_tramitacao->recebido == 's')
                <td>
                  {{$minha_tramitacao->recebido_em->format('d/m/Y')}}
                </td>
                @else
                <td>
                  <h5><span class="badge badge-danger">Não Recebido</span></h5>
                </td>
                @endif
                <td>
                  @if (Auth::user()->id == $minha_tramitacao->userDestino->id)
                  <div class="btn-group" role="group">
                    <a href="{{route('tramitacoes.edit', $minha_tramitacao->id)}}" class="btn btn-info btn-sm" role="button"><i class="bi bi-pencil-square"></i></a>
                  </div>
                  @endif
                  @if (Auth::user()->id == $minha_tramitacao->userOrigem->id)
                  <div class="btn-group" role="group">
                    <a href="{{route('tramitacoes.show', $minha_tramitacao->id)}}" class="btn btn-primary btn-sm" role="button"><i class="bi bi-eye"></i></a>
                  </div>
                  @endif
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <div class="container p-2">
    <p class="text-center">Você está esperando {{ $minhas_tramitacoes->count() }} tramitações(ão) de protocolo(s) para ser(em) recebida(s)</p>    
  </div>
  @else
    <p class="p-3 text-center">Você não possui nenhuma tramitação de protocolo para ser recebida</p>
  @endif
</div>
<div class="container-fluid p-3 mb-2 bg-secondary text-light text-center text-uppercase">
      <h4><i class="bi bi-clipboard-data"></i> Minhas Métricas</h4>
</div>

  <div class="container p-3 text-center"> 
    <a href="{{ route('funcionario.metricas.index') }}" class="btn btn-primary btn-lg" role="button"><i class="bi bi-clipboard-data"></i> Minhas Métricas</a>
    <a href="{{ route('setor.metricas.index') }}" class="btn btn-primary btn-lg" role="button"><i class="bi bi-clipboard-data"></i> Métricas do Meu Setor</a>
  </div> 

@endsection
