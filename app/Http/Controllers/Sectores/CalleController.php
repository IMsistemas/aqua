<?php 
namespace App\Http\Controllers\Sectores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelos\Sectores\Provincia;
use App\Modelos\Sectores\Canton;
use App\Modelos\Sectores\Parroquia;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;

class CalleController extends Controller
{
    public function index()
    {
        return view('Sectores/calle');
    }

    public function getCalles()
    {
        return Calle::orderBy('nombrecalle', 'asc')->get();
    }

    public function getCallesById($id){
        return Calle::where('idbarrio', $id)->orderBy('nombrecalle')->get();
    }

    public function getCalleByBarrio($id){
        return Calle::where('idbarrio', $id)->orderBy('nombrecalle')->get();
    }


    public function getBarrios()
    {
        return Barrio::orderBy('nombrebarrio', 'asc')->get();
    }


    public function getLastID()
    {
        $max_calle = Calle::max('idcalle');

        if ($max_calle != null){
            $max_calle += 1;
        } else {
            $max_calle = 1;
        }
        return response()->json(['id' => $max_calle]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calle = new Calle();

        $calle->idbarrio = $request->input('idbarrio');
        $calle->nombrecalle = $request->input('nombrecalle');

        $calle->save();

        return response()->json(['success' => true]);

    }


    public function editar_calle(Request $request)
    {
        $callea = $request->input('arr_calle');

        foreach ($callea as $item) {
            $calle1 = Calle::find($item['idcalle']);

            $calle1->nombrecalle = $item['nombrecalle'];

            $calle1->save();
        }
        return response()->json(['success' => true]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calle = Calle::find($id);
        $calle->delete();
        return response()->json(['success' => true]);

    }

}