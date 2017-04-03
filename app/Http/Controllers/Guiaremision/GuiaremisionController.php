<?php

namespace App\Http\Controllers\Guiaremision;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use App\Modelos\Persona;
use App\Modelos\Transportista\Transportista;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Contabilidad\Cont_DocumentoGuiaRemision;
use App\Modelos\Contabilidad\Cont_DocumentoGuiaRemisionMerc;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
use App\Modelos\Contabilidad\Cont_ItemVenta;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use DB;



class GuiaremisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Guiaremision/index_guiaremision');
        //view('Guiaremision/from_guiaremision');

    }

    public function show()
    {
        return $guias=DB::table('cont_documentoguiaremision')
        ->join('cliente','cliente.idcliente','=','cont_documentoguiaremision.idcliente')
        ->join('persona','persona.idpersona','=','cliente.idpersona')
        ->select(DB::raw("persona.razonsocial,cont_documentoguiaremision.iddocumentoguiaremision,cont_documentoguiaremision.nrodocumentoguiaremision,cont_documentoguiaremision.iddocumentoventa, (select cont_documentoventa.numdocumentoventa  from  cont_documentoventa  join cont_documentoguiaremision on cont_documentoventa.iddocumentoventa= cont_documentoguiaremision.iddocumentoventa 
            where cont_documentoguiaremision.iddocumentoventa = cont_documentoventa.iddocumentoventa   limit 1
        )"))
        ->get();


    }

    public function GetTrasportista($texto)
    {
        
        return Persona::join('transportista','transportista.idpersona','=','persona.idpersona')
        ->whereRaw("numdocidentific::text LIKE '%" . $texto . "%'")
        ->get() ;
    }

    public function GetVentanro($texto)
    {
        return $venta=Cont_DocumentoVenta::whereRaw("numdocumentoventa::text LIKE '%" . $texto . "%'")
        ->get() ;
    }

    public function BuscarDestinatario($texto)
    {
        return Persona::join('cliente','cliente.idpersona','=','persona.idpersona')
        ->whereRaw("numdocidentific::text LIKE '%" . $texto . "%'")
        ->get() ;

    }
    //Buscar la venta
    public function BuscarVenta($texto)
    {
        $venta=Cont_DocumentoVenta::
        whereRaw("numdocumentoventa::text LIKE '%" . $texto . "%'")
        ->select('cont_documentoventa.nroautorizacionventa','cont_documentoventa.nroguiaremision','cont_documentoventa.fechaemisionventa','cont_documentoventa.iddocumentoventa')
        ->first();

        $productos=Cont_ItemVenta::join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemventa.idcatalogitem')
        ->join('cont_categoria','cont_categoria.idcategoria','=','cont_catalogitem.idcategoria')
        ->where('cont_itemventa.iditemventa','=',$venta->iddocumentoventa)
        ->select('cont_categoria.nombrecategoria','cont_catalogitem.codigoproducto','cont_catalogitem.nombreproducto','cont_catalogitem.codigoproducto','cont_itemventa.idcatalogitem','cont_itemventa.cantidad')
        ->get();
        return response()->json([
                'venta' => $venta,
                'productos' => $productos
                ]);
    }

    public function store(Request $request)
    {
        if ($request->input('nrodocventa')==null) {
            $cliente=DB::table('cliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('cliente.idcliente')
            ->where('persona.numdocidentific','=',$request->input('cidestinatario'))->first();
            $transportista=DB::table('transportista')
            ->join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('transportista.idtransportista')
            ->where('persona.numdocidentific','=',$request->input('citransportista'))->first();

            $guiaremision = new Cont_DocumentoGuiaRemision();
            $guiaremision->idtransportista = $transportista->idtransportista;
            $guiaremision->idcliente = $cliente->idcliente;
            $guiaremision->nrodocumentoguiaremision = $request->input('nrodocumentoguiaremision');
            $guiaremision->iddocumentoventa = $request->input('iddocumentoventa');
            $guiaremision->nrodeclaracionaduana = $request->input('nrodeclaracionaduana');
            $guiaremision->codestablecdestino = $request->input('codestablecdestino');
            $guiaremision->ruta = $request->input('ruta');
            $guiaremision->fechainiciotransp = $request->input('fechainiciotransp');
            $guiaremision->fechafintransp = $request->input('fechafintransp');
            $guiaremision->motivotraslado = $request->input('motivotraslado');
            $guiaremision->direccdestinatario = $request->input('direccdestinatario');
            $guiaremision->puntopartida = $request->input('puntopartida');
            $guiaremision->estaanulada = false;
            $guiaremision->save();

            $guia=Cont_DocumentoGuiaRemision::orderBy('iddocumentoguiaremision', 'desc')->first();

            $detallemer=$request->input('detallemer');

            foreach ($detallemer as $i => $value) {
                $guiaremisionmerc = new Cont_DocumentoGuiaRemisionMerc();
                $guiaremisionmerc->iddocumentoguiaremision = $guia->iddocumentoguiaremision;
                $guiaremisionmerc->cantidad = $detallemer[$i]['cantidad'];
                $guiaremisionmerc->peso = $detallemer[$i]['peso'];
                $guiaremisionmerc->largo = $detallemer[$i]['largo'];
                $guiaremisionmerc->ancho = $detallemer[$i]['ancho'];
                $guiaremisionmerc->altura = $detallemer[$i]['altura'];
                $guiaremisionmerc->tipoempaque = $detallemer[$i]['tipoempaque'];
                $guiaremisionmerc->descripcion = $detallemer[$i]['descripcion'];
                $guiaremisionmerc->save();
            };
            return response()->json([
                    'success' => true
                    ]);
        }else{
            $venta=DB::table('cont_documentoventa')
            ->select('cont_documentoventa.iddocumentoventa')
            ->where('cont_documentoventa.numdocumentoventa','=',$request->input('nrodocventa'))->first();
            $cliente=DB::table('cliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('cliente.idcliente')
            ->where('persona.numdocidentific','=',$request->input('cidestinatario'))->first();
            $transportista=DB::table('transportista')
            ->join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('transportista.idtransportista')
            ->where('persona.numdocidentific','=',$request->input('citransportista'))->first();

            $guiaremision = new Cont_DocumentoGuiaRemision();
            $guiaremision->idtransportista = $transportista->idtransportista;
            $guiaremision->idcliente = $cliente->idcliente;
            $guiaremision->iddocumentoventa = $venta->iddocumentoventa;
            $guiaremision->nrodocumentoguiaremision = $request->input('nrodocumentoguiaremision');
            $guiaremision->nrodeclaracionaduana = $request->input('nrodeclaracionaduana');
            $guiaremision->codestablecdestino = $request->input('codestablecdestino');
            $guiaremision->ruta = $request->input('ruta');
            $guiaremision->fechainiciotransp = $request->input('fechainiciotransp');
            $guiaremision->fechafintransp = $request->input('fechafintransp');
            $guiaremision->motivotraslado = $request->input('motivotraslado');
            $guiaremision->direccdestinatario = $request->input('direccdestinatario');
            $guiaremision->puntopartida = $request->input('puntopartida');
            $guiaremision->estaanulada = false;
            $guiaremision->save();

            $guia=Cont_DocumentoGuiaRemision::orderBy('iddocumentoguiaremision', 'desc')->first();

            $detallemer=$request->input('detallemer');

            foreach ($detallemer as $i => $value) {
                $guiaremisionmerc = new Cont_DocumentoGuiaRemisionMerc();
                $guiaremisionmerc->iddocumentoguiaremision = $guia->iddocumentoguiaremision;
                $guiaremisionmerc->cantidad = $detallemer[$i]['cantidad'];
                $guiaremisionmerc->peso = $detallemer[$i]['peso'];
                $guiaremisionmerc->largo = $detallemer[$i]['largo'];
                $guiaremisionmerc->ancho = $detallemer[$i]['ancho'];
                $guiaremisionmerc->altura = $detallemer[$i]['altura'];
                $guiaremisionmerc->tipoempaque = $detallemer[$i]['tipoempaque'];
                $guiaremisionmerc->descripcion = $detallemer[$i]['descripcion'];
                $guiaremisionmerc->save();
            };
            
            return response()->json([
                    'success' => true
                    ]);
            }
    }

    public function getGuia($idguiaremision){
        $guiaremision=Cont_DocumentoGuiaRemision::find($idguiaremision);
        if($guiaremision->iddocumentoventa == null){         
            $transportista=Transportista::join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('persona.razonsocial','transportista.placa','persona.numdocidentific')
            ->where('transportista.idtransportista','=',$guiaremision->idtransportista)
            ->get();
            $destinatario=Persona::join('cliente','cliente.idpersona','=','persona.idpersona')
            ->select('persona.razonsocial','persona.direccion','persona.numdocidentific')
            ->where('cliente.idcliente','=',$guiaremision->idcliente)
            ->get();
            $mercaderia=Cont_DocumentoGuiaRemisionMerc::join('cont_documentoguiaremision','cont_documentoguiaremision.iddocumentoguiaremision','=','cont_documentoguiaremisionmerc.iddocumentoguiaremision')
            ->where('cont_documentoguiaremisionmerc.iddocumentoguiaremision','=',$idguiaremision)
            ->get();

            return response()->json([
                'transportista' => $transportista,
                'destinatario' => $destinatario,
                'guiaremision' => $guiaremision,
                'mercaderia' => $mercaderia
                ]);
        }else{
            $transportista=Transportista::join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('persona.razonsocial','transportista.placa','persona.numdocidentific')
            ->where('transportista.idtransportista','=',$guiaremision->idtransportista)
            ->get();
            $destinatario=Persona::join('cliente','cliente.idpersona','=','persona.idpersona')
            ->select('persona.razonsocial','persona.direccion','persona.numdocidentific')
            ->where('cliente.idcliente','=',$guiaremision->idcliente)
            ->get();
            $mercaderia=Cont_DocumentoGuiaRemisionMerc::join('cont_documentoguiaremision','cont_documentoguiaremision.iddocumentoguiaremision','=','cont_documentoguiaremisionmerc.iddocumentoguiaremision')
            ->where('cont_documentoguiaremisionmerc.iddocumentoguiaremision','=',$idguiaremision)
            ->get();
            $venta=Cont_DocumentoVenta::
            where('cont_documentoventa.iddocumentoventa','=',$guiaremision->iddocumentoventa)
            ->select('cont_documentoventa.numdocumentoventa','cont_documentoventa.nroautorizacionventa','cont_documentoventa.nroguiaremision','cont_documentoventa.fechaemisionventa','cont_documentoventa.iddocumentoventa')
            ->first();

            $productos=Cont_ItemVenta::join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemventa.idcatalogitem')
            ->join('cont_categoria','cont_categoria.idcategoria','=','cont_catalogitem.idcategoria')
            ->where('cont_itemventa.iditemventa','=',$venta->iddocumentoventa)
            ->select('cont_categoria.nombrecategoria','cont_catalogitem.codigoproducto','cont_catalogitem.nombreproducto','cont_catalogitem.codigoproducto','cont_itemventa.idcatalogitem','cont_itemventa.cantidad')
            ->get();
            return response()->json([
                'transportista' => $transportista,
                'destinatario' => $destinatario,
                'guiaremision' => $guiaremision,
                'venta' => $venta,
                'productos' => $productos,
                'mercaderia' => $mercaderia
                ]);
        }
    }

    public function update(Request $request, $id){
        if($request->input('nrodocventa')==null){
            $cliente=DB::table('cliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('cliente.idcliente')
            ->where('persona.numdocidentific','=',$request->input('cidestinatario'))->first();

            $transportista=DB::table('transportista')
            ->join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('transportista.idtransportista')
            ->where('persona.numdocidentific','=',$request->input('citransportista'))->first();

            $guiaremision = Cont_DocumentoGuiaRemision::find($id);
            $guiaremision->idtransportista = $transportista->idtransportista;
            $guiaremision->idcliente = $cliente->idcliente;
            $guiaremision->nrodocumentoguiaremision = $request->input('nrodocumentoguiaremision');
            $guiaremision->nrodeclaracionaduana = $request->input('nrodeclaracionaduana');
            $guiaremision->codestablecdestino = $request->input('codestablecdestino');
            $guiaremision->ruta = $request->input('ruta');
            $guiaremision->fechainiciotransp = $request->input('fechainiciotransp');
            $guiaremision->fechafintransp = $request->input('fechafintransp');
            $guiaremision->motivotraslado = $request->input('motivotraslado');
            $guiaremision->direccdestinatario = $request->input('direccdestinatario');
            $guiaremision->puntopartida = $request->input('puntopartida');
            $guiaremision->estaanulada = false;
            $guiaremision->save();

            $detallemer=$request->input('detallemer');
            foreach ($detallemer as $i => $value) {
                $guiaremisionmerc = Cont_DocumentoGuiaRemisionMerc::where('iddocumentoguiaremision','=',$guiaremision->iddocumentoguiaremision)
                    ->first();
                $guiaremisionmerc->iddocumentoguiaremision = $guiaremision->iddocumentoguiaremision;
                $guiaremisionmerc->cantidad = $detallemer[$i]['cantidad'];
                $guiaremisionmerc->peso = $detallemer[$i]['peso'];
                $guiaremisionmerc->largo = $detallemer[$i]['largo'];
                $guiaremisionmerc->ancho = $detallemer[$i]['ancho'];
                $guiaremisionmerc->altura = $detallemer[$i]['altura'];
                $guiaremisionmerc->tipoempaque = $detallemer[$i]['tipoempaque'];
                $guiaremisionmerc->descripcion = $detallemer[$i]['descripcion'];
                $guiaremisionmerc->save();
            };
            
            return response()->json([
                    'success' => true
                    ]);
        }else{
            $cliente=DB::table('cliente')
            ->join('persona','persona.idpersona','=','cliente.idpersona')
            ->select('cliente.idcliente')
            ->where('persona.numdocidentific','=',$request->input('cidestinatario'))
            ->first();

            $transportista=DB::table('transportista')
            ->join('persona','persona.idpersona','=','transportista.idpersona')
            ->select('transportista.idtransportista')
            ->where('persona.numdocidentific','=',$request->input('citransportista'))
            ->first();

            $venta=DB::table('cont_documentoventa')
            ->select('cont_documentoventa.iddocumentoventa')
            ->where('cont_documentoventa.numdocumentoventa','=',$request->input('nrodocventa'))
            ->first();
            $guiaremision = Cont_DocumentoGuiaRemision::find($id);
            $guiaremision->idtransportista = $transportista->idtransportista;
            $guiaremision->idcliente = $cliente->idcliente;
            $guiaremision->nrodocumentoguiaremision = $request->input('nrodocumentoguiaremision');
            $guiaremision->iddocumentoventa = $venta->iddocumentoventa;
            $guiaremision->nrodeclaracionaduana = $request->input('nrodeclaracionaduana');
            $guiaremision->codestablecdestino = $request->input('codestablecdestino');
            $guiaremision->ruta = $request->input('ruta');
            $guiaremision->fechainiciotransp = $request->input('fechainiciotransp');
            $guiaremision->fechafintransp = $request->input('fechafintransp');
            $guiaremision->motivotraslado = $request->input('motivotraslado');
            $guiaremision->direccdestinatario = $request->input('direccdestinatario');
            $guiaremision->puntopartida = $request->input('puntopartida');
            $guiaremision->estaanulada = false;
            $guiaremision->save();

            $detallemer=$request->input('detallemer');

            foreach ($detallemer as $i => $value) {
                $guiaremisionmerc = Cont_DocumentoGuiaRemisionMerc::where('iddocumentoguiaremision','=',$guiaremision->iddocumentoguiaremision)
                    ->first();
                $guiaremisionmerc->iddocumentoguiaremision = $guiaremision->iddocumentoguiaremision;
                $guiaremisionmerc->cantidad = $detallemer[$i]['cantidad'];
                $guiaremisionmerc->peso = $detallemer[$i]['peso'];
                $guiaremisionmerc->largo = $detallemer[$i]['largo'];
                $guiaremisionmerc->ancho = $detallemer[$i]['ancho'];
                $guiaremisionmerc->altura = $detallemer[$i]['altura'];
                $guiaremisionmerc->tipoempaque = $detallemer[$i]['tipoempaque'];
                $guiaremisionmerc->descripcion = $detallemer[$i]['descripcion'];
                $guiaremisionmerc->save();
            };
            
            return response()->json([
                    'success' => true
                    ]);
            }
    }
    public function destroy($id)
    {
        $mercaderias = Cont_DocumentoGuiaRemisionMerc::where('iddocumentoguiaremision',$id)->get();
        foreach ($mercaderias as $mercaderia) {
            $mercaderia->delete();
        }
        $guia = Cont_DocumentoGuiaRemision::find($id);
        $guia->delete();
        return response()->json(['success' => true]);
    }
}
