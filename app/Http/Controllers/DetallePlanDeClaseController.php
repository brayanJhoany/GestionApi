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
    private function rulesStoreValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planId"                => 'required|Integer',
            "fecha"                 => 'required|date',
            // "semana"                => 'required|string',
            // "saberTema"             => 'required|string',
            // "actividad"             => 'required|string',
            // "observacion"           => 'required|string',
        ];
    }
    private function rulesUpdateValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planId"                => 'required|Integer',
            "detalleId"             => 'required|Integer',
            //"fecha"                 => 'nullable|date',
            //"semana"                => 'nullable|string',
            //"saberTema"             => 'nullable|string',
            //"actividad"             => 'nullable|string',
            //"observacion"           => 'nullable|string',
        ];
    }
    private function setDataToCamelCase($detalle)
    {
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($detalle, new DetallePlanDeClaseTransformer());
        $data = $manager->createData($resource);
        return $data;
    }
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
