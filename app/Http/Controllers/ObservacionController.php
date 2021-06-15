<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Observacion;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use App\Transformers\SerializerCustom;
use App\Http\Controllers\ApiController;
use App\Models\Bitacora;
use League\Fractal\Resource\Collection;
use App\Transformers\ObservacionTransformer;

class ObservacionController extends ApiController
{
    /**
     * Muestra todas las observaciones registradas.
     * @param userId: user identifier.
     * @param cursoId: curso identifier.
     * @param bitacoraId: bitacora identifier.
     */
    public function index(int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "No se encotro el usuario con el identificador {$userId}");
        }
        $curso = Curso::where('id', $cursoId)->where('user_id', $user->id)->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
        }
        $bitacora = $curso->bitacora;
        //por si no pasan el cambio
        $bitacora = Bitacora::where('id', $bitacoraId)->where('curso_id', $curso->id)->first();
        if (!$bitacora) {
            return $this->errorResponse(404, "El curso no tiene bitacora asociada");
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
     * registra una observación en la base de datos.
     * @param userId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param bitacoraId: identificador de una bitacora.
     */
    public function store(Request $request, int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "No se encotro el usuario con el identificador {$userId}");
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
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
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
    /**
     * actualiza la información registrada de una observación.
     * @param userId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param bitacoraId: identificador de una bitacora.
     */
    public function update(Request $request, int $userId, int $cursoId, int $bitacoraId, int $id)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
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
        $observacion->titulo = $request->titulo ?: $observacion->titulo;
        $observacion->descripcion = $request->descripcion ?: $observacion->descripcion;
        $observacion->fecha = $request->fecha ?: $observacion->fecha;
        $observacion->save();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($observacion, new ObservacionTransformer());
        $data = $manager->createData($resource);
        return $this->successResponse($data, 200);
    }
    /**
     * Elimina una observacion de la base de datos.
     * @param userId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param bitacoraId: identificador de una bitacora.
     * @param id: identificador de una observacion.
     */
    public function destroy(Request $request, int $userId, int $cursoId, int $bitacoraId, int $id)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "No se encotro el curso con identificador {$cursoId}.");
        }
        $bitacora = $curso->bitacora;
        if (is_null($bitacora)) {
            return $this->errorResponse(404, "No se encontro la bitacora con identificador {$bitacoraId}");
        }
        $observacion = Observacion::where('id', $id)
            ->where('bitacora_id', $bitacora->id)->first();
        $observacion->delete();
        return $this->successMessageResponse("observacion eliminada", 200);
    }
}
