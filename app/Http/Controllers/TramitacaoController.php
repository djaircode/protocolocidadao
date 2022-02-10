<?php

namespace App\Http\Controllers;

use App\Models\Tramitacao;
use App\Models\Protocolo;
use App\Models\Anexo;


use App\Models\Perpage;

use Response;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect; // para poder usar o redirect

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Builder; // para poder usar o whereHas nos filtros

use Auth; // receber o id do operador logado no sistema

use Carbon\Carbon; // tratamento de datas

class TramitacaoController extends Controller
{
    protected $pdf;

    /**
     * Construtor.
     *
     * precisa estar logado ao sistema
     * precisa ter a conta ativa (access)
     *
     * @return 
     */
    public function __construct(\App\Reports\TemplateReport $pdf)
    {
        $this->middleware(['middleware' => 'auth']);
        $this->middleware(['middleware' => 'hasaccess']);

        $this->pdf = $pdf;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tramitacoes = new Tramitacao;

        $user = Auth::user();

        // filtros
        ## $filtro_tram = tramitações feitas para o
        if (request()->has('filtro_tram')){
             if (request('filtro_tram') != ""){
                $filtro_tram = request('filtro_tram');
             } else {
                $filtro_tram = '1';
             }
        } else {
            $filtro_tram = '1';
        }


        // acessar todos protocolos do usuário
        if ($filtro_tram == '1') {
            $tramitacoes = $tramitacoes->where('user_id_destino', '=', $user->id)
                                       ->orWhere('user_id_origem', '=', $user->id);
        }

        // acessar todos protocolos criados (de origem)do usuário
        if ($filtro_tram == '2') {
            $tramitacoes = $tramitacoes->where('user_id_origem', '=', $user->id);
        }

        // acessar todos protocolos tramitatos para o usuário usuário
        if ($filtro_tram == '3') {
            $tramitacoes = $tramitacoes->where('user_id_destino', '=', $user->id);
        }

        ## $filtro_ab = filtra protocolos que estão em tramitação ou abertos
        if (request()->has('filtro_ab')){
             if (request('filtro_ab') != ""){
                $filtro_ab = 'S';
             } else {
                $filtro_ab = 'N';
             }
        } else {
            $filtro_ab = 'N';
        }

        if ($filtro_ab == 'S'){
            $tramitacoes = $tramitacoes->whereHas('protocolo', function ($query) {
                                                $query->whereIn('protocolo_situacao_id', [1,2]);
                                            });
        }

        ## $filtro_n_recb = filtra protocolos que não foram recebidos
        if (request()->has('filtro_n_recb')){
             if (request('filtro_n_recb') != ""){
                $filtro_n_recb = 'S';
             } else {
                $filtro_n_recb = 'N';
             }
        } else {
            $filtro_n_recb = 'N';
        }

        if ($filtro_n_recb == 'S'){
            $tramitacoes = $tramitacoes->where('recebido', '=', 'n');
        }

        if (request()->has('filtro_func')){
            if (request('filtro_func') != ""){
                $tramitacoes = $tramitacoes->whereHas('userDestino', function ($query) {
                                                    $query->where('name', 'like', '%' . request('filtro_func') . '%');
                                                });
            }
        }

        if (request()->has('filtro_set')){
            if (request('filtro_set') != ""){
                $tramitacoes = $tramitacoes->whereHas('setorDestino', function ($query) {
                                                    $query->where('descricao', 'like', '%' . request('filtro_set') . '%');
                                                });
            }
        }


        // ordena
        $tramitacoes = $tramitacoes->orderBy('created_at', 'desc');

        // se a requisição tiver um novo valor para a quantidade
        // de páginas por visualização ele altera aqui
        if(request()->has('perpage')) {
            session(['perPage' => request('perpage')]);
        }

        // consulta a tabela perpage para ter a lista de
        // quantidades de paginação
        $perpages = Perpage::orderBy('valor')->get();

        // paginação
        $tramitacoes = $tramitacoes->paginate(session('perPage', '5'))->appends([
            'filtro_tram' => request('filtro_tram'),
            'filtro_ab' => request('filtro_ab'),                   
            'filtro_n_recb' => request('filtro_n_recb'),                   
            'filtro_func' => request('filtro_func'),                   
            'filtro_set' => request('filtro_set'),                   
            ]);

        return view('tramitacoes.index', compact('tramitacoes', 'perpages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
          'funcionario_tramitacao_id' => 'required',
          'setor_tramitacao_id' => 'required',
          'protocolo_id' => 'required',
        ],
        [
            'funcionario_tramitacao_id.required' => 'Escolha o funcionário para tramitação',
            'setor_tramitacao_id.required' => 'Escolha o funcionário e o setor para tramitação',
            'protocolo_id.required' => 'Erro no sistema, protocolo não selecionado para tramitação',
        ]);

        // recebe os dados do request
        $input_tramitacao = $request->all();

        // atualiza o protocolo como em tramitação
        $user = Auth::user();
        $protocolo = Protocolo::findOrFail($input_tramitacao['protocolo_id']);
        $protocolo->protocolo_situacao_id = 2; //Em Tramitação
        $protocolo->save();

        // ajusta os dados do request
        $input_tramitacao['user_id_origem'] = $user->id;
        $input_tramitacao['setor_id_origem'] = $user->setor->id;
        $input_tramitacao['user_id_destino'] = $input_tramitacao['funcionario_tramitacao_id'];
        $input_tramitacao['setor_id_destino'] = $input_tramitacao['setor_tramitacao_id'];
        $input_tramitacao['recebido'] = 'n';
        $input_tramitacao['tramitado'] = 'n';
        $tramitacao = Tramitacao::create($input_tramitacao); //salva

        // controle de acesso ao protocolo
        $tramitacao->userDestino->protocolos()->detach($protocolo); // remove o acesso se tiver
        $tramitacao->userDestino->protocolos()->attach($protocolo); // refaz o acesso

        // dar acesso a todos os anexos ao usuário tramitado, caso ele não tenha ainda acesso
        // remove todos acessos do usario destino, para refazer todos acessos
        $tramitacao->userDestino->anexos()->detach();
        // refaz os acessos ao anexo
        $anexos = $protocolo->anexos()->get();
        foreach ($anexos as $anexo) {
            $tramitacao->userDestino->anexos()->attach($anexo);    
        }

        // atualiza oa tramitacao de origem
        $tramitacaoOrigem = Tramitacao::findOrFail($input_tramitacao['tramitacao_id']);
        $tramitacaoOrigem->tramitado = 's';
        //$tramitacaoOrigem->tramitado_em = Carbon::now()->format('Y-m-d H:i:s');
        $tramitacaoOrigem->tramitado_em = Carbon::now()->toDateTimeString();
        $tramitacaoOrigem->save();

        Session::flash('create_tramitacao', "Tramitação para o funcionário {$tramitacao->userDestino->name} foi realizada com sucesso!");

        $novaTramitacaoID = $tramitacao->id;
        
        Session::flash('novaTramitacaoID', $novaTramitacaoID);

        return Redirect::route('tramitacoes.edit', $input_tramitacao['tramitacao_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tramitacao = Tramitacao::findOrFail($id);

        $protocolo = Protocolo::findOrFail($tramitacao->protocolo->id);

        $anexos = $protocolo->anexos()->orderBy('id', 'desc')->get();

        return view('tramitacoes.show', compact('tramitacao', 'protocolo', 'anexos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tramitacao = Tramitacao::findOrFail($id);

        $protocolo = Protocolo::findOrFail($tramitacao->protocolo->id);

        $anexos = $protocolo->anexos()->orderBy('id', 'desc')->get();

        return view('tramitacoes.edit', compact('tramitacao', 'protocolo', 'anexos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tramitacao = Tramitacao::findOrFail($id);

        $input = $request->all();

        $tramitacao->recebido = 's';
        if (isset($input['concluido_mensagem']) && !empty($input['concluido_mensagem'])){
                $mensagem = $input['concluido_mensagem'];
            } else {
                $mensagem = 'Nenhuma mensagem de recebimento';
            } 
        $tramitacao->mensagemRecebido = $mensagem;
        $tramitacao->recebido_em =  Carbon::now()->toDateTimeString();
        $tramitacao->save();

        Session::flash('recebe_tramitacao', 'Tramitação recebida com sucesso!');

        return Redirect::route('tramitacoes.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
