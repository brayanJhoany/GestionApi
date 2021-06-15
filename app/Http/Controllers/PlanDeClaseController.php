<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\PlanDeClase;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Transformers\SerializerCustom;
use League\Fractal\Resource\Collection;
use App\Transformers\PlanDeClaseTransformer;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Resource\Item;

class PlanDeClaseController extends ApiController
{
    /**
     * lista los plan de clases registrados en la base de datos.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     */
    public function index($usuarioId, $cursoId)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClases = PlanDeClase::where('curso_id', $cursoId)->get();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($planDeClases, new PlanDeClaseTransformer());
        $data = [
            "error" => false,
            "PlanDeClase" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
    /**
     * Lista la informacion de un plan de clases en especifico.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planDeClaseId: identificador de un plan de clases.
     */
    public function show($usuarioId, $cursoId, $planDeClaseId)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClase = PlanDeClase::where('id', $planDeClaseId)->first();
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planDeClaseId}"
                . ', intentelo nuevamente');
        }
        return $this->successResponse($this->setDataToCamelCase($planDeClase), 200);
    }
    /**
     * crea un nuevo registro de un plan de clases
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     */
    public function store(Request $request, $usuarioId, $cursoId)
    {
        $identifier = [
            "usuarioId" => $usuarioId,
            "cursoId"   => $cursoId
        ];
        $validator = Validator::make(array_merge($request->all(), $identifier), $this->rulesStoreValidation());
        if ($validator->fails()) {
            return $this->errorResponse(400, 'los parametros ingresados no son validos');
        }
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClase = new PlanDeClase();
        $planDeClase->horario_de_clases = json_encode($request->horarioDeClase);
        $planDeClase->horario_de_consulta = json_encode($request->horarioDeConsulta);
        $planDeClase->curso_id = $request->cursoId;
        $planDeClase->save();
        return $this->successResponse($this->setDataToCamelCase($planDeClase), 200);
    }
    public function update(Request $request, $usuarioId, $cursoId, $planDeClaseId)
    {
        $identifier = [
            "usuarioId"     => $usuarioId,
            "cursoId"       => $cursoId,
            "planDeClaseId" => $planDeClaseId
        ];
        $validator = Validator::make(array_merge($request->all(), $identifier), $this->rulesUpdateValidation());
        if ($validator->fails()) {
            return $this->errorResponse(400, 'los parametros ingresados no son validos');
        }
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClase = PlanDeClase::where('id', $planDeClaseId)->first();
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planDeClaseId}"
                . ', intentelo nuevamente');
        }
        $horarioDeConsulta = $planDeClase->horario_de_consulta;
        if (is_null($request->horarioDeConsulta) == false) {
            $horarioDeConsulta = json_encode($request->horarioDeConsulta);
        }
        $horarioDeClase = $planDeClase->horario_de_clases;
        if (is_null($request->horarioDeClase) == false) {
            $horarioDeClase = json_encode($request->horarioDeClase);
        }

        $planDeClase->horario_de_consulta = $horarioDeConsulta;
        $planDeClase->horario_de_clases   = $horarioDeClase;
        $planDeClase->save();
        return $this->successResponse($this->setDataToCamelCase($planDeClase), 200);
    }
    public function destroy($usuarioId, $cursoId, $planDeClaseId)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClase = PlanDeClase::where('id', $planDeClaseId)->first();
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planDeClaseId}"
                . ', intentelo nuevamente');
        }
        $planDeClase->delete();
        return $this->successResponse("Se elimino el plan de clases con existo", 200);
    }
    /**
     * Modifica el retorno de la base de datos a camelCase
     * @param planDeClases: instancia de plan de clases.
     */
    private function setDataToCamelCase($planDeClase)
    {
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($planDeClase, new PlanDeClaseTransformer());
        $data = $manager->createData($resource);
        return $data;
    }
    /**
     * Reglas para validar los parametros al registrar un
     * plan de clases
     */
    private function rulesStoreValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "horarioDeClase"        => 'required|array|min:1',
            "horarioDeConsulta"     => 'required|array|min:1'
        ];
    }
    /**
     * Reglas para validar la actualización de la informacion
     * de un plan de clases.
     */
    private function rulesUpdateValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planDeClaseId"         => 'required|Integer',
            "horarioDeClase"        => 'nullable|array|min:1',
            "horarioDeConsulta"     => 'nullable|array|min:1'
        ];
    }
}
