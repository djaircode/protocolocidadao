@extends('layouts.app')

@section('content')
<div class="container-fluid p-3 mb-2 bg-secondary text-light text-center text-uppercase">
      <h4><i class="bi bi-clipboard-data"></i> Métricas do Setor {{ Auth::user()->setor->descricao }}</h4>
</div>


<div class="container">
  <div class="container bg-primary text-light">
      <h3 class="text-center"><strong>Meus Protocolos</strong></h3>
  </div>
</div>

<div class="container p-2">
  <div class="row justify-content-md-center">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Total de Protocolos</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['total_de_protocolos'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Protocolos em Tramitação</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['protocolos_em_tramitacao'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Protocolos Concluídos</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['protocolos_concluidos'] }}</h1>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container p-2">  
  <div class="row justify-content-md-center">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Protocolos Abertos</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['protocolos_abertos'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Protocolos Cancelados</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['protocolos_cancelados'] }}</h1>
        </div>
      </div>
    </div>
  </div>  
</div>

<div class="container">
  <div class="container bg-primary text-light">
      <h3 class="text-center"><strong>Minhas Tramitações a Receber</strong></h3>
  </div>
</div>
<div class="container p-2">
  <div class="row justify-content-md-center">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Total de Tramitações</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_receber_total'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Tramitações Recebidas</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_receber_recebidas'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Tramitações Não Recebidas</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_receber_nao_recebidas'] }}</h1>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="container bg-primary text-light">
      <h3 class="text-center"><strong>Minhas Tramitações Criadas</strong></h3>
  </div>
</div>
<div class="container p-2">
  <div class="row justify-content-md-center">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Total de Tramitações</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_criadas_total'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Tramitações Recebidas</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_criadas_recebidas'] }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Tramitações Não Recebidas</h4>  
        </div>
        <div class="card-body text-center">
          <h1>{{ $metricas['tram_criadas_nao_recebidas'] }}</h1>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
