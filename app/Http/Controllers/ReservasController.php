<?php

namespace App\Http\Controllers;

use App\Models\Reservas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

           $reservas =  DB::table('reservas')->join('modalidades', 'reservas.modalidade_id', 'modalidades.id')
           ->select('reservas.*', 'modalidades.nome_modalidade')->get();

           return response()->json([
            'status' => true,
            'reservas' => $reservas
           ], 200);

        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Falha ao recuperar os dados da reserva ' .$e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
      try{
        DB::beginTransaction();


        $reserva = Reservas::create([
            'usuario_id' => $request->usuario_id,
            'modalidade_id' => $request->modalidade_id,
            'dia_semana' => $request->dia_semana,
            'horario' => $request->horario,
        ]);


        DB::commit();

        return response()->json([
            'status' => true,
            'aulas' => $reserva,
            'message' => 'Reserva feita com sucesso'
        ],201);

      }catch(Exception $e){

        return response()->json([
            'status' => false,
            'message' => 'Falha em realizar a reserva ' .$e->getMessage() 
        ], 400);

      }
    }

    public function destroy(string $id)
    {
        try{


          $reserva =  Reservas::findOrFail($id);
          $reserva->delete();
            


            return response ()->json([
                'status' => true,
                'message' => "Exclusão feita com sucesso"
            ]);

        }catch(Exception $e){
            return response ()->json([
                'status' => false,
                'message' => 'Falha ao excluir a reserva ' .$e->getMessage()
            ]);
        }
    }
}
