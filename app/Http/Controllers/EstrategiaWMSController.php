<?php

namespace App\Http\Controllers;

use App\Models\Estrategia;
use App\Models\Horario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstrategiaWMSController extends Controller
{
    public function __construct(
        protected Request $request
    ) {}

    public function postEstrategiaWMS()
    {
        $dados = $this->request->json()->all();

        $horarios = $dados['horarios'];

        try {

            DB::beginTransaction();

            Estrategia::create([
                'ds_estrategia_wms' => $dados['dsEstrategia'],
                'nr_prioridade' => $dados['nrPrioridade']
            ]);

            $ultimoID = Estrategia::latest('cd_estrategia_wms')->value('cd_estrategia_wms');

            foreach($horarios as $horario)
            {
                Horario::create([
                    'cd_estrategia_wms' => $ultimoID,
                    'ds_horario_inicio' => $horario['dsHorarioInicio'],
                    'ds_horario_final' => $horario['dsHorarioFinal'],
                    'nr_prioridade' => $horario['nrPrioridade']
                ]);
            }

            DB::commit();

            return response()->json([
                "Sucess" => true
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "Sucess"    => false,
                "Error"     => $e->getMessage()
            ], 500);
        }
    }

    public function getEstrategiaWMS()
    {
        $dsMinuto       = $this->request->route('dsMinuto');
        $dsHora         = $this->request->route('dsHora');
        $cdEstrategia   = $this->request->route('cdEstrategia');

        if (strlen($dsMinuto) !== 2 || strlen($dsHora) !== 2)
        {
            return response()->json([
                "Success" => false,
                "Error"   => "Informe 2 digitos para a hora e 2 para os minutos"
            ], 400);
        }

        try {
            $min = Horario::min('ds_horario_inicio');

            $max = Horario::max('ds_horario_final');

            if($dsHora < substr($min, 0, 2) || $dsHora > substr($max, 0, 2) || ($dsHora == substr($max, 0, 2) && $dsMinuto > 0))
            {
                $prioridades = Estrategia::select('nr_prioridade')->get();

                foreach($prioridades as $prioridade)
                {
                    return response()->json([
                        "Prioridade" => $prioridade->nr_prioridade,
                    ]);
                }
            }

            if($dsMinuto != 00)
            {
                $dsHora++;
                $dsMinuto = "00";

                $horarios = Horario::where('ds_horario_final', "$dsHora:$dsMinuto")
                                    ->where('cd_estrategia_wms', $cdEstrategia)
                                    ->get();

                foreach($horarios as $horario)
                {
                    return response()->json([
                        "Prioridade" => $horario->nr_prioridade,
                    ]);
                }
            }
        } catch (QueryException $e) {
            return response()->json([
                "Success" => false,
                "Error"   => "Erro de consulta: " . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                "Success" => false,
                "Error"   => "Erro: " . $e->getMessage()
            ], 500);
        }
    }
}
