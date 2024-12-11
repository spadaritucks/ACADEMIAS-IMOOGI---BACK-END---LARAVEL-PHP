<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\UsuariosPacks;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPackEditController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);
             
            $existingPacks = UsuariosPacks::where('usuario_id', $usuario->id)->pluck('packs_id')->toArray();
            $newPacks = is_array($request->packs_id) ? $request->packs_id : [$request->packs_id];

            // Remover packs que não estão mais selecionados
            foreach ($existingPacks as $existingPack) {
                if (!in_array($existingPack, $newPacks)) {
                    UsuariosPacks::where('usuario_id', $usuario->id)
                        ->where('packs_id', $existingPack)
                        ->delete();
                }
            }

            // Adicionar ou atualizar os packs selecionados
            foreach ($newPacks as $packId) {
                UsuariosPacks::updateOrCreate(
                    ['usuario_id' => $usuario->id, 'packs_id' => $packId],
                    ['packs_id' => $packId]
                );
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Packs atualizadas com sucesso!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar os Packs: ' . $e->getMessage()
            ]);
        }
    }
}
