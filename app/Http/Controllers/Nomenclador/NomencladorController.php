<?php

namespace App\Http\Controllers\Nomenclador;

use App\Modelos\SRI\SRI_TipoDocumento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\SRI\SRI_TipoIdentificacion;
use App\Modelos\SRI\SRI_TipoImpuesto;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use App\Modelos\SRI\SRI_TipoImpuestoIce;
use App\Modelos\SRI\SRI_TipoImpuestoRetencion;
use App\Modelos\SRI\SRI_DetalleImpuestoRetencion;
use App\Modelos\SRI\SRI_SustentoTributario;
use App\Modelos\SRI\SRI_TipoComprobante;
use App\Modelos\SRI\SRI_PagoResidente;
use App\Modelos\SRI\SRI_PagoPais;
use App\Modelos\SRI\SRI_Sustento_Comprobante;
use App\Modelos\SRI\SRI_Sustento_Comprobantev1;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Sectores\Provincia;
/*use App\Modelos\Sectores\Emp_Canton;
use App\Modelos\Sectores\Emp_Parroquia;*/
use App\Modelos\Sectores\Canton;
use App\Modelos\Sectores\Parroquia;


class NomencladorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Nomenclador/index_nomenclador');
        //return $this->CargadataProvincia();
    }

    public function getTipoDocumento(Request $request)

    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRItipodocumento = null;

        if ($search != null and !empty($search)) {
            $SRItipodocumento = SRI_TipoDocumento::whereRaw("sri_tipodocumento.nametipodocumento ILIKE '%" . $search . "%'")->orderBy('nametipodocumento', 'asc');
            return $SRItipodocumento->paginate(8);
        }
        else{
            $SRItipodocumento = SRI_TipoDocumento::orderBy('nametipodocumento', 'asc');
            return $SRItipodocumento->paginate(8);
        }
    }

    public function gettipoidentificacion(Request $request)

    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRItipoidentificacion = null;

        if ($search != null) {
            $SRItipoidentificacion = sri_tipoidentificacion::whereRaw("sri_tipoidentificacion.nameidentificacion ILIKE '%" . $search . "%'")->orderBy('nameidentificacion', 'asc');
            return $SRItipoidentificacion->paginate(8);
        }
        else{

            $SRItipoidentificacion = SRI_TipoIdentificacion::orderBy('nameidentificacion', 'asc');
            return $SRItipoidentificacion->paginate(8);
        }

    }

    public function getTipoImpuestoEx(Request $request)

    {

        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRITipoImpuesto = null;

        if ($search != null) {
            $SRITipoImpuesto = SRI_TipoImpuesto::whereRaw("SRI_TipoImpuesto.nameimpuesto ILIKE '%" . $search . "%'")->orderBy('nameimpuesto', 'asc');
            return $SRITipoImpuesto->paginate(100);
        }
        else{

            $SRITipoImpuesto = SRI_TipoImpuesto::orderBy('nameimpuesto', 'asc');
            return $SRITipoImpuesto->paginate(100);

        }

    }

    public function getTipoImpuesto(Request $request)

    {

        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRITipoImpuesto = null;

        if ($search != null) {
            $SRITipoImpuesto = SRI_TipoImpuesto::whereRaw("SRI_TipoImpuesto.nameimpuesto ILIKE '%" . $search . "%'")->orderBy('nameimpuesto', 'asc');
            return $SRITipoImpuesto->paginate(8);
        }
        else{

            $SRITipoImpuesto = SRI_TipoImpuesto::orderBy('nameimpuesto', 'asc');
            return $SRITipoImpuesto->paginate(8);

        }

    }

    public function getImpuestoIVA(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $SRIImpuestoIVA  = null;

        if ($search != null) {

            return SRI_TipoImpuestoIva::join('sri_tipoimpuesto', 'sri_tipoimpuestoiva.idtipoimpuesto', '=', 'sri_tipoimpuesto.idtipoimpuesto')
                ->select('sri_tipoimpuestoiva.*', 'sri_tipoimpuesto.idtipoimpuesto')
                ->where('sri_tipoimpuestoiva.nametipoimpuestoiva','ILIKE','%' . $search . '%')
                ->orderBy ('nametipoimpuestoiva','asc')->paginate(8);

        }
        else{
            return SRI_TipoImpuestoIva::join('sri_tipoimpuesto', 'sri_tipoimpuestoiva.idtipoimpuesto', '=', 'sri_tipoimpuesto.idtipoimpuesto')
                ->select('sri_tipoimpuestoiva.*', 'sri_tipoimpuesto.idtipoimpuesto')
                ->orderBy ('nametipoimpuestoiva','asc')->paginate(8);

        }
    }

    public function getImpuestoICE(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        //$SRIImpuestoICE  = null;

        if ($search != null) {

            return SRI_TipoImpuestoIce::join('sri_tipoimpuesto', 'sri_tipoimpuestoice.idtipoimpuesto', '=', 'sri_tipoimpuesto.idtipoimpuesto')
                ->select('sri_tipoimpuestoice.*', 'sri_tipoimpuesto.idtipoimpuesto')
                ->where('sri_tipoimpuestoice.nametipoimpuestoice','ILIKE','%' . $search . '%')
                ->orderBy ('nametipoimpuestoice','asc')->paginate(8);

        }
        else{
            return SRI_TipoImpuestoIce::join('sri_tipoimpuesto', 'sri_tipoimpuestoice.idtipoimpuesto', '=', 'sri_tipoimpuesto.idtipoimpuesto')
                ->select('sri_tipoimpuestoice.*', 'sri_tipoimpuesto.idtipoimpuesto')
                ->orderBy ('nametipoimpuestoice','asc')->paginate(8);

        }
    }

    public function getTipoImpuestoRetenc(Request $request)

    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRITipoImpuestoRten = null;

        if ($search != null) {
            $SRITipoImpuestoRten = SRI_TipoImpuestoRetencion::whereRaw("sri_tipoimpuestoretencion.nametipoimpuestoretencion ILIKE '%" . $search . "%'")->orderBy('nametipoimpuestoretencion', 'asc');
            return $SRITipoImpuestoRten->paginate(8);
        }
        else{

            $SRITipoImpuestoRten = SRI_TipoImpuestoRetencion::orderBy('nametipoimpuestoretencion', 'asc');
            return $SRITipoImpuestoRten->paginate(8);
        }
    }

    public function getImpuestoIVARENTA(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $SRITipoImpuestoRteniva  = null;

        if ($search != null) {

            return SRI_DetalleImpuestoRetencion::with('cont_plancuenta')
                ->join('sri_tipoimpuestoretencion', 'sri_detalleimpuestoretencion.idtipoimpuestoretencion', '=', 'sri_tipoimpuestoretencion.idtipoimpuestoretencion')
                ->select('sri_detalleimpuestoretencion.*', 'sri_tipoimpuestoretencion.nametipoimpuestoretencion')
                ->where('sri_detalleimpuestoretencion.namedetalleimpuestoretencion','ILIKE','%' . $search . '%')
                ->orderBy ('namedetalleimpuestoretencion','asc')->paginate(5);

        }
        else{
            return SRI_DetalleImpuestoRetencion::with('cont_plancuenta')
                ->join('sri_tipoimpuestoretencion', 'sri_detalleimpuestoretencion.idtipoimpuestoretencion', '=', 'sri_tipoimpuestoretencion.idtipoimpuestoretencion')
                ->select('sri_detalleimpuestoretencion.*', 'sri_tipoimpuestoretencion.nametipoimpuestoretencion')
                ->orderBy ('namedetalleimpuestoretencion','asc')->paginate(5);

        }

    }

    public function getSustentoTributario(Request $request)

    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRISustento = null;

        if ($search != null) {
            $SRISustento = SRI_SustentoTributario::whereRaw("sri_sustentotributario.namesustento ILIKE '%" . $search . "%'")->orderBy('namesustento', 'asc');
            return $SRISustento->paginate(8);
        }
        else{

            $SRISustento = SRI_SustentoTributario::orderBy('namesustento', 'asc');
            return $SRISustento->paginate(8);
        }
    }

    public function getSustentoTributarioEX(Request $request)

    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRISustento = null;

        if ($search != null) {
            $SRISustento = SRI_SustentoTributario::whereRaw("sri_sustentotributario.namesustento ILIKE '%" . $search . "%'")->orderBy('namesustento', 'asc');
            return $SRISustento->paginate(500);
        }
        else{

            $SRISustento = SRI_SustentoTributario::orderBy('namesustento', 'asc');
            return $SRISustento->paginate(500);
        }
    }

    public function getTipoComprobante(Request $request)
        // Caso especial se va hacer de Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRITipoComp = null;

        if ($search != null) {


            return SRI_Sustento_Comprobante::join('sri_tipocomprobante', 'sri_sustento_comprobante.idtipocomprobante', '=', 'sri_tipocomprobante.idtipocomprobante')
                -> join('sri_sustentotributario', 'sri_sustento_comprobante.idsustentotributario', '=', 'sri_sustentotributario.idsustentotributario')
                ->select('sri_tipocomprobante.*', 'sri_sustentotributario.namesustento')
                ->where('sri_tipocomprobante.namecomprobante','ILIKE','%' . $search . '%')
                ->orderBy('sri_sustentotributario.namesustento', 'asc')
                ->orderBy('namecomprobante', 'asc')->paginate(8);

        }
        else{


            return SRI_Sustento_Comprobante::join('sri_tipocomprobante', 'sri_sustento_comprobante.idtipocomprobante', '=', 'sri_tipocomprobante.idtipocomprobante')
                -> join('sri_sustentotributario', 'sri_sustento_comprobante.idsustentotributario', '=', 'sri_sustentotributario.idsustentotributario')
                ->select('sri_tipocomprobante.*', 'sri_sustentotributario.namesustento')
                //->where('sri_tipocomprobante.namecomprobante','ILIKE','%" . $search . "%')
                ->orderBy('sri_sustentotributario.namesustento', 'asc')
                ->orderBy('namecomprobante', 'asc')->paginate(8);



        }
    }

    public function getPagoResidente(Request $request)
        // Caso especial se va hacer de Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRIPagoResidente = null;

        if ($search != null) {
            $SRIPagoResidente = SRI_PagoResidente::whereRaw("sri_pagoresidente.tipopagoresidente ILIKE '%" . $search . "%'")->orderBy('tipopagoresidente', 'asc');
            return $SRIPagoResidente->paginate(8);
        }
        else{

            $SRIPagoResidente = SRI_PagoResidente::orderBy('tipopagoresidente', 'asc');
            return $SRIPagoResidente->paginate(8);
        }
    }


    public function getPagoPais(Request $request)
        // Caso especial se va hacer de Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $SRIPagoPais = null;

        if ($search != null) {
            $SRIPagoPais = SRI_PagoPais::whereRaw("sri_pagopais.pais ILIKE '%" . $search . "%'")->orderBy('pais', 'asc');
            return $SRIPagoPais->paginate(8);
        }
        else{

            $SRIPagoPais = SRI_PagoPais::orderBy('pais', 'asc');
            return $SRIPagoPais->paginate(8);
        }
    }


    public function getContFormaPago(Request $request)
        // Caso especial se va hacer CargadataTPidentde Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $FormaPago = null;

        if ($search != null) {
            $FormaPago = Cont_FormaPago::whereRaw("cont_formapago.nameformapago ILIKE '%" . $search . "%'")->orderBy('nameformapago', 'asc');
            return $FormaPago->paginate(8);
        }
        else{

            $FormaPago = Cont_FormaPago::orderBy('nameformapago', 'asc');
            return $FormaPago->paginate(8);
        }
    }

    public function getprovincia(Request $request)
        // Caso especial se va hacer de Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $Provin = null;

        if ($search != null) {
            $Provin = Provincia::whereRaw("provincia.nameprovincia ILIKE '%" . $search . "%'")->orderBy('nameprovincia', 'asc');
            return $Provin->paginate(8);
        }
        else{

            $Provin = Provincia::orderBy('nameprovincia', 'asc');
            return $Provin->paginate(8);
        }

    }

    public function getprovinciaEX(Request $request)
        // Caso especial se va hacer de Ultimo
    {
        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $Provin = null;

        if ($search != null) {
            $Provin = Provincia::whereRaw("provincia.nameprovincia ILIKE '%" . $search . "%'")->orderBy('nameprovincia', 'asc');
            return $Provin->paginate(500);
        }
        else{

            $Provin = Provincia::orderBy('nameprovincia', 'asc');
            return $Provin->paginate(500);
        }

    }

    public function getCantonEX(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $Canton  = null;

        if ($search != null) {

            return Canton::join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                ->select('canton.*', 'provincia.idprovincia', 'provincia.nameprovincia')
                ->where('canton.namecanton','ILIKE','%' . $search . '%')
                ->orderBy('provincia.nameprovincia', 'asc')
                ->orderBy ('namecanton','asc')->paginate(8);
        }
        else{
            return Canton::join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                ->select('canton.*', 'provincia.idprovincia', 'provincia.nameprovincia')
                ->orderBy('provincia.nameprovincia', 'asc')
                ->orderBy ('namecanton','asc')->paginate(8);
        }

    }

    public function getCantonEXA(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $Canton  = null;

        if ($search != null) {

            return Canton::join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                ->select('canton.*', 'provincia.idprovincia', 'provincia.nameprovincia')
                ->where('canton.namecanton','ILIKE','%' . $search . '%')
                ->orderBy('provincia.nameprovincia', 'asc')
                ->orderBy ('namecanton','asc')->paginate(500);
        }
        else{
            return Canton::join('provincia', 'canton.idprovincia', '=', 'provincia.idprovincia')
                ->select('canton.*', 'provincia.idprovincia', 'provincia.nameprovincia')
                ->orderBy('provincia.nameprovincia', 'asc')
                ->orderBy ('namecanton','asc')->paginate(500);
        }
    }

    public function getParroquiaEX(Request $request)

    {

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;

        if ($search != null) {
            return Parroquia::join('canton', 'parroquia.idcanton', '=', 'canton.idcanton')
                ->select('parroquia.*', 'canton.idcanton', 'canton.namecanton')
                ->where('parroquia.nameparroquia','ILIKE','%' . $search . '%')
                ->orderBy('canton.namecanton', 'asc')
                ->orderBy ('nameparroquia','asc')->paginate(8);
        }

        else{

            return Parroquia::join('canton', 'parroquia.idcanton', '=', 'canton.idcanton')
                ->select('parroquia.*', 'canton.idcanton', 'canton.namecanton')
                ->orderBy('canton.namecanton', 'asc')
                ->orderBy ('nameparroquia','asc')->paginate(8);

        }

        //return Emp_Parroquia::orderBy('nameparroquia', 'asc') ->paginate(10);


    }


    public function store(Request $request)
    {
        $TipoDoc01 = SRI_TipoDocumento::where('nametipodocumento', $request->input('nametipodocumento'))->count();

        if ($TipoDoc01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoDocu = new SRI_TipoDocumento();
            $TipoDocu->nametipodocumento = $request->input('nametipodocumento');
            $TipoDocu->codigosri = $request->input('codigosri');
            $TipoDocu->estado = $request->input('estado');

            if ($TipoDocu->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }


        }


    }
    public function storeTipoIdent(Request $request)
    {

        $TipoIdent01 = SRI_TipoIdentificacion::where('nameidentificacion', $request->input('nameidentificacion'))->count();

        if ($TipoIdent01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoIdent = new SRI_TipoIdentificacion();
            $TipoIdent->nameidentificacion = $request->input('nameidentificacion');
            $TipoIdent->codigosri = $request->input('codigosri');
            $TipoIdent->estado = $request->input('estado');

            if ($TipoIdent->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }


    }

    public function storeTipoImpuestoReten(Request $request)
    {

        $TipoImpuestoRetencion01 = SRI_TipoImpuestoRetencion::where('nametipoimpuestoretencion', $request->input('nametipoimpuestoretencion'))->count();

        if ($TipoImpuestoRetencion01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoImpuestoRetencion = new SRI_TipoImpuestoRetencion();
            $TipoImpuestoRetencion->nametipoimpuestoretencion = $request->input('nametipoimpuestoretencion');
            $TipoImpuestoRetencion->codigosri = $request->input('codigosri');
            $TipoImpuestoRetencion->estado = $request->input('estado');

            if ($TipoImpuestoRetencion->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeTipoImpuestoIvaReten(Request $request)
    {
        $TipoImpuestoIvaReten01 = SRI_DetalleImpuestoRetencion::where('namedetalleimpuestoretencion', $request->input('namedetalleimpuestoretencion'))->count();

        if ($TipoImpuestoIvaReten01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoImpuestoIvaReten = new SRI_DetalleImpuestoRetencion();
            $TipoImpuestoIvaReten ->namedetalleimpuestoretencion = $request->input('namedetalleimpuestoretencion');
            $TipoImpuestoIvaReten ->idtipoimpuestoretencion = $request->input('idtipoimpuestoretencion');
            $TipoImpuestoIvaReten ->porcentaje = $request->input('porcentaje');
            $TipoImpuestoIvaReten ->codigosri = $request->input('codigosri');
            $TipoImpuestoIvaReten ->estado = $request->input('estado');
            $TipoImpuestoIvaReten ->idplancuenta = $request->input('idplancuenta');

            if ($TipoImpuestoIvaReten ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }


    public function storeTipoImpuesto(Request $request)
    {

        $TipoImpuest = SRI_TipoImpuesto::where('nameimpuesto', $request->input('nameimpuesto'))->count();

        if ($TipoImpuest > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoImpuesto = new SRI_TipoImpuesto();
            $TipoImpuesto ->nameimpuesto = $request->input('nameimpuesto');
            $TipoImpuesto ->codigosri = $request->input('codigosri');
            $TipoImpuesto ->estado = $request->input('estado');

            if ($TipoImpuesto ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }


    public function storeTipoImpuestoiva(Request $request)
    {

        $TipoImpuestiva = SRI_TipoImpuestoIva::where('nametipoimpuestoiva', $request->input('nametipoimpuestoiva'))->count();

        if ($TipoImpuestiva > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoImpuestoiva = new SRI_TipoImpuestoIva();
            $TipoImpuestoiva -> nametipoimpuestoiva = $request->input('nameimpuestoiva');
            $TipoImpuestoiva ->codigosri = $request->input('codigosri');
            $TipoImpuestoiva -> idtipoimpuesto  = $request->input('TipoImpuesto');
            $TipoImpuestoiva ->porcentaje = $request->input('porcentaje');
            $TipoImpuestoiva ->estado = $request->input('estado');

            if ($TipoImpuestoiva ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }


    public function storeTipoImpuestoice(Request $request)
    {

        $TipoImpuestice = SRI_TipoImpuestoIva::where('nametipoimpuestoiva', $request->input('nametipoimpuestoiva'))->count();

        if ($TipoImpuestice > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipoImpuestoice = new SRI_TipoImpuestoIce();
            $TipoImpuestoice -> nametipoimpuestoice = $request->input('nameimpuestoice');
            $TipoImpuestoice ->codigosri = $request->input('codigosri');
            $TipoImpuestoice -> idtipoimpuesto  = $request->input('TipoImpuesto');
            $TipoImpuestoice ->porcentaje = $request->input('porcentaje');
            $TipoImpuestoice ->estado = $request->input('estado');

            if ($TipoImpuestoice ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeTipoPagoResidente(Request $request)
    {

        $TipopagoR01 = SRI_PagoResidente::where('tipopagoresidente', $request->input('tipopagoresidente'))->count();

        if ($TipopagoR01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $TipopagoR = new SRI_PagoResidente();
            $TipopagoR -> tipopagoresidente = $request->input('tipopagoresidente');
            $TipopagoR -> codigosriats = $request->input('codigosriats');


            if ($TipopagoR  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storepagopais(Request $request)
    {

        $Pagopais01 = SRI_PagoPais::where('pais', $request->input('pais'))->count();

        if ($Pagopais01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $Pagopais = new SRI_PagoPais();
            $Pagopais -> pais = $request->input('pais');
            $Pagopais -> codigosri = $request->input('codigosri');


            if ($Pagopais  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeformapago(Request $request)
    {

        $FormaPago01 = Cont_FormaPago::where('nameformapago', $request->input('nameformapago'))->count();

        if ($FormaPago01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $FormaPago = new Cont_FormaPago();
            $FormaPago -> nameformapago = $request->input('nameformapago');
            $FormaPago -> codigosri = $request->input('codigosri');
            $FormaPago -> estado = $request->input('estado');


            if ($FormaPago  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeprovincia(Request $request)
    {

        $Provin01 = Provincia::where('nameprovincia', $request->input('nameprovincia'))->count();

        if ($Provin01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $Provin = new Provincia();
            $Provin -> nameprovincia = $request->input('nameprovincia');

            //dd($Provin);
            if ($Provin  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }


    public function storecantonEX(Request $request)
    {

        $Canton01 = Canton::where('namecanton', $request->input('namecanton'))->count();

        if ($Canton01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $Canton = new Canton();
            $Canton -> namecanton = $request->input('namecanton');
            $Canton -> idprovincia = $request->input('idprovincia');

            if ($Canton  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeparroquiaEX(Request $request)
    {

        $parroquia01 = Parroquia::where('nameparroquia', $request->input('nameparroquia'))->count();

        if ($parroquia01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $parroquia = new Parroquia();
            $parroquia -> nameparroquia = $request->input('nameparroquia');
            $parroquia -> idcanton = $request->input('idcanton');

            if ($parroquia  ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function storeSustentoTrib(Request $request)
    {

        $TipoSustentoTributario01 = SRI_SustentoTributario::where('namesustento', $request->input('namesustento'))->count();

        if ($TipoSustentoTributario01 > 0) {
            return response()->json(['success' => false]);
        }
        else{

            $SustentoTrib = new SRI_SustentoTributario();
            $SustentoTrib ->namesustento = $request->input('namesustentotributario');
            $SustentoTrib ->codigosrisustento = $request->input('codigosrisustento');
            $SustentoTrib ->estado = $request->input('estado');


            if ($SustentoTrib ->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }


    public function storeComprobanteSustento(Request $request)
    {

        $TipoComprobante01 = SRI_TipoComprobante::where('namecomprobante', $request->input('namecomprobante'))->count();

        if ($TipoComprobante01 > 0) {
            return response()->json(['success' => false]);
        }
        else{


            $TipoComprobante = new SRI_TipoComprobante();
            $TipoComprobante ->namecomprobante = $request->input('namecomprobante');
            $TipoComprobante ->codigosri = $request->input('codigosri');
            $TipoComprobante ->estado = $request->input('estado');

            if ($TipoComprobante ->save()) {


                $idTC = $TipoComprobante->idtipocomprobante;


                $Sustento_Comprobante = new SRI_Sustento_Comprobante();
                $Sustento_Comprobante->idtipocomprobante = intval($idTC);
                $Sustento_Comprobante->idsustentotributario = intval($request->input('idtSustento'));


                if ($Sustento_Comprobante->save()) {
                    return response()->json(['success' => true]);
                }
                else{
                    //dd($Sustento_Comprobante);
                    return response()->json(['success' => false]);
                }


            } else {
                return response()->json(['success' => false]);
            }
        }
    }



    public function getTipoDocByID($id)
    {
        return SRI_TipoDocumento::where('idtipodocumento', $id)->orderBy('nametipodocumento')->get();
    }

    public function getTipoIdentByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_TipoIdentificacion::where('idtipoidentificacion',$id)  -> get();

    }

    public function getTipoImpuestoByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_TipoImpuesto::where('idtipoimpuesto',$id)  -> get();

    }

    public function getTipoImpuestoIvaByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_TipoImpuestoIva::where('idtipoimpuestoiva',$id)  -> get();

    }

    public function getTipoImpuestoIceByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_TipoImpuestoIce::where('idtipoimpuestoice',$id)  -> get();

    }

    public function getTipoImpuestoRetencionRetByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_TipoImpuestoRetencion::where('idtipoimpuestoretencion',$id)  -> get();

    }

    public function getTipoImpuestoRetencionIvaRetByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_DetalleImpuestoRetencion::with('cont_plancuenta')
                                            ->where('iddetalleimpuestoretencion',$id)->get();

    }


    public function getSustentoTributarioByID($id)
    {
        //return SRI_TipoIdentificacion::where('idtipoidentificacion', $id)->orderBy('nameidentificacion')->get();
        return SRI_SustentoTributario::where('idsustentotributario',$id)  -> get();

    }

    public function getComprobanteTributarioByID($id)
    {

        return SRI_TipoComprobante::where('idtipocomprobante',$id)  -> get();

    }


    public function getPagoResidenteByID($id)
    {

        return SRI_PagoResidente::where('idpagoresidente',$id)  -> get();

    }

    public function getPaisPagoByID($id)
    {

        return SRI_PagoPais::where('idpagopais',$id)  -> get();

    }

    public function getFormaPagoByID($id)
    {

        return Cont_FormaPago::where('idformapago',$id)  -> get();

    }

    public function getprovinciaByID($id)
    {

        return Provincia::where('idprovincia',$id)  -> get();

    }

    public function getcantonEXByID($id)
    {

        return Canton::where('idcanton',$id)  -> get();

    }

    public function getparroquiaEXByID($id)
    {

        return Parroquia::where('idparroquia',$id)  -> get();

    }


    public function getSustentoComprobanteByID($id)
    {

        return SRI_Sustento_Comprobante::where('idtipocomprobante',$id)  -> get();

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
        $TipoDocu = SRI_TipoDocumento::find($id);
        $TipoDocu->nametipodocumento = $request->input('nametipodocumento');
        $TipoDocu->codigosri = $request->input('codigosri');
        $TipoDocu->estado = $request->input('estado');

        if ($TipoDocu->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }



    public function updatetpidentsri(Request $request, $id)
    {

        //$id = json_decode($request->get('id'));
        //$data = json_decode($request->get('data'));


        $TipoIdent = SRI_TipoIdentificacion::find($id);
        $TipoIdent->nameidentificacion = $request->input('nameidentificacion');
        $TipoIdent->codigosri = $request->input('codigosri');
        $TipoIdent->estado = $request->input('estado');
        if ($TipoIdent->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatetpimpsri(Request $request, $id)
    {

        $TipoIdent = SRI_TipoImpuesto::find($id);
        $TipoIdent->nameimpuesto = $request->input('nameimpuesto');
        $TipoIdent->codigosri = $request->input('codigosri');
        $TipoIdent->estado = $request->input('estado');
        if ($TipoIdent->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatetpimpIvasri(Request $request, $id)
    {

        $ImpuestoIva = SRI_TipoImpuestoIva::find($id);
        $ImpuestoIva->idtipoimpuesto = $request->input('TipoImpuesto');
        $ImpuestoIva->nametipoimpuestoiva = $request->input('nameimpuestoiva');
        $ImpuestoIva->porcentaje = $request->input('porcentaje');
        $ImpuestoIva->codigosri = $request->input('codigosri');
        $ImpuestoIva->estado = $request->input('estado');
        if ($ImpuestoIva->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatetpimpIcesri(Request $request, $id)
    {

        $ImpuestoIce = SRI_TipoImpuestoIce::find($id);
        $ImpuestoIce->idtipoimpuesto = $request->input('TipoImpuesto');
        $ImpuestoIce->nametipoimpuestoice = $request->input('nameimpuestoice');
        $ImpuestoIce->porcentaje = $request->input('porcentaje');
        $ImpuestoIce->codigosri = $request->input('codigosri');
        $ImpuestoIce->estado = $request->input('estado');
        if ($ImpuestoIce->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatetpimpRetensri(Request $request, $id)
    {

        $TipoImpRt = SRI_TipoImpuestoRetencion::find($id);
        $TipoImpRt->nametipoimpuestoretencion = $request->input('nametipoimpuestoretencion');
        $TipoImpRt->codigosri = $request->input('codigosri');
        $TipoImpRt->estado = $request->input('estado');
        if ($TipoImpRt->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatetpimpIvaRetensri(Request $request, $id)
    {

        $TipoImpIvaRt = SRI_DetalleImpuestoRetencion::find($id);
        $TipoImpIvaRt ->namedetalleimpuestoretencion = $request->input('namedetalleimpuestoretencion');
        $TipoImpIvaRt ->idtipoimpuestoretencion = $request->input('idtipoimpuestoretencion');
        $TipoImpIvaRt ->porcentaje = $request->input('porcentaje');
        $TipoImpIvaRt ->codigosri = $request->input('codigosri');
        $TipoImpIvaRt ->estado = $request->input('estado');
        $TipoImpIvaRt ->idplancuenta = $request->input('idplancuenta');

        if ($TipoImpIvaRt ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateSustentoTributario(Request $request, $id)
    {

        $SustentoTrib = SRI_SustentoTributario::find($id);
        $SustentoTrib ->namesustento = $request->input('namesustentotributario');
        $SustentoTrib ->codigosrisustento = $request->input('codigosrisustento');
        $SustentoTrib ->estado = $request->input('estado');
        if ($SustentoTrib ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function updateSustento_Comprobante(Request $request, $id)
    {
        //console.log($id);
        $TipoComprobante = SRI_TipoComprobante::find($id);
        $TipoComprobante ->namecomprobante = $request->input('namecomprobante');
        $TipoComprobante ->codigosri = $request->input('codigosri');
        $TipoComprobante ->estado = $request->input('estado');

        if ($TipoComprobante ->save()) {


            //$idTC = $TipoComprobante->idtipocomprobante;

            //dd($id);

            $Sustento_Comprobante = SRI_Sustento_Comprobantev1::find($id);
            $Sustento_Comprobante->idtipocomprobante = intval($id);
            $Sustento_Comprobante->idsustentotributario = intval($request->input('idtSustento'));


            if ($Sustento_Comprobante->save()) {
                return response()->json(['success' => true]);
            }
            else{
                //dd($Sustento_Comprobante);
                return response()->json(['success' => false]);
            }


        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatePagoResidente(Request $request, $id)
    {

        $PagoR = SRI_PagoResidente::find($id);
        $PagoR ->tipopagoresidente = $request->input('tipopagoresidente');
        $PagoR -> codigosriats = $request->input('codigosriats');

        if ($PagoR ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updatePagoPais(Request $request, $id)
    {

        $PagoP = SRI_PagoPais::find($id);
        $PagoP ->pais = $request->input('pais');
        $PagoP ->codigosri = $request->input('codigosri');

        if ($PagoP ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateFormaPago(Request $request, $id)
    {

        $FormaPago = Cont_FormaPago::find($id);
        $FormaPago ->nameformapago = $request->input('nameformapago');
        $FormaPago ->codigosri = $request->input('codigosri');
        $FormaPago ->estado = $request->input('estado');

        if ($FormaPago ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateprovincia(Request $request, $id)
    {
        $Provin = provincia::find($id);
        $Provin ->nameprovincia = $request->input('nameprovincia');

        if ($Provin ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function updatecantonEX(Request $request, $id)
    {
        $Canton = Canton::find($id);
        $Canton ->idprovincia = $request->input('idprovincia');
        $Canton ->namecanton = $request->input('namecanton');

        if ($Canton ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function updateparroquiaEX(Request $request, $id)
    {
        $Parq = Parroquia::find($id);
        $Parq ->idcanton = $request->input('idcanton');
        $Parq ->nameparroquia = $request->input('nameparroquia');

        if ($Parq ->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function destroy($id)
    {

        $data=SRI_TipoDocumento::find($id);
        $data->delete();
        return response()->json(['success' => true]);

        //$TipoDocu = SRI_TipoDocumento::where('idtipodocumento',$id)->delete();


    }

    public function deleteTipoIdentSRI(Request $request)
    {

        $data=SRI_TipoIdentificacion::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);

        //$TipoDocu = SRI_TipoDocumento::where('idtipodocumento',$id)->delete();


    }

    public function deleteTipoImpuesto(Request $request)
    {

        $impuestoiva = SRI_TipoImpuestoIva::where('idtipoimpuesto', $request->input('id'))->count();
        $impuestoiice = SRI_TipoImpuestoIce::where('idtipoimpuesto', $request->input('id'))->count();


        if ($impuestoiva == 0 && $impuestoiice == 0 ){
            $data=SRI_TipoImpuesto::find($request->input('id'));
            $data->delete();
            return response()->json(['success' => true]);

        }
        else{
            //dd($impuestoiva);
            //dd($impuestoiice);
            return response()->json(['success' => false]);
        }




        //$TipoDocu = SRI_TipoDocumento::where('idtipodocumento',$id)->delete();


    }


    public function deleteTipoImpuestoIva(Request $request)
    {

        $data=SRI_TipoImpuestoIva::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);

    }

    public function deleteTipoImpuestoIce(Request $request)
    {

        $data=SRI_TipoImpuestoIce::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);

    }

    public function deleteTipoImpuestoRetencion(Request $request)
    {

        $data=SRI_TipoImpuestoRetencion::find($request->input('id'));
        //dd($data);
        $data->delete();
        return response()->json(['success' => true]);

    }

    public function deleteTipoImpuestoIvaRetencion(Request $request)
    {

        $data=SRI_DetalleImpuestoRetencion::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);

    }

    public function deleteSustentoTrib(Request $request)
    {

        $data=SRI_SustentoTributario::find($request->input('id'));
        //dd($data);
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function deleteSustentoComprobante(Request $request)
    {
        $data=SRI_Sustento_Comprobantev1::find($request->input('id'));
        $data->delete();
        $data=SRI_TipoComprobante::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function deleteTipoPagoResidente(Request $request)
    {
        $data=SRI_PagoResidente::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function deletepagopais(Request $request)
    {
        $data=SRI_PagoPais::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function deleteformapago(Request $request)
    {

        $data=Cont_FormaPago::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);
    }

    public function deleteprovincia(Request $request)
    {
        $canton01 = Canton::where('idprovincia', $request->input('id'))->count();
        $data=provincia::find($request->input('id'));
        //dd($request);
        //dd($canton01);
        if($canton01 > 0){
            return response()->json(['success' => false]);
        }
        else{
            $data->delete();
            return response()->json(['success' => true]);
        }



    }

    public function deletecantonEX(Request $request)


    {

        $parroquia01 = Parroquia::where('idcanton', $request->input('id'))->count();
        //dd($parroquia01);
        $data=Canton::find($request->input('id'));
        if($parroquia01 > 0){
            return response()->json(['success' => false]);
        }
        else{
            $data->delete();
            return response()->json(['success' => true]);
        }


    }

    public function deleteParroquiaEX(Request $request)
    {
        $data=Parroquia::find($request->input('id'));
        $data->delete();
        return response()->json(['success' => true]);
    }
}
