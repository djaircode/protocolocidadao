<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\Tramitacao;
use App\Models\Anexo;
use App\Models\ProtocoloSituacao;
use App\Models\ProtocoloTipo;
use App\Models\Setor;

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

use Illuminate\Support\Str;

class ProtocoloController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $protocolotipos = ProtocoloTipo::orderBy('descricao', 'asc')->get();

        return view('protocolos.create', compact('protocolotipos'));
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
          'conteudo' => 'required',
          'protocolo_tipo_id' => 'required',       
          'arquivos.*' => 'mimes:pdf,doc,docx|max:10240',
        ],
        [
            'conteudo.required' => 'Preencha o conteúdo ou descrição do protocolo',
            'protocolo_tipo_id.required' => 'Selecione o tipo do protocolo na lista',
            'arquivos.*.mimes' => 'O arquivo anexado deve ser das seguintes extensões: pdf, doc, docx, rtf, txt, jpg, jpeg, jpg, png, bmp, xls, xlsx, csv ou xml',
            'arquivos.*.max' => 'O arquivo anexado não pode ter mais de 5MB',
        ]);

        $protocolo_input = $request->all();

        $user = Auth::user();

        $protocolo_input['user_id'] = $user->id;

        $protocolo_input['setor_id'] = $user->setor->id;

        $protocolo_input['protocolo_situacao_id'] = 1 ; //aberto
        
        $protocolo_input['concluido'] = 'n' ; // não concluido

        $protocolo = Protocolo::create($protocolo_input); //salva

        Session::flash('create_protocolo', 'Protocolo cadastrado com sucesso!');

        // caso faça a primeira tramitação
        if (isset($protocolo_input['funcionario_tramitacao_id']) && !empty($protocolo_input['funcionario_tramitacao_id'])){
            // muda a situação do protocolo
            $protocolo->protocolo_situacao_id = 2; //Em Tramitação
            $protocolo->save();

            // ajusta os dados do request
            $input_tramitacao['protocolo_id'] = $protocolo->id;
            $input_tramitacao['user_id_origem'] = $user->id;
            $input_tramitacao['setor_id_origem'] = $user->setor->id;
            $input_tramitacao['user_id_destino'] = $protocolo_input['funcionario_tramitacao_id'];
            $input_tramitacao['setor_id_destino'] = $protocolo_input['setor_tramitacao_id'];
            $input_tramitacao['recebido'] = 'n';
            $input_tramitacao['tramitado'] = 'n';
            if (isset($protocolo_input['mensagem']) && !empty($protocolo_input['mensagem'])){
                $input_tramitacao['mensagem'] = $protocolo_input['mensagem'];
            } else {
                $input_tramitacao['mensagem'] = 'Nenhuma mensagem';
            }    
            $tramitacao = Tramitacao::create($input_tramitacao); //salva            
        }

        // caso tenha arquivos faz a anexação
        if($request->hasFile('arquivos'))
        {
            $files = $request->file('arquivos');

            foreach ($files as $file) {

                $codigoAnexoPublico = Str::random(30); // código publico para o link
                $codigoAnexoSecreto = Str::random(30); // codigo secreto do anexo, nome da pasta onde será salvo o arquivo

                // guarda o nome do arquivo
                $nome_arquivo = $file->getClientOriginalName();

                // salva o arquivo
                $path = $file->storeAs($codigoAnexoSecreto, $file->getClientOriginalName(), 'public');

                $temp = new Anexo;

                $temp->protocolo_id = $protocolo->id;
                $temp->user_id =  $user->id;  
                $temp->arquivoNome =  $nome_arquivo;  
                $temp->codigoAnexoPublico =  $codigoAnexoPublico;
                $temp->codigoAnexoSecreto =  $codigoAnexoSecreto;

                $temp->save();
            }
        }

        #return redirect(route('protocolos.index'));  

        return redirect(route('protocolos.edit', $protocolo->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
