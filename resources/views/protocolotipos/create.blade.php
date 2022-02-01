@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('protocolotipos.index') }}">Lista de Tipos de Protocolo</a></li>
      <li class="breadcrumb-item active" aria-current="page">Novo Registro</li>
    </ol>
  </nav>
</div>
<div class="container">
  <form method="POST" action="{{ route('protocolotipos.store') }}">
    @csrf
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" name="descricao" value="{{ old('descricao') ?? '' }}">
        @if ($errors->has('descricao'))
        <div class="invalid-feedback">
        {{ $errors->first('descricao') }}
        </div>
        @endif
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Incluir Tipo de Protocolo</button>
  </form>
</div>
<div class="container">
  <div class="float-right">
    <a href="{{ route('protocolotipos.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
@endsection
