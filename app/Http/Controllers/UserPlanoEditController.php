<?php

namespace App\Http\Controllers;

use App\Models\Contratos;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPlanoEditController extends Controller
{
    public function update (Request $request,$id) {

        try {
            DB::beginTransaction();

             $usuario = Usuario::findOrFail($id);
             

            $contrato = Contratos::where('usuario_id', $usuario->id);

            $contrato->update([
                'usuario_id' => $usuario->id,
                'planos_id' => $request->planos_id
            ]);

            DB::commit();


            return response()->json([
                'status' => true,
                'message' => 'Plano atualizado com sucesso!'
            ]);


          
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar o plano' .$e->getMessage()
            ]);
        }
 
    }
}
