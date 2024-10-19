<?php

namespace App\Http\Controllers;

use App\Models\Packs;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacksController extends Controller
{
    public function index()
    {
        $packs = Packs::all();
        return response()->json([
            'packs' => $packs
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Substituir vírgula por ponto nos campos numéricos
            $valorMatricula = str_replace(',', '.', $request->valor_matricula);
            $valorMensal = str_replace(',', '.', $request->valor_mensal);
            $valorTotal = str_replace(',', '.', $request->valor_total);

            $pack = Packs::create([
                'nome_plano' => $request->input('nome_plano'),
                'duracao' => $request->input('duracao'),
                'valor_matricula' => $valorMatricula, // Usar valor formatado
                'valor_mensal' => $valorMensal, // Usar valor formatado
                'valor_total' => $valorTotal, // Usar valor formatado
                'num_modalidades' => $request->input('num_modalidades'),
                'status' => $request->input('status'),
                'number_checkins_especial' => $request->input('number_checkins_especial')
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pacote criado com sucesso!',
                'pack' => $pack
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Falha ao criar o pacote! ' . $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $pack = Packs::findOrFail($id);

            // Substituir vírgula por ponto nos campos numéricos
            $valorMatricula = str_replace(',', '.', $request->valor_matricula);
            $valorMensal = str_replace(',', '.', $request->valor_mensal);
            $valorTotal = str_replace(',', '.', $request->valor_total);

            $pack->update([
                'nome_plano' => $request->input('nome_plano'),
                'duracao' => $request->input('duracao'),
                'valor_matricula' => $valorMatricula, // Usar valor formatado
                'valor_mensal' => $valorMensal, // Usar valor formatado
                'valor_total' => $valorTotal, // Usar valor formatado
                'num_modalidades' => $request->input('num_modalidades'),
                'status' => $request->input('status'),
                'number_checkins_especial' => $request->input('number_checkins_especial')
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Pacote atualizado com sucesso!',
                'pack' => $pack
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar o pacote! ' . $e->getMessage()
            ], 400);    
        }
    }   


    public function destroy($id)
    {
       try{
         DB::beginTransaction();
         $pack = Packs::findOrFail($id);
         $pack->delete();
         DB::commit();
         return response()->json([
            'message' => 'Pacote deletado com sucesso!'
        ]);
       }catch(Exception $e){
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Falha ao deletar o pacote! ' . $e->getMessage()
        ], 400);    
       }
    }
}
