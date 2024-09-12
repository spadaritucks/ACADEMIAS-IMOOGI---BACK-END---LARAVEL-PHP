<?php

namespace App\Http\Controllers;

use App\Models\Checkins;
use App\Models\Reservas;
use Carbon\Carbon;
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
        $usuarioId = $request->input('usuario_id');

        // 1. Verificar se o usuário já fez 2 check-ins nesta semana
        $checkinsNaSemana = Checkins::where('usuario_id', $usuarioId)
        ->whereBetween('checkin_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->count();


        if ($checkinsNaSemana >= 2) {
            return response()->json([
                'status' => false,
                'message' => 'Você já atingiu o limite de 2 check-ins nesta semana.'
            ], 403);
        }

        // 2. Criar a reserva
        $reserva = Reservas::create([
            'usuario_id' => $request->input('usuario_id'),
            'modalidade_id' => $request->input('modalidade_id'),
            'dia_semana' => $request->input('dia_semana'),
            'horario' => $request->input('horario'),
        ]);

        Checkins::create([
            'usuario_id' => $usuarioId,
            'checkin_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Reserva criada com sucesso!',
            'reserva' => $reserva
        ]);

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
