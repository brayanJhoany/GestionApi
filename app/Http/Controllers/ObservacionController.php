<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\SerializerCustom;
use App\Http\Controllers\ApiController;
use App\Models\Bitacora;
use App\Models\Observacion;
use League\Fractal\Resource\Collection;
use App\Transformers\ObservacionTransformer;
use Illuminate\Http\Request;

class ObservacionController extends ApiController
{
    /**
     * displays all observations associated with a specific course
     * @param userId: user identifier.
     * @param cursoId: curso identifier.
     * @param bitacoraId: bitacora identifier.
     */
    public function index(int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "The user with identifier {$userId} was not found.");
        }
        $curso = Curso::where('id', $cursoId)->where('user_id', $user->id)->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "The curso with identifier {$cursoId} was not found.");
        }
        $bitacora = $curso->bitacora;
        if (is_null($bitacora)) {
            return $this->errorResponse(404, "The course has no associated logbook");
        }
        $observaciones = Observacion::where('bitacora_id', $bitacora->id)
            ->orderBy('created_at', 'ASC')->get();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($observaciones, new ObservacionTransformer());
        $data = [
            "error" => false,
            "observaciones" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
    /**
     * records an observation
     * @param userId: user identifier.
     * @param cursoId: curso identifier.
     * @param bitacoraId: bitacora identifier.
     */
    public function store(Request $request, int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "The user with identifier {$userId} was not found.");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            //error response
            dd("no existe el usuario");
        }
        $bitacora = $curso->bitacora;
        if (is_null($bitacora)) {
            return $this->errorResponse(404, "The curso with identifier {$cursoId} was not found.");
        }
        $observacion = new Observacion();
        $observacion->titulo = $request->titulo;
        $observacion->descripcion = $request->descripcion;
        $observacion->bitacora_id = $bitacora->id;
        $observacion->fecha = $request->fecha;
        $observacion->save();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($observacion, new ObservacionTransformer());
        $data = $manager->createData($resource);
        return $this->successResponse($data, 200);
    }
    public function update(Request $request, int $userId, int $cursoId, int $bitacoraId, int $id)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "the curso with identifier {$cursoId} was not found.");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "The curso with identifier {$cursoId} was not found.");
        }
        $bitacora = $curso->bitacora;
        if (is_null($bitacora)) {
            return $this->errorResponse(404, "El curso no tiene bitacora asociada");
        }
        $observacion = Observacion::where('id', $id)
            ->where('bitacora_id', $bitacora->id)->first();
        if (is_null($observacion)) {
            return $this->errorResponse(404, "observacion no encotrada");
        }
        $observacion->titulo = $request->titulo;
        $observacion->descripcion = $request->descripcion;
        $observacion->fecha = $request->fecha;
        $observacion->save();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($observacion, new ObservacionTransformer());
        $data = $manager->createData($resource);
        return $this->successResponse($data, 200);
    }
    public function destroy(Request $request, int $userId, int $cursoId, int $bitacoraId, int $id)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "the curso with identifier {$cursoId} was not found.");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "The curso with identifier {$cursoId} was not found.");
        }
        $bitacora = $curso->bitacora;
        if (is_null($bitacora)) {
            return $this->errorResponse(404, "The course has no associated logbook");
        }
        $observacion = Observacion::where('id', $id)
            ->where('bitacora_id', $bitacora->id)->first();
        $observacion->delete();
        return $this->successMessageResponse("observacion eliminada", 200);
    }
}
