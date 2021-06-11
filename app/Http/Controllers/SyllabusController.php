<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Syllabus;
use App\Models\User;
use App\Transformers\SerializerCustom;
use App\Transformers\SyllabusTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class SyllabusController extends ApiController
{
    /**
     * Lista todos los syllabus registrados en la base de datos.
     */
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
    /**
     * muestra un syllabus en especifico.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param id: identificador del syllabus a listar.
     */
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
    /**
     * Registra un nuevo syllabus en la base de datos.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     */
    public function store(Request $request, $usuarioId, $cursoId)
    {
        $identifier = ["usuarioId" => $usuarioId, "cursoId" => $cursoId];
        $validator = Validator::make(array_merge($request->all(), $identifier), $this->rulesStoreValidation());
        if ($validator->fails()) {
            return $this->errorResponse(400, 'bad request.');
        }
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
    /**
     * Actualiza la informacion de un syllabus en especifico.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param syllabusId: identificador de un syllabus.
     */
    public function update(Request $request, $usuarioId, $cursoId, $syllabusId)
    {
        $identifier = ["usuarioId" => $usuarioId, "cursoId" => $cursoId, ' $syllabusId' => $syllabusId];
        $validator = Validator::make(array_merge($request->all(), $identifier), $this->rulesUpdateValidation());
        if ($validator->fails()) {
            return $this->errorResponse(400, 'bad request.');
        }
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
        if (is_null($request->preRequisito) == false) {

            $validarCurso = $this->validarPreRequisitos($request->preRequisito);
            if (!$validarCurso) {
                return $this->errorResponse(404, "No se encontraron los cursos de pre-requisito, intentelo de nuevo.");
            }
        }
        $syllabus = Syllabus::where('id', $syllabusId)->first();
        if (!$syllabus) {
            return $this->errorResponse(404, "No se encotro el syllabus, intentelo mas tarde.");
        }
        $preRequisito = $syllabus->pre_requisito;
        if ($request->preRequisito) {
            $preRequisito = json_encode($request->preRequisito);
        }
        $competencia = $syllabus->competencia;
        if ($request->preRequisito) {
            $competencia = json_encode($request->competencia);
        }
        $aprendizaje = $syllabus->aprendizaje;
        if ($request->aprendizaje) {
            $aprendizaje = json_encode($request->aprendizaje);
        }
        $unidad = $syllabus->unidad;
        if ($request->unidad) {
            $unidad = json_encode($request->unidad);
        }
        $bibliografia = $syllabus->bibliografia;
        if ($request->bibliografia) {
            $bibliografia = json_encode($request->bibliografia);
        }
        $syllabus->nro_creditos         = $request->nroCreditos ?: $syllabus->nro_creditos;
        $syllabus->area_conocimiento    = $request->areaConocimiento ?: $syllabus->area_conocimiento;
        $syllabus->semestre             = $request->semestre ?: $syllabus->semestre;
        $syllabus->pre_requisito        = $preRequisito;
        $syllabus->responsable_syllabus = $request->responsableSyllabus ?: $syllabus->responsable_syllabus;
        $syllabus->competencia          = $competencia;
        $syllabus->aprendizaje          = $aprendizaje;
        $syllabus->unidad               = $unidad;
        $syllabus->metodologia          = $request->metodologia ?: $syllabus->metodologia;
        $syllabus->bibliografia         = $bibliografia;
        $syllabus->save();
        return $this->successResponse($this->setDataToCamelCase($syllabus), 200);
    }
    /**
     * Elimina un syllabus de la base de datos.
     * @param usuarioId: identificador de un usuario.
     * @param cursoId: identificador de un curso.
     * @param id: identificador de un syllabus.
     */
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
    /**
     * Cambia la respuesta de la base de datos a camelCase
     * @param syllabus: instancia de un syllabus.
     */
    private function setDataToCamelCase($syllabus)
    {
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Item($syllabus, new SyllabusTransformer());
        $data = $manager->createData($resource);
        return $data;
    }
    /**
     * Valida el array de identificadores de un curso, si la lista
     * contiene identificadores de curso que no esten registrados
     * en la base de datos, retornara false, true de lo contrario.
     * @param cursos: lista de identificadores de curso.
     */
    private function validarPreRequisitos($cursos)
    {
        try {
            $cursoIds = $cursos[0];
            foreach ($cursoIds as $id) {
                $curso = Curso::where('id', $id)->first();
                if (!$curso) {
                    return false;
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    /**
     * Reglas para validar el registro de un syllabus.
     */
    private function rulesStoreValidation()
    {
        return [
            'usuarioId'             => 'required|Integer',
            'cursoId'               => 'required|Integer',
            'nroCreditos'           => 'required|Integer',
            'areaConocimiento'      => 'required|string',
            'semestre'              => "required|Integer",
            'preRequisito'          => 'required|array',
            'responsableSyllabus'   => "required|string",
            'competencia'           => "required|array",
            'aprendizaje'           => "required|array",
            'unidad'                => "required|array",
            'metodologia'           => "required|string",
            'bibliografia'          => "required|array",
        ];
    }
    /**
     * reglas para validar la actualizacion de un syllabus.
     */
    private function rulesUpdateValidation()
    {
        return [
            'usuarioId'             => 'required|Integer',
            'cursoId'               => 'required|Integer',
            'nroCreditos'           => 'nullable|Integer',
            'areaConocimiento'      => 'nullable|string',
            'semestre'              => "nullable|Integer",
            'preRequisito'          => 'nullable|array',
            'responsableSyllabus'   => "nullable|string",
            'competencia'           => "nullable|array",
            'aprendizaje'           => "nullable|array",
            'unidad'                => "nullable|array",
            'metodologia'           => "nullable|string",
            'bibliografia'          => "nullable|array",
        ];
    }
}
