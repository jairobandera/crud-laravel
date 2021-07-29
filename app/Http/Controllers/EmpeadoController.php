<?php

namespace App\Http\Controllers;

use App\Models\Empeado;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage; //Para poder eliminar imagen

class EmpeadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['empleados'] = Empeado::paginate(1); //Consulta datos y muestra los 5 primeros
        return view('empleado.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validar datos
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];

        $mnesaje=[
            'required'=>'El :attribute es requirido',
            'Foto.required'=>'La foto es requirida'
        ];

        $this->validate($request, $campos, $mnesaje);

        //$datosEmpleado = request()->all();
        $datosEmpleado = request()->except('_token'); //trae todos los datos del formuario menos el token de seguridad
        // Configurar para insertar imagen
        if($request->hasFile('Foto')){ //Si existe un archivo file
            $datosEmpleado['Foto'] = $request->file('Foto')->store('uploads', 'public'); //lo insertamos en el storage/upload
        }
    
        Empeado::insert($datosEmpleado); //inserta en la db
        //return response()->json($datosEmpleado);
        return redirect('empleado')->with('mensaje', 'Empleado agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empeado  $empeado
     * @return \Illuminate\Http\Response
     */
    public function show(Empeado $empeado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empeado  $empeado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $empleado=Empeado::findOrFail($id); //Buscamos la info del empleado con el id
        return view('empleado/edit', compact('empleado')); //compact('empleado') estoy pasando todos los datos de la variable $empleado
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empeado  $empeado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validar datos
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
        ];
        
        $mnesaje=[
            'required'=>'El :attribute es requirido',
        ];


        if($request->hasFile('Foto')){
            $campos=['Foto'=>'required|max:10000|mimes:jpeg,png,jpg',];
            $mnesaje=['Foto.required'=>'La foto es requirida'];
        }

        $this->validate($request, $campos, $mnesaje);
        
                
        $datosEmpleado = request()->except(['_token','_method']); //trae todos los datos del formuario menos el token y el motodo de seguridad
       
        if($request->hasFile('Foto')){ //Si existe un archivo file
            //Borramos la foto anterior
            $empleado=Empeado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto); //eliminamos la foto anteriro
            $datosEmpleado['Foto'] = $request->file('Foto')->store('uploads', 'public'); //Insertamos la nueva img
        }
       
        Empeado::where('id', '=', $id)->update($datosEmpleado);     //'id', '=', $id significa que compara si el id de la tabla es el mismo que paso por parametro
        
        // Una ves actualizado regreso al formulario de edit con datos actualizados
        $empleado=Empeado::findOrFail($id); //Buscamos la info del empleado con el id
        //return view('empleado/edit', compact('empleado')); //compact('empleado') estoy pasando todos los datos de la variable $empleado 
        return redirect('empleado')->with('mensaje', 'Empleado Modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empeado  $empeado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //Borrar imagenes del storage
        $empleado=Empeado::findOrFail($id);
        if(Storage::delete('public/'.$empleado->Foto)){ //si existe la img en storage, la borramos
            Empeado::destroy($id);//Borramos registro de Bd
        }        
        return redirect('empleado')->with('mensaje', 'Empleado borrado');
    }
}