<?php

namespace App\Http\Controllers;

use App\Http\Requests\AulasRequest;
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
    public function index(Request $request)
    {
        
        try {
            $dataInicio = $request->query('data_inicio');
            $dataFim = $request->query('data_fim');

            $aulas = DB::table('aulas')
                ->join('modalidades', 'aulas.modalidade_id', '=', 'modalidades.id')
                ->select('aulas.*', 'modalidades.nome_modalidade')
                ->where(function ($query) use ($dataInicio, $dataFim) {
                    $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                        ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
                        ->orWhere(function ($q) use ($dataInicio, $dataFim) {
                            $q->where('data_inicio', '<=', $dataInicio)
                                ->where('data_fim', '>=', $dataFim);
                        });
                })
                ->get();

            return response()->json([
                'status' => true,
                'aulas' => $aulas
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao recuperar os dados das aulas ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AulasRequest $request)
    {
        try {
            DB::beginTransaction();

            $dataInicio = Carbon::parse($request->data_inicio);
            $dataFim = Carbon::parse($request->data_fim);
            
            // Convertendo o array de dias da semana para uma string
            $diasSemana = is_array($request->dia_semana) 
                ? implode(',', $request->dia_semana) 
                : $request->dia_semana;

            $aula = Aulas::create([
                'modalidade_id' => $request->modalidade_id,
                'horario' => $request->horario,
                'dia_semana' => $diasSemana, // Agora é uma string
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'limite_alunos' => $request->limite_alunos
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'aula' => $aula,
                'message' => 'Aula criada com sucesso'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Falha em criar a aula: ' . $e->getMessage()
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
