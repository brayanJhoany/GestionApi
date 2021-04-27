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
    public function index(int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            //error response
            dd("no existe el usuario");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            //error response
            dd("no existe el usuario");
        }
        $bitacora = $curso->bitacora;
        $observaciones = $bitacora->observaciones;
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($observaciones, new ObservacionTransformer());
        $data = [
            "error" => false,
            "observaciones" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
    public function store(Request $request, int $userId, int $cursoId, int $bitacoraId)
    {
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            //error response
            dd("no existe el usuario");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            //error response
            dd("no existe el usuario");
        }
        $bitacora = Bitacora::where('id', $bitacoraId)
            ->where('curso_id', $curso->id)->first();
        if (is_null($bitacora)) {
            //error response
        }
        $observacion = new Observacion();
        $observacion->titulo = $request->titulo;
        $observacion->descripcion = $request->descripcion;
        $observacion->bitacora_id = $bitacora->id;
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
            //error response
            dd("no existe el usuario");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)
            ->first();
        if (is_null($curso)) {
            //error response
            dd("no existe el usuario");
        }
        $bitacora = Bitacora::where('id', $bitacoraId)
            ->where('curso_id', $curso->id)->first();
        if (is_null($bitacora)) {
            //error response
        }
        $observacion = Observacion::where('id', $id)
            ->where('bitacora_id', $bitacora->id)->first();
        $observacion->delete();
        return $this->successMessageResponse("observacion eliminada", 200);
    }
}
