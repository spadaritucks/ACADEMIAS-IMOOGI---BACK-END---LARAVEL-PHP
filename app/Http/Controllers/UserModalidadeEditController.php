<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\UsuarioModalidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserModalidadeEditController extends Controller
{
    public function update(Request $request, $id)
    {


        try {
            DB::beginTransaction();

             $usuario = Usuario::findOrFail($id);
             

            $existingModalidades = UsuarioModalidades::where('usuario_id', $usuario->id)->pluck('modalidade_id')->toArray();
            $newModalidades = is_array($request->modalidade_id) ? $request->modalidade_id : [$request->modalidade_id];

            // Remover modalidades que não estão mais selecionadas
            foreach ($existingModalidades as $existingModalidade) {
                if (!in_array($existingModalidade, $newModalidades)) {
                    UsuarioModalidades::where('usuario_id', $usuario->id)
                        ->where('modalidade_id', $existingModalidade)
                        ->delete();
                }
            }

            // Adicionar ou atualizar as modalidades selecionadas
            foreach ($newModalidades as $modalidadeId) {
                UsuarioModalidades::updateOrCreate(
                    ['usuario_id' => $usuario->id, 'modalidade_id' => $modalidadeId],
                    ['modalidade_id' => $modalidadeId]
                    
                );
            }

            DB::commit();


            return response()->json([
                'status' => true,
                'message' => 'Modalidades atualizadas com sucesso!'
            ]);


          
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar a modalidade' .$e->getMessage()
            ]);
        }
    }
}
