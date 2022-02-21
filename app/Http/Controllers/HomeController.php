<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\Tramitacao;

use Illuminate\Database\Eloquent\Builder;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $protocolos = new Protocolo;
        $protocolos = $protocolos->where('user_id', '=', $user->id);
        $protocolos = $protocolos->where('protocolo_situacao_id', '=', '2');
        $protocolos = $protocolos->orderBy('id', 'desc')->get();

        $minhas_tramitacoes = new Tramitacao;
        $minhas_tramitacoes = $minhas_tramitacoes->where('user_id_origem', '=', $user->id);
        $minhas_tramitacoes = $minhas_tramitacoes->whereHas('protocolo', function ($query) {
                                                $query->whereIn('protocolo_situacao_id', [1,2]);
                                            });
        $minhas_tramitacoes = $minhas_tramitacoes->where('recebido', '=', 's')->limit(5);
        $minhas_tramitacoes = $minhas_tramitacoes->orderBy('created_at', 'desc')->get();


        // tramitações enviadas ao funccionario logado ainda não recebidas
        $tramitacoes_a_receber = new Tramitacao;
        $tramitacoes_a_receber = $tramitacoes_a_receber->where('user_id_destino', '=', $user->id);
        $tramitacoes_a_receber = $tramitacoes_a_receber->whereHas('protocolo', function ($query) {
                                                $query->whereIn('protocolo_situacao_id', [1,2]);
                                            });
        $tramitacoes_a_receber = $tramitacoes_a_receber->where('recebido', '=', 'n');
        $tramitacoes_a_receber = $tramitacoes_a_receber->orderBy('created_at', 'desc')->get();

        return view('home', compact('protocolos', 'tramitacoes_a_receber', 'minhas_tramitacoes'));
    }
}
