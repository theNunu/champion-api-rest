<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class championController extends Controller
{
    public function index() //funcion listar
    {
        try {
            $champions = Champion::where('state', 1)->get(); //llamo a model Champion

            if ($champions->isEmpty()) {
                $data = [
                    'message' => ' no existen campeones',
                    'status' => 200
                ];

                return response()->json($data, 200);
            }

            $data = [
                'message' => 'campeones encontrados',
                'champions' => $champions,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Throwable $e) { //Usar \Throwable en lugar de \Exception porque captura tanto excepciones como errores fatales.
            return response()->json([
                'message' => 'Error al obtener campeones',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {

        //Mantener las validaciones fuera del try (porque son parte del flujo normal).

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255'
            // 'email' => 'required|email|unique:student',
            // 'phone' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'error en la valiacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        try {
            $champion = Champion::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            if (!$champion) {
                $data = [
                    'message' => 'error al crear el estudiante',
                    'status' => 500
                ];
                return response()->json($data, 500);
            }

            $data = [
                'message' => 'se creo el campeon con exito',
                'campeon' => 'nombre: ' . $champion['name'] . ', descripcion: ' . $champion['description'],
                "id del campeon" => $champion['id'],
                'status' => 201
            ];

            return response()->json($data, 201);

        } catch (\Throwable $e) { //Usar \Throwable en lugar de \Exception porque captura tanto excepciones como errores fatales.
            return response()->json([
                'message' => 'Error al guardar el campeón',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $champion = Champion::find($id);

        if (!$champion) {  //si el mostrar falla
            $data = [
                'message' => 'campeon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        if ($champion->state == 0) {  //si el id le pertenece a un campeon eliminado
            $data = [
                'message' => 'ese campeon en concreto fue eliminado',
                'status' => 409
            ];
            return response()->json($data, 409);
        }

        $data = [ //si el mostrar funciona
            'message' => 'ese campeon si existe!!',
            'product' => $champion,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $champion = Champion::find($id);

        if (!$champion) { //si no hay campeones
            $data = [
                'message' => 'campeon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $champion->state = 0; // se cambia a 0 y se "elimina"
        $champion->save();

        $data = [
            'message' => 'campeon eliminado, su nombre fue ' . $champion['name'],
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {

        $champion = Champion::find($id);

        if (!$champion) {
            $data = [
                'message' => 'campeon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // 2. El campeón está eliminado lógicamente
        if ($champion->state == 0) {

            $data = [
                'message' => 'No se puede actualizar un campeón eliminado',
                'status' => 409
            ];
            return response()->json($data, 409); // Conflict
        }

        try {
            // 3. Intentar actualizar
            $champion->update($request->all());

            return response()->json([
                'message' => 'Campeón actualizado correctamente',
                'status' => 200,
                'data' => $champion
            ], 200);
        } catch (\Exception $e) {
            // 4. Algo falló al actualizar
            return response()->json([
                'message' => 'Error al actualizar el campeón',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
