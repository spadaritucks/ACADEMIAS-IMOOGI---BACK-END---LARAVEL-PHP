<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanosRequest;
use App\Http\Requests\PlanosUpdateRequest;
use App\Models\Planos;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanosController extends Controller
{
    public function index (): JsonResponse {
        try {
            $planos = Planos::all(); // Recupera todos os usuários cadastrados

            return response()->json([
                'status' => true,
                'planos' => $planos,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar lista de planos: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function show ($id){
           try {
            $plano = Planos::findOrFail($id); // Busca o usuário pelo ID

            return response()->json([
                'status' => true,
                'plano' => $plano,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar o Plano: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store (PlanosRequest $request){
        
        try{
            DB::beginTransaction();

            $plano = Planos::create([
                'nome_plano' => $request->nome_plano,
                'duracao'=> $request->duracao,
                'valor_matricula'=> $request->valor_matricula,
                'valor_mensal'=> $request->valor_mensal,
                'valor_total'=> $request->valor_total,
                'num_modalidades'=> $request->num_modalidades,
                'status' => $request->status
            ]);
    


            DB::commit();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano cadastrado com Sucesso!'

            ], 201);

        }catch(Exception $e){
            
            return response()->json([
                'status' => false,
                'message' => 'Plano não cadastrado!' . $e->getMessage(),

            ], 400);
        }
    }

    public function update (PlanosUpdateRequest $request, $id){
        try{
            DB::beginTransaction();

            $plano = Planos::findOrFail($id);

            $plano->update([
                'nome_plano' => $request->nome_plano,
                'duracao'=> $request->duracao,
                'valor_matricula'=> $request->valor_matricula,
                'valor_mensal'=> $request->valor_mensal,
                'valor_total'=> $request->valor_total,
                'num_modalidades'=> $request->num_modalidades,
                'status' => $request->status
            ]);
    


            DB::commit();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano Atualizado com Sucesso!'

            ], 201);

        }catch(Exception $e){
            
            return response()->json([
                'status' => false,
                'message' => 'Plano não atualizado!' . $e->getMessage(),

            ], 400);
        }
    }

    public function destroy ($id){
        try {

            $plano = Planos::findOrFail($id);
            $plano->delete();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano Apagado com Sucesso!'

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Plano não Apagado!' . $e->getMessage()

            ], 400);
        }
    }
}
