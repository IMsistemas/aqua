<?php

namespace App\Http\Controllers\Index;

use App\Modelos\Usuario\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('users')) {
            return view('index_new');
        } else {
            return view('login');
        }
    }

    public function logout()
    {
        Session::flush();

        return response()->json(['success' => true]);

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
        /*$count = Usuario::where('usuario', $request->input('user'))
                            ->where('password', Hash::make($request->input('pass')))->count();*/

        /*$count = Usuario::where('usuario', $request->input('user'))
                            //->where('password', $request->input('pass'))->count();
                            ->where('password', Hash::make( $request->input('pass')))->count();

        if ($count == 0) {
            return response()->json(['success' => false]);
        } else {
            $usuario = Usuario::where('usuario', $request->input('user'))
                                ->where('password', Hash::make( $request->input('pass')))->get();

            Session::put('users', $usuario);

            return response()->json(['success' => true]);
        }*/

        $user = Usuario::where( 'usuario', $request->input('user' ) )->get();

        if ( count( $user ) > 0 ) {

            if( Hash::check( $request->input('pass'), $user[0]->password  ) ) {

                Session::put('users', $user);

                return response()->json(['success' => true]);

            } else {

                return response()->json(['success' => false]);

            }

        } else {

            return response()->json(['success' => false]);

        }
    }

    public function resetPassword(Request $request)
    {

        $user = Usuario::where( 'usuario', $request->input('user' ) )->get();

        if ( count( $user ) > 0 ) {

            $user = Usuario::find($user[0]->idusuario);

            if ($user->email != null && $user->email != '') {

                $newPassword = $this->generatePassword(8);

                $user->password = Hash::make($newPassword);

                if ($user->save()) {

                    $correo = $user->email;

                    Mail::send('usuario.bodyResetPass',['emailnew' => $newPassword] , function($message) use ($correo)
                    {
                        $message->from('notificacionimnegocios@gmail.com', 'AQUA');

                        $message->to($correo)->subject('Solicitud de Cambio de Password');
                    });

                    return response()->json(['success' => true]);

                } else {

                    return response()->json(['success' => false]);

                }

            } else {

                return response()->json(['success' => false, 'email' => false]);

            }

        } else {

            return response()->json(['success' => false, 'user' => false]);

        }

    }

    private function generatePassword($longitud)
    {

        $codigo = '';

        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $max = strlen($caracteres) - 1;

        for($i = 0; $i < $longitud; $i++) {

            $codigo .= $caracteres[rand(0, $max)];

        }

        return $codigo;
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
        //
    }
}
