<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cliente;
use App\Models\Modulo;
use App\Models\Regla;
use App\Models\ReglasCondicion;
use App\Models\TipoIncidente;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ReglasPrioridadController extends Controller
{
	public function index()
	{
		//$tipo_incidente = TipoIncidente::orderBy('descripcion')->get();	

		$reglas = Regla::paginate(20);

		return view('admin.reglas', compact('reglas'));
	}

	public function crearRegla()
	{
		
		$usuarios = User::where('tipo', 0)->orderBy('nombre')->get()->pluck('nombre', 'idT')->toArray();
		$clientes = Cliente::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$areas = Area::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$modulos = Modulo::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$tipo_incidentes = TipoIncidente::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$tipo_servicios = TipoServicio::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();


		return view('admin.reglas-crear', compact('usuarios', 'areas', 
			'tipo_incidentes', 'tipo_servicios', 'modulos', 'clientes'));
	}

	public function guardarRegla(Request $request)
	{
		//dd($request->all()); exit();

		$request->validate([
			'descripcion' => 'required|max:100|unique:reglas,descripcion',
			'condiciones' => 'required'
		]);

		$regla = array();
		$regla['descripcion'] = $request->descripcion;
		$regla['pondera'] = $request->prioridad;
		$regla['activo'] = 1;
		$res = Regla::create($regla);

		$res2 = ReglasCondicion::where('regla', $res->id)->delete();

		for ($i=0; $i < count($request->condiciones); ++$i)
		{
			$rc = new ReglasCondicion;
			$rc->regla = $res->id;
			$rc->valor = $request->condiciones[$i];
			$rc->operador = $request->operador[$i];
			$rc->igual = $request->condiciones[$i]=='dia'? $request->igual[$i] : $request->seleccion[$i];
			$rc->minimo = $request->minimo[$i];
			$rc->maximo = $request->maximo[$i];
			$rc->helper = $request->texto[$i];

			$res = $rc->save();
		}

		if ($res)
		{
			return back()->with('message', 'Se guardó la regla correctamente');
		}
		else
		{
			return back()->with('error', 'Hubo un error al guardar la regla');
		}

	}

	public function editarRegla($id)
	{
		$regla = Regla::with('condiciones')->find($id);

		$usuarios = User::where('tipo', 0)->orderBy('nombre')->get()->pluck('nombre', 'idT')->toArray();
		$clientes = Cliente::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$areas = Area::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$modulos = Modulo::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$tipo_incidentes = TipoIncidente::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();
		$tipo_servicios = TipoServicio::orderBy('descripcion')->get()->pluck('descripcion', 'codigo')->toArray();

		return view('admin.reglas-editar', compact('regla', 'usuarios', 'areas', 
			'tipo_incidentes', 'tipo_servicios', 'modulos', 'clientes'));
	}

	public function modificarRegla(Request $request, $id)
	{
		//dd($request->all()); exit();

		$regla = Regla::find($id);

		$request->validate([
			'descripcion' => 'required|max:100|unique:reglas,descripcion,'.$regla->descripcion,
			'condiciones' => 'required'
		]);

		$regla->descripcion = $request->descripcion;
		$regla->pondera = $request->prioridad;
		$res = $regla->save();

		$res = ReglasCondicion::where('regla', $id)->delete();

		for ($i=0; $i < count($request->condiciones); ++$i)
		{
			$rc = new ReglasCondicion;
			$rc->regla = $id;
			$rc->valor = $request->condiciones[$i];
			$rc->operador = $request->operador[$i];
			$rc->igual = $request->condiciones[$i]=='dia'? $request->igual[$i] : $request->seleccion[$i];
			$rc->minimo = $request->minimo[$i];
			$rc->maximo = $request->maximo[$i];
			$rc->helper = $request->texto[$i];

			$res = $rc->save();
		}
			
		if ($res)
		{
			return back()->with('message', 'Se guardaron los cambios correctamente');
		}
		else
		{
			return back()->with('error', 'Hubo un error al guardar los cambios');
		}
	}

	public function habilitarRegla($id)
	{
		$regla = Regla::find($id);
		$regla->activo = $regla->activo? 0 : 1;
		$regla->save();

		return response($regla);
	}

	public function eliminarRegla($id)
	{
		$regla = Regla::find($id);
		$res = $regla->delete();

		if ($res) {
			$res = ReglasCondicion::where('regla', $id)->delete();
		}

		if ($res)
		{
			return back()->withMessage('Se eliminó la regla seleccionado');
		}
		else
		{
			return back()->withError('Hubo un error al eliminar la regla');
		}
	}


	public static function verificarRegla($regla, $inc)
	{

		foreach ($regla->condiciones as $cond)
		{
			if ($cond->valor == 'dia')
			{
				$dia = date('d', strtotime($inc['fecha_ingreso']));

				if ($cond->operador=='igual')
				{
					if ($dia != $cond->igual) return 0;
				}

				elseif ($cond->operador=='entre')
				{
					if ($dia < $cond->minimo) return 0;
					if ($dia > $cond->maximo) return 0;
				}
			}

			elseif ($cond->valor == 'remitente')
			{
				if ($cond->igual != User::where('Usuario', $inc['usuario'])->first()->idT)
					return 0;
			}

			elseif ($cond->valor == 'tipo_servicio')
			{
				if ($cond->igual != Cliente::where('codigo', $inc['cliente'])->first()->servicio->codigo)
					return 0;
			}

			elseif ($cond->valor == 'cliente')
			{
				if ($cond->igual != $inc['cliente'])
					return 0;
			}

			elseif ($cond->valor == 'area')
			{
				if ($cond->igual != $inc['area'])
					return 0;
			}

			elseif ($cond->valor == 'modulo')
			{
				if ($cond->igual != $inc['modulo'])
					return 0;
			}

			elseif ($cond->valor == 'tipo_incidente')
			{
				if ($cond->igual != $inc['tipo_incidente'])
					return 0;
			}

		}

		return $regla->pondera;
	}

}