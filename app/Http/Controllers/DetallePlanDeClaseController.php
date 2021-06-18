<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\DetallePlanDeClase;
use App\Models\PlanDeClase;
use App\Models\User;
use App\Transformers\DetallePlanDeClaseTransformer;
use App\Transformers\SerializerCustom;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class DetallePlanDeClaseController extends ApiController
{
    /**
     * Entrega un listado del detalle de un plan de clases.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planId: identificador de un plan de clases.
     */
    public function index($usuarioId, $cursoId, $planId)
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
        $planDeClase = PlanDeClase::where('id', $planId)->first(['id']);
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planId}"
                . ', intentelo nuevamente');
        }
        $detalle = DetallePlanDeClase::where('plan_de_clase_id', $planDeClase->id)
            ->orderBy('fecha', 'DESC')
            ->get();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($detalle, new DetallePlanDeClaseTransformer());
        $data = [
            "error" => false,
            "DetallePlanDeClase" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
    /**
     * Muestra el detalle especifico de un plan de clases.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planId: identificador de un plan de clases.
     * @param detalleId: identificador de un detalle de plan de clases.
     */
    public function show($usuarioId, $cursoId, $planId, $detalleId)
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
        $planDeClase = PlanDeClase::where('id', $planId)->first(['id']);
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planId}"
                . ', intentelo nuevamente');
        }
        $detalle = DetallePlanDeClase::where('id', $detalleId)->first();
        if (!$detalle) {
            return $this->errorResponse(404, "No se encotro el detalle del plan de clases {$planId}");
        }
        return $this->successResponse($this->setDataToCamelCase($detalle), 200);
    }
    /**
     * Registra un nuevo detalle de un plan de clases.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planId: identificador de un plan de clases.
     */
    public function store(Request $request, $usuarioId, $cursoId, $planId)
    {
        $identifier = [
            "usuarioId" => $usuarioId,
            "cursoId"   => $cursoId,
            "planId"    => $planId
        ];
        $validator = Validator::make(array_merge($request->all(), $identifier), $this->rulesStoreValidation());
        if ($validator->fails()) {
            return $this->errorResponse(400, 'los parametros ingresados no son validos');
        }
        //http://127.0.0.1:8000/profesor/1/curso/2/plan-de-clases/1/detalle
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $planDeClase = PlanDeClase::where('id', $planId)->first(['id']);
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planId}"
                . ', intentelo nuevamente');
        }
        $detalle = new DetallePlanDeClase();
        $detalle->fecha         = $this->setFormatDate($request->fecha);
        $detalle->semana        = $request->semana;
        $detalle->saber_tema    = $request->saberTema;
        $detalle->actividad     = $request->actividad;
        $detalle->observacion   = $request->observacion;
        $detalle->plan_de_clase_id = $planId;
        $detalle->save();
        return $this->successResponse($this->setDataToCamelCase($detalle), 200);
    }
    /**
     * Actualiza la información de un detalle de plan de clases.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planId: identificador de un plan de clases.
     * @param detalleId: identificador de un detalle de plan de clases.
     */
    public function update(Request $request, $usuarioId, $cursoId, $planId, $detalleId)
    {
        $identifier = [
            "usuarioId" => $usuarioId,
            "cursoId"   => $cursoId,
            "planId"    => $planId,
            "detalleId" => $detalleId
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
        $planDeClase = PlanDeClase::where('id', $planId)->first(['id']);
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planId}"
                . ', intentelo nuevamente');
        }
        $detalle = DetallePlanDeClase::where('id', $detalleId)->first();
        if (!$detalle) {
            return $this->errorResponse(404, "No se encotro el detalle del plan de clases {$planId}");
        }
        //update info
        $detalle->fecha = $this->setFormatDate($request->fecha) ?: $detalle->fecha;
        $detalle->semana = $request->semana ?: $detalle->semana;
        $detalle->saber_tema = $request->saberTema ?: $detalle->saber_tema;
        $detalle->actividad = $request->actividad ?: $detalle->actividad;
        $detalle->observacion = $request->observacion ?: $detalle->observacion;
        $detalle->save();
        return $this->successResponse($this->setDataToCamelCase($detalle), 200);
    }
    /**
     * Elimina el detalle de un plan de clases en especifico.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param planId: identificador de un plan de clases.
     * @param detalleId: identificador de un detalle de plan de clases.
     */
    public function destroy($usuarioId, $cursoId, $planId, $detalleId)
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
        $planDeClase = PlanDeClase::where('id', $planId)->first(['id']);
        if (!$planDeClase) {
            return $this->errorResponse(404, "No se encontro el plan de clases con identificador {$planId}"
                . ', intentelo nuevamente');
        }
        $detalle = DetallePlanDeClase::where('id', $detalleId)->first();
        if (!$detalle) {
            return $this->errorResponse(404, "No se encotro el detalle del plan de clases {$planId}");
        }
        $detalle->delete();
        return $this->successResponse("se elimino correctamente el detalle del pla de clases", 200);
    }
    /**
     * Reglas de validacion para registrar un nuevo plan de clases.
     */
    private function rulesStoreValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planId"                => 'required|Integer',
            "fecha"                 => 'required|date',
            "semana"                => 'required|Integer',
            "saberTema"             => 'required|string',
            "actividad"             => 'required|string',
            "observacion"           => 'required|string',
        ];
    }
    /**
     * Reglas para actualizar un detalle de plan de clases en especifico.
     */
    private function rulesUpdateValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planId"                => 'required|Integer',
            "detalleId"             => 'required|Integer',
            "fecha"                 => 'nullable|date',
            "semana"                => 'nullable|Integer',
            "saberTema"             => 'nullable|string',
            "actividad"             => 'nullable|string',
            "observacion"           => 'nullable|string',
        ];
    }
    /**
     * Modifica la respuesta de la base de datos, a estilo camelCase.
     * @param detalle: instancia de detalle de un plan de clases.
     */
    private function setDataToCamelCase($detalle)
    {
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($detalle, new DetallePlanDeClaseTransformer());
        $data = $manager->createData($resource);
        return $data;
    }
    /**
     * Modifica el formato de la fecha.
     * año-mes-dia.
     */
    private function setFormatDate($fecha)
    {
        if ($fecha) {
            $fecha = new DateTime($fecha);
            $date = $fecha->format('Y-m-d');
            return $date;
        }
        return null;
    }
}
