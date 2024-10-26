<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadesRequest;
use App\Http\Requests\UnidadesUpdateRequest;
use App\Models\Unidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UnidadesController extends Controller
{
    public function index (){
        try{
            $unidades = Unidades::all();
            return response()->json([
                'status' => true,
                'unidades' => $unidades
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar as unidades' .$e->getMessage(),
            
            ],500);
        }
    }

    public function show ($id){
        try{
            $unidade = Unidades::findOrFail($id);
            return response()->json([
                'status' => true,
                'unidade' => $unidade
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar a unidade' .$e->getMessage(),
            
            ],500);
        }
    }

    public function store (UnidadesRequest $request){

        try{

        DB::beginTransaction();
        $imagem_unidade = null;
        if($request->hasFile('imagem_unidade')){
            $file = $request->file('imagem_unidade');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/' . $fileName;
            Storage::putFileAs('public/uploads',$file,$fileName);
            $imagem_unidade= $filePath;
        }

        $grade = null;
        if($request->hasFile('grade')){
            $file = $request->file('grade');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/' . $fileName;
            Storage::putFileAs('public/uploads',$file,$fileName);
            $grade= $filePath;
        }

        $data = [
            'imagem_unidade' => $imagem_unidade,
            'nome_unidade' => $request->nome_unidade,
            'endereco' => $request->endereco,
            'grade' => $grade,
            'descricao' => $request->descricao
        ];

        $unidade = Unidades::create($data);

        DB::commit();

        return response()->json([
            'status' => true,
            'unidade' => $unidade,
            'message' => 'Unidade Cadastrada com Sucesso'
        ],201);


        }catch(Exception $e){
            
        return response()->json([
            'status' => false,
            'message' => 'Erro ao cadastrar a unidade ' .$e->getMessage()
        ],400);
        }
        
    }

    public function update (UnidadesRequest $request,$id){
        try{

            DB::beginTransaction();

            $unidade = Unidades::findOrFail($id);

            $imagem_unidade = null;
            if($request->hasFile('imagem_unidade')){
                $file = $request->file('imagem_unidade');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads',$file,$fileName);
                $imagem_unidade= $filePath;
            }
    
            $grade = null;
            if($request->hasFile('grade')){
                $file = $request->file('grade');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads',$file,$fileName);
                $grade= $filePath;
            }
    
            $data = [
                'imagem_unidade' => $imagem_unidade,
                'nome_unidade' => $request->nome_unidade,
                'endereco' => $request->endereco,
                'grade' => $grade,
                'descricao' => $request->descricao
            ];
    
            $data2 = [
                'nome_unidade' => $request->nome_unidade,
                'endereco' => $request->endereco,
                'grade' => $grade,
                'descricao' => $request->descricao
            ];
    
            $data3 = [
                'imagem_unidade' => $imagem_unidade,
                'nome_unidade' => $request->nome_unidade,
                'endereco' => $request->endereco,
                'descricao' => $request->descricao
            ];

            $data4 = [
                'nome_unidade' => $request->nome_unidade,
                'endereco' => $request->endereco,
                'descricao' => $request->descricao
            ];
    
          
            if($imagem_unidade == null && $grade == null ){
                $unidade->update($data4);
            }if($imagem_unidade && $grade == null){
                $unidade->update($data3);
            }if($imagem_unidade == null && $grade ){
                $unidade->update($data2);
            }if($imagem_unidade && $grade){
                $unidade->update($data);
            }
            
           
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'unidade' => $unidade,
                'message' => 'Unidade Cadastrada com Sucesso'
            ],201);
    
    
            }catch(Exception $e){
                
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar a unidade ' .$e->getMessage()
            ],400);
            }
    }
    

    public function destroy ($id){
        try{
            $unidade = Unidades::findOrFail($id);
            $unidade->delete();

            return response()->json([
                'status' => true,
                'message' => 'Unidade deletada com sucesso'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar ao deletar unidade' .$e->getMessage(),
            
            ]);
        }
    }
}
