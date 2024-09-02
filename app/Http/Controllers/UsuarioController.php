<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Models\Contratos;
use App\Models\DadosFuncionario;
use App\Models\Usuario;
use App\Models\UsuarioModalidades;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = DB::table('usuarios')->get();

            // Buscar todos os contratos com detalhes dos planos
            $contratos = DB::table('contratos')
                ->join('planos', 'contratos.planos_id', '=', 'planos.id')
                ->select('contratos.*', 'planos.nome_plano')
                ->get();

            // Buscar todas as modalidades com detalhes das modalidades
            $modalidades = DB::table('usuarios_modalidades')->get();

            // Buscar nomes das modalidades
            $modalidadeNomes = DB::table('modalidades')->pluck('nome_modalidade', 'id');
    
            // Adicionar o nome da modalidade ao array de modalidades
            foreach ($modalidades as &$modalidade) {
                $modalidade->nome_modalidade = $modalidadeNomes[$modalidade->modalidade_id] ?? 'Desconhecida';
            }

            $funcionarios = DB::table('dados_funcionario')->get();


            // Montar o resultado final
            $userData = [
                'usuarios' => $user,
                'contratos' => $contratos,
                'modalidades' => $modalidades,
                'funcionarios' => $funcionarios
            ];


            return response()->json([
                'status' => true,
                'userData' => $userData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Something went wrong.',
                'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }


    public function show($id): JsonResponse
    {
        try {
            // Buscar os dados do usuário
            $user = DB::table('usuarios')->where('id', $id)->first();
    
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado',
                ], 404);
            }
    
            // Inicializar os arrays para contratos, modalidades e funcionários
            $contratos = [];
            $modalidades = [];
            $funcionarios = [];
    
            if ($user->tipo_usuario == 'aluno') {
                // Buscar contratos com detalhes dos planos
                $contratos = DB::table('contratos')
                    ->join('planos', 'contratos.planos_id', '=', 'planos.id')
                    ->where('contratos.usuario_id', $id)
                    ->select('contratos.*', 'planos.nome_plano')
                    ->get();
    
                // Buscar modalidades do usuário
                $modalidades = DB::table('usuarios_modalidades')
                    ->where('usuario_id', $id)
                    ->get();
    
                // Buscar nomes das modalidades
                $modalidadeNomes = DB::table('modalidades')->pluck('nome_modalidade', 'id');
    
                // Adicionar o nome da modalidade ao array de modalidades
                foreach ($modalidades as &$modalidade) {
                    $modalidade->nome_modalidade = $modalidadeNomes[$modalidade->modalidade_id] ?? 'Desconhecida';
                }
            } else if ($user->tipo_usuario == 'funcionario') {
                // Buscar dados do funcionário
                $funcionarios = DB::table('dados_funcionario')
                    ->where('usuario_id', $id)
                    ->get();
            }
    
            // Montar o resultado final
            $userData = [
                'usuario' => $user,
                'contratos' => $contratos,
                'modalidades' => $modalidades,
                'funcionarios' => $funcionarios
            ];
    
            return response()->json([
                'status' => true,
                'userData' => $userData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar usuário: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(UsuarioRequest $request): JsonResponse

    {

        try {

            DB::beginTransaction();


            $fotoUsuario = null;
            if ($request->hasFile('foto_usuario')) {
                $file = $request->file('foto_usuario');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads', $file, $fileName); // Salva o arquivo na pasta storage/public
                $fotoUsuario = $filePath;
            }

            $data = [
                'tipo_usuario' => $request->tipo_usuario,
                'nome' => $request->nome,
                'email' => $request->email,
                'data_nascimento' => $request->data_nascimento,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'telefone' => $request->telefone,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'password' => Hash::make($request->password),
            ];

            $data2 = [
                'foto_usuario' => $fotoUsuario,
                'tipo_usuario' => $request->tipo_usuario,
                'nome' => $request->nome,
                'email' => $request->email,
                'data_nascimento' => $request->data_nascimento,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'telefone' => $request->telefone,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'password' => Hash::make($request->password),
            ];

            $foto_usuario == null? $usuario = Usuario::create([$data]) : $usuario = Usuario::create([$data2]);



            if ($request->tipo_usuario == 'aluno') {

                Contratos::create([
                    'usuario_id' => $usuario->id,
                    'planos_id' => $request->planos_id,
                    'data_inicio' => $request->data_inicio,
                    'data_renovacao' => $request->data_renovacao,
                    'data_vencimento' =>  $request->data_vencimento,
                    'valor_plano' => $request->valor_plano,
                    'desconto' => $request->desconto,
                    'parcelas' => $request->parcelas,
                    'observacoes' => $request->observacoes
                ]);

                if (is_array($request->modalidade_id)) {

                    foreach ($request->modalidade_id as $modalidadeId) {
                        UsuarioModalidades::create([
                            'usuario_id' => $usuario->id,
                            'modalidade_id' => $modalidadeId
                        ]);
                    }
                } else {
                    UsuarioModalidades::create([
                        'usuario_id' => $usuario->id,
                        'modalidade_id' => $request->modalidade_id
                    ]);
                }
            } else if ($request->tipo_usuario == 'funcionario') {
                $dados = DadosFuncionario::create([
                    'usuario_id' => $usuario->id,
                    'tipo_funcionario' => $request->tipo_funcionario,
                    'cargo' => $request->cargo,
                    'atividades' => $request->atividades,
                ]);
            }

            DB::commit();


            return response()->json([
                'status' => true,
                'user' => $usuario,
                'message' => 'Usuario cadastrado com Sucesso!'

            ], 201);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Usuario não cadastrado!' . $e->getMessage(),

            ], 400);
        }
    }
    public function update(UsuarioUpdateRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            $fotoUsuario = $usuario->foto_usuario;
            if ($request->hasFile('foto_usuario')) {
                $file = $request->file('foto_usuario');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
                Storage::putFileAs('public/uploads', $file, $fileName); // Salva o arquivo na pasta storage/public
                $fotoUsuario = $filePath;
            }




            $data = [
                'foto_usuario' => $fotoUsuario,
                'tipo_usuario' => $request->tipo_usuario,
                'nome' => $request->nome,
                'email' => $request->email,
                'data_nascimento' => $request->data_nascimento,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'telefone' => $request->telefone,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
            ];

            $data2 = [
                'tipo_usuario' => $request->tipo_usuario,
                'nome' => $request->nome,
                'email' => $request->email,
                'data_nascimento' => $request->data_nascimento,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'telefone' => $request->telefone,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
            ];



            $fotoUsuario != null ?   $usuario->update($data) : $usuario->update($data2);

            $dados = null;
            $dados_modalidades = null;

            if ($request->tipo_usuario == 'aluno') {

                $contrato = Contratos::where('usuario_id', $usuario->id)->first();

                $contrato->update([
                    'planos_id' => $request->planos_id,
                    'data_inicio' => $request->data_inicio,
                    'data_renovacao' => $request->data_renovacao,
                    'data_vencimento' => $request->data_vencimento,
                    'valor_plano' => $request->valor_plano,
                    'desconto' => $request->desconto,
                    'parcelas' => $request->parcelas,
                    'observacoes' => $request->observacoes
                ]);

              
            } else if ($request->tipo_usuario == 'funcionario') {

                $dados = DadosFuncionario::where('usuario_id', $usuario->id)->first();
                $dados->update(
                    [
                        'usuario_id' => $usuario->id,
                        'tipo_funcionario' => $request->tipo_funcionario,
                        'cargo' => $request->cargo,
                        'atividades' => $request->atividades,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $usuario,
                'dados' => $dados,
                'dados_modalidades' => $dados_modalidades,
                'message' => 'Usuário atualizado com sucesso!'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Usuário não editado! ' . $e->getMessage()
            ], 400);
        }
    }



    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            // Verifica o tipo de usuário e deleta os relacionamentos apropriados
            if ($usuario->tipo_usuario == 'aluno') {
                Contratos::where('usuario_id', $usuario->id)->delete();
                UsuarioModalidades::where('usuario_id', $usuario->id)->delete();
            } else if ($usuario->tipo_usuario == 'funcionario') {
                DadosFuncionario::where('usuario_id', $usuario->id)->delete();
            }

            // Deleta o usuário
            $usuario->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $usuario,
                'message' => 'Usuário apagado com sucesso!'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Usuário não apagado! ' . $e->getMessage()
            ], 400);
        }
    }
}
