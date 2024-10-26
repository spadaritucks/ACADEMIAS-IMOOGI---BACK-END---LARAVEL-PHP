<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModalidadesRequest;
use App\Http\Requests\ModalidadesUpdateRequest;
use App\Models\Modalidades;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModalidadesController extends Controller
{
    public function index (): JsonResponse {
        try {
            $modalidades = Modalidades::all(); // Recupera todos os usuários cadastrados

            return response()->json([
                'status' => true,
                'modalidades' => $modalidades,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar lista de modalidades: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function show ($id){
           try {
            $modalidade = Modalidades::findOrFail($id); // Busca o usuário pelo ID

            return response()->json([
                'status' => true,
                'modalidade' => $modalidade,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar a Modalidade: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store (ModalidadesRequest $request){
        
        try{
            DB::beginTransaction();

            $foto_modalidade = null;
            if($request->hasFile('foto_modalidade')){
                $file = $request->file('foto_modalidade');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads',$file,$fileName);
                $foto_modalidade = $filePath;
            }

            $modalidade = Modalidades::create([
                'foto_modalidade' => $foto_modalidade,
                'nome_modalidade'=> $request->nome_modalidade,
                'descricao_modalidade' => $request->descricao_modalidade,
            ]);
    


            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $modalidade,
                'message' => 'Modalidade cadastrada com Sucesso!'

            ], 201);

        }catch(Exception $e){
            
            return response()->json([
                'status' => false,
                'message' => 'Modalidade não cadastrado!' . $e->getMessage(),

            ], 400);
        }
    }

    public function update (ModalidadesRequest $request, $id){

        try{

            DB::beginTransaction();

            $modalidade = Modalidades::findOrFail($id);

            $foto_modalidade = null;
            if($request->hasFile('foto_modalidade')){
                $file = $request->file('foto_modalidade');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads',$file,$fileName);
                $foto_modalidade = $filePath;
            }

            $foto_modalidade != null ?   $modalidade->update([
                'foto_modalidade' => $foto_modalidade,
                'nome_modalidade'=> $request->nome_modalidade,
                'descricao_modalidade' => $request->descricao_modalidade,
            ]):$modalidade->update([
                'nome_modalidade'=> $request->nome_modalidade,
                'descricao_modalidade' => $request->descricao_modalidade,
            ]);

         
    


            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $modalidade,
                'message' => 'Modalidade Atualizada com Sucesso!'

            ], 201);

        }catch(Exception $e){
            
            return response()->json([
                'status' => false,
                'message' => 'Modalidade não atualizada!' . $e->getMessage(),

            ], 400);
        }
    }

    public function destroy ($id){
        try {

            $modalidade = Modalidades::findOrFail($id);
            $modalidade->delete();

            return response()->json([
                'status' => true,
                'user' => $modalidade,
                'message' => 'Modalidade Apagada com Sucesso!'

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Modalidade não Apagada!' . $e->getMessage()

            ], 400);
        }
    }
}
