<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Syllabus;
use App\Models\User;
use App\Transformers\SerializerCustom;
use App\Transformers\SyllabusTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class SyllabusController extends ApiController
{
    public function index()
    {
        $syllabus = Syllabus::all();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($syllabus, new SyllabusTransformer());
        $data = [
            "error" => false,
            "syllabus" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
    public function show($usuarioId, $cursoId, $id)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $syllabus = Syllabus::where('curso_id', $curso->id)->where('id', $id)->first();
        if (!$syllabus) {
            return $this->errorResponse(404, "syllabus no encotrado.");
        }
        return $this->successResponse($this->setDataToCamelCase($syllabus), 200);
    }
    public function store(Request $request, $usuarioId, $cursoId)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $validarCurso = $this->validarPreRequisitos($request->preRequisito);
        if (!$validarCurso) {
            return $this->errorResponse(404, "No se encontraron los cursos de pre-requisito, intentelo de nuevo.");
        }
        $syllabus = new Syllabus();
        $syllabus->curso_id             = $curso->id;
        $syllabus->nro_creditos         = $request->nroCreditos;
        $syllabus->area_conocimiento    = $request->areaConocimiento;
        $syllabus->semestre             = $request->semestre;
        $syllabus->pre_requisito        = json_encode($request->preRequisito);
        $syllabus->responsable_syllabus = $request->responsableSyllabus;
        $syllabus->competencia          = json_encode($request->competencia);
        $syllabus->aprendizaje          = json_encode($request->aprendizaje);
        $syllabus->unidad               = json_encode($request->unidad);
        $syllabus->metodologia          = $request->metodologia;
        $syllabus->bibliografia         = json_encode($request->bibliografia);
        $syllabus->save();
        return $this->successResponse($this->setDataToCamelCase($syllabus), 200);
    }
    public function update(Request $request, $usuarioId, $cursoId, $syllabusId)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $validarCurso = $this->validarPreRequisitos($request->preRequisito);
        if (!$validarCurso) {
            return $this->errorResponse(404, "No se encontraron los cursos de pre-requisito, intentelo de nuevo.");
        }
        $syllabus = Syllabus::where('id', $syllabusId)->first();
        if (!$syllabus) {
            return $this->errorResponse(404, "No se encotro el syllabus, intentelo mas tarde.");
        }
        $syllabus->nro_creditos         = $request->nroCreditos ?: $syllabus->nro_creditos;
        $syllabus->area_conocimiento    = $request->areaConocimiento ?: $syllabus->area_conocimiento;
        $syllabus->semestre             = $request->semestre ?: $syllabus->semestre;
        $syllabus->pre_requisito        = json_encode($request->preRequisito) ?: $syllabus->pre_requisito;
        $syllabus->responsable_syllabus = $request->responsableSyllabus ?: $syllabus->responsable_syllabus;
        $syllabus->competencia          = json_encode($request->competencia) ?: $syllabus->competencia;
        $syllabus->aprendizaje          = json_encode($request->aprendizaje) ?: $syllabus->aprendizaje;
        $syllabus->unidad               = json_encode($request->unidad) ?: $syllabus->unidad;
        $syllabus->metodologia          = $request->metodologia ?: $syllabus->metodologia;
        $syllabus->bibliografia         = json_encode($request->bibliografia) ?: $syllabus->bibliografia;
        $syllabus->save();
        return $this->successResponse($this->setDataToCamelCase($syllabus), 200);
    }
    public function destroy($usuarioId, $cursoId, $id)
    {
        $user = User::where('id', $usuarioId)->first(['id']);
        if (!$user) {
            return $this->errorResponse(404, "No se encontro el usuario con identificador {$usuarioId}");
        }
        $curso = Curso::where('id', $cursoId)
            ->where('user_id', $user->id)->first(['id']);
        if (!$curso) {
            return $this->errorResponse(404, "No se encontro el curso con identificador {$cursoId}"
                . ', intetelo nuevamente');
        }
        $syllabus = Syllabus::where('id', $id)->first();
        $syllabus->delete();
        return $this->successMessageResponse("Se elimino el syllabus exitosamente", 200);
    }
    private function setDataToCamelCase($syllabus)
    {
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($syllabus, new SyllabusTransformer());
        $data = $manager->createData($resource);
        return $data;
    }
    private function validarPreRequisitos($cursos)
    {
        $cursoIds = $cursos[0];
        foreach ($cursoIds as $id) {
            $curso = Curso::where('id', $id)->first();
            if (!$curso) {
                return false;
            }
        }
        return true;
    }
}
