<?php

namespace App\Http\Controllers;

use App\Models\Aulas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AulasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

           $aulas =  DB::table('aulas')->join('modalidades', 'aulas.modalidade_id', '=' , 'modalidades.id')
           ->select('aulas.*', 'modalidades.nome_modalidade')->get();

           return response()->json([
            'status' => true,
            'aulas' => $aulas
           ], 200);

        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Falha ao recuperar os dados das aulas ' .$e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {

       
      try{
        
        DB::beginTransaction();

        $aula = Aulas::create([
            'modalidade_id' => $request->modalidade_id,
            'horario' => $request->horario,
            'dia_semana' => $request->dia_semana,
            'limite_alunos' => $request->limite_alunos
        ]);


        DB::commit();

        return response()->json([
            'status' => true,
            'aula' => $aula,
            'message' => 'Aula criada com sucesso'
        ],201);

      }catch(Exception $e){

        return response()->json([
            'status' => false,
            'message' => 'Falha em criar a aula ' .$e->getMessage() 
        ], 400);

      }
    }

    public function destroy($id)
    {
        try{


          $aula =  Aulas::findOrFail($id);
          $aula->delete();
            


            return response ()->json([
                'status' => true,
                'message' => "Exclusão feita com sucesso"
            ]);

        }catch(Exception $e){
            return response ()->json([
                'status' => false,
                'message' => 'Falha ao excluir a aula' .$e->getMessage()
            ]);
        }
    }
}
