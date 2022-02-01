@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('setores.index') }}">Lista de Setores</a></li>
      <li class="breadcrumb-item active" aria-current="page">Novo Registro</li>
    </ol>
  </nav>
</div>
<div class="container">
  <form method="POST" action="{{ route('setores.store') }}">
    @csrf
    <div class="form-row">
      <div class="form-group col-md-2">
        <label for="codigo">Código</label>
        <input type="text" class="form-control{{ $errors->has('codigo') ? ' is-invalid' : '' }}" name="codigo" value="{{ old('codigo') ?? '' }}">
        @if ($errors->has('codigo'))
        <div class="invalid-feedback">
        {{ $errors->first('codigo') }}
        </div>
        @endif
      </div>
      <div class="form-group col-md-5">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" name="descricao" value="{{ old('descricao') ?? '' }}">
        @if ($errors->has('descricao'))
        <div class="invalid-feedback">
        {{ $errors->first('descricao') }}
        </div>
        @endif
      </div>
      <div class="form-group col-md-5">
        <label for="contato">Contato</label>
        <input type="text" class="form-control{{ $errors->has('contato') ? ' is-invalid' : '' }}" name="contato" value="{{ old('contato') ?? '' }}">
        @if ($errors->has('contato'))
        <div class="invalid-feedback">
        {{ $errors->first('contato') }}
        </div>
        @endif
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Incluir Setor</button>
  </form>
</div>
<div class="container">
  <div class="float-right">
    <a href="{{ route('setores.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
@endsection
