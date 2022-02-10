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
      <li class="breadcrumb-item active" aria-current="page">Novo Registro</li>
    </ol>
  </nav>
</div>
<div class="container">
  <form method="POST" action="{{ route('protocolos.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="dia">Funcionário Responsável pelo Protocolo</label>
        <input type="text" class="form-control" name="dia" value="{{ Auth::user()->name }}" readonly tabindex="-1">
      </div>
      <div class="form-group col-md-6">
        <label for="dia">Setor de Origem</label>
        <input type="text" class="form-control" name="dia" value="{{ Auth::user()->setor->descricao }}" readonly tabindex="-1">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="referencia">N° do Processo/Referência <strong class="text-warning">(Opcional)</strong></label>
        <input type="text" class="form-control{{ $errors->has('referencia') ? ' is-invalid' : '' }}" name="referencia" value="{{ old('referencia') ?? '' }}">
        @if ($errors->has('referencia'))
        <div class="invalid-feedback">
        {{ $errors->first('referencia') }}
        </div>
        @endif
      </div>

      <div class="form-group col-md-6">
        <label for="protocolo_tipo_id">Tipo do Protocolo <strong  class="text-danger">(*)</strong></label>
        <select class="form-control {{ $errors->has('protocolo_tipo_id') ? ' is-invalid' : '' }}" name="protocolo_tipo_id" id="protocolo_tipo_id">
          <option value="" selected="true">Selecione ...</option>        
          @foreach($protocolotipos as $protocolotipo)
          <option value="{{$protocolotipo->id}}" {{ old("protocolo_tipo_id") == $protocolotipo->id ? "selected":"" }}>{{$protocolotipo->descricao}}</option>
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
      <textarea class="form-control {{ $errors->has('conteudo') ? ' is-invalid' : '' }}" name="conteudo" id="conteudo" rows="5">{{ old('conteudo') ?? '' }}</textarea>
      @if ($errors->has('conteudo'))
      <div class="invalid-feedback">
      {{ $errors->first('conteudo') }}
      </div>
      @endif
    </div>
    <br>
    <div class="container bg-warning text-dark">
      <p class="text-center"><strong>Primeira Tramitação (Opcional)</strong></p>
    </div>
    <div class="form-group">
      <label for="funcionario_tramitacao">Funcionário <strong class="text-warning">(Opcional)</strong></label>
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
    <br>
    <div class="container bg-warning text-dark">
      <p class="text-center"><strong>Anexos (Opcional)</p>
    </div>

    <div class="form-group">
      <ul class="list-group">
        <li class="list-group-item">
          <label for="arquivos">Somente são aceitos os seguintes formatos para o arquivo: pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg ou rtf. Cada arquivo não pode ter mais de 10Mb. Você pode adicionar múltiplos arquivos de uma só vez. <strong class="text-warning">(Opcional)</strong></label>
          <input type="file" class="form-control-file" id="arquivos" name="arquivos[]" multiple data-show-upload="true" data-show-caption="true">
        </li>
      </ul>
      @if ($errors->has('arquivos.*'))
      <div class="alert alert-warning" role="alert">
        <b><i class="bi bi-exclamation-diamond"></i> Erro ao anexar arquivos:</b> {{ $errors->first('arquivos.*') }}
      </div>
      @endif
    </div>    


    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Incluir Novo Protocolo</button>
  </form>
</div>
<br>
<div class="container">
  <div class="float-right">
    <a href="{{ url('/') }}" class="btn btn-secondary" role="button"><i class="bi bi-clipboard-data"></i> Painel de Controle</a>
    <a href="{{ route('protocolos.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
<br>
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
                templates: {
                  empty: [
                    '<div class="empty-message">',
                      '<p class="text-center font-weight-bold text-warning">Não foi encontrado nenhum funcionário com o texto digitado.</p>',
                    '</div>'
                  ].join('\n'),
                  suggestion: function(data) {
                      return '<div class="text-dark"><div>' + data.text + ' - <strong>Setor:</strong> ' + data.setor + '</div></div>';
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

});
</script>
@endsection
