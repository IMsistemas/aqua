<?php

namespace App\Http\Controllers\Index;

use App\Modelos\Usuario\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
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

        $count = Usuario::where('usuario', $request->input('user'))
                            ->where('password', $request->input('pass'))->count();

        if ($count == 0) {
            return response()->json(['success' => false]);
        } else {
            $usuario = Usuario::where('usuario', $request->input('user'))
                                ->where('password', $request->input('pass'))->get();

            Session::put('users', $usuario);

            return response()->json(['success' => true]);
        }
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
