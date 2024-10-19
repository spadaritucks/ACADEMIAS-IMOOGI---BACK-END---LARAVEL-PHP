<?php

namespace App\Http\Controllers;

use App\Models\Pagamentos_Mensais;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PagamentosMensaisController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamentos_Mensais::all();
        return response()->json([
            'status' => true,
            'message' => 'Pagamentos mensais listados com sucesso!',
            'pagamentos' => $pagamentos
        ], 200);
    }
    public function store(Request $request)

    {


        try {

            DB::beginTransaction();

            $comprovante = null;
            if ($request->hasFile('comprovante')) {
                $file = $request->file('comprovante');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads', $file, $fileName); // Salva o arquivo na pasta storage/public
                $comprovante = $filePath;
            }

            $valorPago = str_replace(',', '.', $request->valor_pago);
            



            $pagamento = Pagamentos_Mensais::create([
                'usuario_id' => $request->usuario_id,
                'comprovante' => $comprovante,
                'valor_pago' => $valorPago,
                'data_pagamento' => $request->data_pagamento,
                'comentario' => $request->comentario,


            ]);




            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pagamento mensal cadastrado com sucesso!',
                'data' => $pagamento
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar pagamento mensal! ' . $e->getMessage(),
            ], 400);
        }
    }

    public function adicionarComentarioAdmin(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pagamento = Pagamentos_Mensais::findOrFail($id);

            $pagamento->update([
                'comentario' => $request->comentario ?? null,
                'comentario_adm' => $request->comentario_adm ?? null
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Comentário do administrador adicionado com sucesso!',
                'data' => $pagamento
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao adicionar comentário do administrador: ' . $e->getMessage(),
            ], 400);
        }
    }
}
