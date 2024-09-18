<?php

namespace App\Http\Controllers;

use App\Models\Packs;
use Exception;
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

    public function store(Request $request)
    {
       try{
        DB::beginTransaction();

        $pack = Packs::create([
            'nome_plano' => $request->input('nome_plano'),
            'duracao' => $request->input('duracao'),
            'valor_matricula' => $request->input('valor_matricula'),
            'valor_mensal' => $request->input('valor_mensal'),
            'valor_total' => $request->input('valor_total'),
            'num_modalidades' => $request->input('num_modalidades'),
            'status' => $request->input('status'),
            'number_checkins_especial' => $request->input('number_checkins_especial')
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Pacote criado com sucesso!',
            'pack' => $pack
        ]);
       }catch(Exception $e){
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Falha ao criar o pacote! ' . $e->getMessage()
        ], 400);
       }
    }

    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $pack = Packs::findOrFail($id);
            $pack->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Pacote atualizado com sucesso!',
                'pack' => $pack
            ]);
        }catch(Exception $e){
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

