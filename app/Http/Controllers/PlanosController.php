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
            $planos = Planos::all();

            return response()->json([
                'status' => true,
                'planos' => $planos,
            ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar lista de planos: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function show ($id): JsonResponse {
        try {
            $plano = Planos::findOrFail($id);

            return response()->json([
                'status' => true,
                'plano' => $plano,
            ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar o Plano: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store (PlanosRequest $request): JsonResponse {
        try {
            DB::beginTransaction();

            // Substituir vírgula por ponto nos campos numéricos
            $valorMatricula = str_replace(',', '.', $request->valor_matricula);
            $valorMensal = str_replace(',', '.', $request->valor_mensal);
            $valorTotal = str_replace(',', '.', $request->valor_total);

            $plano = Planos::create([
                'nome_plano' => $request->nome_plano,
                'duracao'=> $request->duracao,
                'valor_matricula'=> $valorMatricula, // Usar valor formatado
                'valor_mensal'=> $valorMensal, // Usar valor formatado
                'valor_total'=> $valorTotal,
                'num_modalidades'=> $request->num_modalidades,
                'status' => $request->status,
                'number_checkins' => $request->number_checkins
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano cadastrado com Sucesso!'
            ], 201)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return response()->json([
                'status' => false,
                'message' => 'Plano não cadastrado! ' . $e->getMessage(),
            ], 400);
        }
    }

    public function update (PlanosUpdateRequest $request, $id): JsonResponse {
        try {
            DB::beginTransaction();

            $plano = Planos::findOrFail($id);

              // Substituir vírgula por ponto nos campos numéricos
              $valorMatricula = str_replace(',', '.', $request->valor_matricula);
              $valorMensal = str_replace(',', '.', $request->valor_mensal);
              $valorTotal = str_replace(',', '.', $request->valor_total);
  

            $plano->update([
                'nome_plano' => $request->nome_plano,
                'duracao'=> $request->duracao,
                'valor_matricula'=> $valorMatricula,
                'valor_mensal'=> $valorMensal,
                'valor_total'=> $valorTotal,
                'num_modalidades'=> $request->num_modalidades,
                'status' => $request->status,
                'number_checkins' => $request->number_checkins
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano Atualizado com Sucesso!'
            ], 201)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return response()->json([
                'status' => false,
                'message' => 'Plano não atualizado! ' . $e->getMessage(),
            ], 400);
        }
    }

    public function destroy ($id): JsonResponse {
        try {
            $plano = Planos::findOrFail($id);
            $plano->delete();

            return response()->json([
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano Apagado com Sucesso!'
            ], 200)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Plano não Apagado! ' . $e->getMessage(),
            ], 400);
        }
    }
}
