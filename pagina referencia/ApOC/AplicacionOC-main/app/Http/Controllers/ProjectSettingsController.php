<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoordinadorProyecto;
use App\Models\TipoProyecto;
use App\Models\TipoServicio;

class ProjectSettingsController extends Controller
{
    public function index()
    {
        $coordinadores = CoordinadorProyecto::orderBy('nombre')->get();
        $tipoProyectos = TipoProyecto::orderBy('nombre')->get();
        $tipoServicios = TipoServicio::orderBy('nombre')->get();

        return view('oc.settings', compact('coordinadores', 'tipoProyectos', 'tipoServicios'));
    }

    // AJAX Store for the "+" button
    public function ajaxStore(Request $request)
    {
        $type = $request->input('type'); // 'coordinador', 'tipo_proyecto', 'tipo_servicio'
        $nombre = $request->input('nombre');

        if (!$nombre) return response()->json(['success' => false, 'message' => 'Nombre requerido']);

        try {
            $item = null;
            switch ($type) {
                case 'coordinador':
                    $item = CoordinadorProyecto::create(['nombre' => $nombre]);
                    break;
                case 'tipo_proyecto':
                    $item = TipoProyecto::create(['nombre' => $nombre]);
                    break;
                case 'tipo_servicio':
                    $item = TipoServicio::create(['nombre' => $nombre]);
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Tipo no válido']);
            }

            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ya existe o error de base de datos']);
        }
    }

    // Destroy methods for the management page
    public function destroyCoordinador($id)
    {
        CoordinadorProyecto::destroy($id);
        return redirect()->route('oc.config')
            ->with('success', 'Coordinador eliminado')
            ->with('alert_id', uniqid());
    }

    public function destroyTipoProyecto($id)
    {
        TipoProyecto::destroy($id);
        return redirect()->route('oc.config')
            ->with('success', 'Tipo de proyecto eliminado')
            ->with('alert_id', uniqid());
    }

    public function destroyTipoServicio($id)
    {
        TipoServicio::destroy($id);
        return redirect()->route('oc.config')
            ->with('success', 'Tipo de servicio eliminado')
            ->with('alert_id', uniqid());
    }
    
    // Quick store for management page
    public function storeCoordinador(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:coordinador_proyectos,nombre']);
        CoordinadorProyecto::create($request->only('nombre'));
        return redirect()->route('oc.config')
            ->with('success', 'Coordinador añadido')
            ->with('alert_id', uniqid());
    }

    public function storeTipoProyecto(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:tipo_proyectos,nombre']);
        TipoProyecto::create($request->only('nombre'));
        return redirect()->route('oc.config')
            ->with('success', 'Tipo de proyecto añadido')
            ->with('alert_id', uniqid());
    }

    public function storeTipoServicio(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:tipo_servicios,nombre']);
        TipoServicio::create($request->only('nombre'));
        return redirect()->route('oc.config')
            ->with('success', 'Tipo de servicio añadido')
            ->with('alert_id', uniqid());
    }
}
