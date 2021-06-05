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
        $detalle = DetallePlanDeClase::where('plan_de_clase_id', $planDeClase->id)->get();
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
        $detalle->fecha = $request->fecha; //$this->setFormatDate($request->fecha);
        $detalle->semana = $request->semana ?: null;
        $detalle->proposito = $request->proposito ?: null;
        $detalle->actividad = $request->actividad;
        $detalle->tiempo_presencial = $request->tiempoPresencial ?: null;
        $detalle->actividad_no_presencial = $request->actividadNoPresencial ?: null;
        $detalle->trabajo_autonomo = $request->trabajoAutonomo ?: null;
        $detalle->informacion_extra = $request->informacionExtra ?: null;
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
        $detalle->fecha = $request->fecha ?: $detalle->fecha;
        $detalle->semana = $request->semana ?: $detalle->semana;
        $detalle->proposito = $request->proposito ?: $detalle->proposito;
        $detalle->actividad = $request->actividad ?: $detalle->actividad;
        $detalle->tiempo_presencial = $request->tiempoPresencial ?: $detalle->tiempoPrecencial;
        $detalle->actividad_no_presencial = $request->actividadNoPresencial ?: $detalle->actividadNoPresencial;
        $detalle->trabajo_autonomo = $request->trabajoAutonomo ?: $detalle->trabajoAutonomo;
        $detalle->informacion_extra = $request->informacionExtra ?: $detalle->informacionExtra;
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
            "semana"                => 'nullable|string',
            "proposito"             => 'nullable|string',
            "actividad"             => 'required|string',
            "tiempoPresencial"      =>  ['nullable', 'numeric', 'min:1', 'max:99.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            "actividadNoPresencial" => 'nullable|string',
            "trabajoAutonomo"       => ['nullable', 'numeric', 'min:1', 'max:99.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            "informacionExtra"      => 'nullable|string',
        ];
    }
    private function rulesUpdateValidation()
    {
        return [
            "usuarioId"             => 'required|Integer',
            "cursoId"               => 'required|Integer',
            "planId"                => 'required|Integer',
            "detalleId"             => 'required|Integer',
            "fecha"                 => 'nullable|date',
            "semana"                => 'nullable|string',
            "proposito"             => 'nullable|string',
            "actividad"             => 'nullable|string',
            "tiempoPresencial"      =>  ['nullable', 'numeric', 'min:1', 'max:99.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            "actividadNoPresencial" => 'nullable|string',
            "trabajoAutonomo"       => ['nullable', 'numeric', 'min:1', 'max:99.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            "informacionExtra"      => 'nullable|string',
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
        $formato = 'Y-m-d';
        $date = DateTime::createFromFormat($formato, $fecha);
        return $date;
    }
}
