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

                $user->token = md5(time().rand());

                if ($user->save()) {

                    $correo = $user->email;
                    $token = $user->token;
                    $username = $user->usuario;

                    Mail::send('usuario.bodyEmail_1',['token' => $token, 'username' => $username] , function($message) use ($correo)
                    {
                        $message->from('notificacionimnegocios@gmail.com', 'Verificación de Correo Electrónico');

                        $message->to($correo)->subject('Verificación de Correo Electrónico');
                    });

                    return response()->json(['success' => true]);

                } else {

                    return response()->json(['success' => false]);

                }


                /*$newPassword = $this->generatePassword(8);

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

                }*/

            } else {

                return response()->json(['success' => false, 'email' => false]);

            }

        } else {

            return response()->json(['success' => false, 'user' => false]);

        }

    }

    public function changePassword($token)
    {

        $user = Usuario::where( 'token', $token )->get();

        if ( count( $user ) > 0 ) {

            $user = Usuario::find($user[0]->idusuario);

            if ($user->email != null && $user->email != '') {

                $newPassword = $this->generatePassword(10);

                $user->password = Hash::make($newPassword);

                if ($user->save()) {

                    $correo = $user->email;
                    $username = $user->usuario;

                    Mail::send('usuario.bodyEmail_2',['newPassword' => $newPassword, 'username' => $username] , function($message) use ($correo)
                    {
                        $message->from('notificacionimnegocios@gmail.com', 'Actualización de Contraseña exitoso');

                        $message->to($correo)->subject('Actualización de Contraseña exitoso');
                    });

                    return redirect('/');

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

        $caracteres = "!'#$%&/()=?¡*][_:;abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

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
