<?php

namespace App\Http\Controllers;

use App\Models\Checkins;
use App\Models\Contratos;
use App\Models\Especial_checkins;
use App\Models\Packs;
use App\Models\Planos;
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
        try {

            $reservas =  DB::table('reservas')->join('modalidades', 'reservas.modalidade_id', 'modalidades.id')
                ->select('reservas.*', 'modalidades.nome_modalidade')->get();

            return response()->json([
                'status' => true,
                'reservas' => $reservas
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao recuperar os dados da reserva ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        try {
            $usuarioId = $request->usuario_id;
            $contrato = Contratos::where('usuario_id', $usuarioId)->first();
            $planosId = $contrato->planos_id;
            $plano = Planos::where('id', $planosId )->first();
            $checkins_planos = $plano->number_checkins;


            // 1. Verificar se o usuário já fez 2 check-ins nesta semana
            $checkinsNaSemana = Checkins::where('usuario_id', $usuarioId)
                ->whereBetween('checkin_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->count();


            if ($checkinsNaSemana >=  $checkins_planos) {

                if ($contrato->packs_id != null) {
                    $pack = Packs::findOrFail($contrato->packs_id);
                    
                    $number_especial_checkins = $pack->number_checkins_especial;
                    
                    $checkins_extras = Especial_checkins::where('usuario_id', $usuarioId)
                        ->whereBetween('checkin_at_especial', [
                            Carbon::now()->startOfMonth(),
                            Carbon::now()->endOfMonth()
                        ])->count();



                    if ($checkins_extras >= $number_especial_checkins) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Você já atingiu o limite de ' . $number_especial_checkins . ' check-ins especiais neste mês.'
                        ], 403);
                    }

                    // 2. Criar a reserva
                    $reserva = Reservas::create([
                        'usuario_id' => $request->usuario_id,
                        'modalidade_id' => $request->modalidade_id,
                        'dia_semana' => $request->dia_semana,
                        'horario' => $request->horario,
                    ]);

                    Especial_checkins::create([
                        'usuario_id' => $usuarioId,
                        'checkin_at_especial' => Carbon::now()
                    ]);

                    return response()->json([
                        'message' => 'Reserva criada com sucesso!',
                        'reserva' => $reserva
                    ]); 

                    
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Você já atingiu o limite de ' . $checkins_planos . ' check-ins nesta semana.'
                    ], 403);
                }



          
            }

            // 2. Criar a reserva
            $reserva = Reservas::create([
                'usuario_id' => $request->usuario_id,
                'modalidade_id' => $request->modalidade_id,
                'dia_semana' => $request->dia_semana,
                'horario' => $request->horario,
            ]);

            Checkins::create([
                'usuario_id' => $usuarioId,
                'checkin_at' => Carbon::now()
            ]);

            return response()->json([
                'message' => 'Reserva criada com sucesso!',
                'reserva' => $reserva
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Falha em realizar a reserva ' . $e->getMessage()

            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {


            $reserva =  Reservas::findOrFail($id);
            $reserva->delete();



            return response()->json([
                'status' => true,
                'message' => "Exclusão feita com sucesso"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao excluir a reserva ' . $e->getMessage()
            ]);
        }
    }
}
