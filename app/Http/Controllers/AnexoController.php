<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\Anexo;

use App\Models\Perpage;

use Response;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon; // tratamento de datas
use Illuminate\Support\Facades\Redirect; // para poder usar o redirect

use Illuminate\Support\Facades\Storage;

use Auth; // receber o id do operador logado no sistema

use Illuminate\Support\Str;

class AnexoController extends Controller
{
    /**
     * Construtor.
     *
     * precisa estar logado ao sistema
     * precisa ter a conta ativa (access)
     *
     * @return 
     */
    public function __construct()
    {
        $this->middleware(['middleware' => 'auth']);
        $this->middleware(['middleware' => 'hasaccess']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anexos = new Anexo;

        $user = Auth::user();

        if(request()->has('perpage')) {
            session(['perPage' => request('perpage')]);
        }
        
        $lista_anexos_com_acesso = $user->anexos()->pluck('id')->toArray();
        $anexos = $anexos->whereIn('id', $lista_anexos_com_acesso);
        $anexos = $anexos->orderBy('created_at', 'asc');
        $anexos = $anexos->paginate(session('perPage', '5'));
        $perpages = Perpage::orderBy('valor')->get();

        return view('anexos.index', compact('anexos', 'perpages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404, 'Não Existe.');
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
          'arquivos.*' => 'required|file|mimes:pdf,doc,docx,rtf,jpg,jpeg,png,xls,xlsx,ppt,pptx|max:10240',
        ],
        [
            'arquivos.*.required' => 'Selecione o(s) arquivo(s) a ser(em) anexado(s)',
            'arquivos.*.mimes' => 'O arquivo anexado deve ser das seguintes extensões: pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg ou rtf',
            'arquivos.*.max' => 'O arquivo anexado não pode ter mais de 5MB',
        ]);



        if($request->hasFile('arquivos'))
        {
            $files = $request->file('arquivos');

            // guarda os dados do usuário logado no sistema
            $user = Auth::user(); 

            //guarda os dados do protocolo
            $protocolo = Protocolo::findOrFail($request['protocolo_id']);          

            foreach ($files as $file) {

                // esses codigos precisam ser únicos dentro da tabela                
                $codigoAnexoPublico = Str::random(30); // código publico para o link
                while (!is_null(Anexo::where('codigoAnexoPublico', '=', $codigoAnexoPublico)->first())) {
                    $codigoAnexoPublico = Str::random(30);
                }
                $codigoAnexoSecreto = Str::random(30); // codigo secreto do anexo, nome da pasta onde será salvo o arquivo
                while (!is_null(Anexo::where('codigoAnexoSecreto', '=', $codigoAnexoSecreto)->first())) {
                    $codigoAnexoSecreto = Str::random(30);
                }

                // guarda o nome do arquivo
                $nome_arquivo = $file->getClientOriginalName();

                // salva o arquivo
                $path = $file->storeAs($codigoAnexoSecreto, $file->getClientOriginalName(), 'public');

                $anexo = new Anexo;

                $anexo->protocolo_id = $protocolo->id;
                $anexo->user_id =  $user->id;  
                $anexo->arquivoNome =  $nome_arquivo; 
                $anexo->codigoAnexoPublico =  $codigoAnexoPublico;
                $anexo->codigoAnexoSecreto =  $codigoAnexoSecreto;

                $anexo->save();

                // acesso do arquivo a todos usuarios que tem acesso ao protocolo, incluindo o proprietario do protocolo
                $users_acesso = $protocolo->users()->get();

                foreach ($users_acesso as $user_acesso) {
                    $user_acesso->anexos()->attach($anexo);
                }
            }
        }


        Session::flash('create_anexo', 'Anexo salvo com sucesso!');


        if (request()->has('tramitacao_id')){
            return Redirect::route('tramitacoes.edit', $request['tramitacao_id']);
        } else {
            return Redirect::route('protocolos.edit', $request['protocolo_id']);    
        }    

                
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anexo = Anexo::findOrFail($id);

        return view('anexos.show', compact('anexo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404, 'Não Existe.');
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
        abort(404, 'Não Existe.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        // guarda os dados do usuário logado no sistema
        $user = Auth::user();

        $anexo = Anexo::findOrFail($id);

        // guarda o numero do protocolo
        $num_protocolo = $anexo->protocolo->id;

        //verifica se o usuário logado é dono do arquivo
        if ($anexo->user->id != $user->id) {
            abort(403, 'Acesso negado.');
        }

        // apaga o arquivo e pasta junto
        // problema se tiver dois arquivos usando a mesma pasta
        Storage::deleteDirectory('public/' . $anexo->codigoAnexoSecreto);

        // apaga todos acessos
        $anexo->users()->detach();

        $anexo->delete();

        Session::flash('delete_anexo', 'Anexo excluído com sucesso!');

        return Redirect::route('protocolos.edit', $num_protocolo);

    }

    public function download($codigoanexo)
    {
        $anexo = Anexo::where('codigoAnexoPublico', $codigoanexo)->firstOrFail();

        // guarda os dados do usuário logado no sistema
        $user = Auth::user();

        if (!$anexo->users()->find($user->id)){
            abort(403, 'Acesso negado.');    
        }

        return response()->download('storage/' . $anexo->codigoAnexoSecreto . '/' . $anexo->arquivoNome);
    }    
}
