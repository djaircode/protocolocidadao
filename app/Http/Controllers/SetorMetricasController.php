<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\Tramitacao;

use Illuminate\Database\Eloquent\Builder;

use Auth;

class SetorMetricasController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['middleware' => 'hasaccess']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $setor_id = $user->setor->id;

        $metricas['total_de_protocolos'] = Protocolo::with('user')->where('setor_id', '=', $setor_id)->count();
        $metricas['protocolos_em_tramitacao'] = Protocolo::with('user')->where('setor_id', '=', $setor_id)->where('protocolo_situacao_id', '=', '2')->count();
        $metricas['protocolos_concluidos'] = Protocolo::with('user')->where('setor_id', '=', $setor_id)->where('protocolo_situacao_id', '=', '3')->count();
        $metricas['protocolos_abertos'] = Protocolo::with('user')->where('setor_id', '=', $setor_id)->where('protocolo_situacao_id', '=', '1')->count();
        $metricas['protocolos_cancelados'] = Protocolo::with('user')->where('setor_id', '=', $setor_id)->where('protocolo_situacao_id', '=', '4')->count();

        $metricas['tram_receber_total'] = Tramitacao::where('setor_id_destino', '=', $setor_id)->count();
        $metricas['tram_receber_recebidas'] = Tramitacao::where('setor_id_destino', '=', $setor_id)->where('recebido', '=', 's')->count();
        $metricas['tram_receber_nao_recebidas'] = Tramitacao::where('setor_id_destino', '=', $setor_id)->where('recebido', '=', 'n')->count();

        $metricas['tram_criadas_total'] = Tramitacao::where('setor_id_origem', '=', $setor_id)->count();
        $metricas['tram_criadas_recebidas'] = Tramitacao::where('setor_id_origem', '=', $setor_id)->where('recebido', '=', 's')->count();
        $metricas['tram_criadas_nao_recebidas'] = Tramitacao::where('setor_id_origem', '=', $setor_id)->where('recebido', '=', 'n')->count();

        return view('metricas.setor.index', compact('metricas'));
    }
}
