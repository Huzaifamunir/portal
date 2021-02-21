<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class Login2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm2()
    {
        
        session()->forget('user');

        return view('auth.login2');
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

    //     public function login(Request $request)
    // {
    //     $email=$request->input('email');
    //     $password=$request->input('password');
    //     $users=User::where('int_email',$email)->where('int_password',$password)->first();
    //     if($users!=null){
    //         $request->session()->put('user',$users);
    //     return redirect()->action('HomeController@index');
    //     }
    //     else{
    //         dd('incorrect');
    //     }
        
    // }
    public function login2(Request $request)
    {
        $email=$request->input('email');
        
        $password=$request->input('password');
        $users=User::where('int_email',$email)->first();
        if($users){
            $request->session()->put('user',$users);
        return redirect()->action('DashboadController@index');
        }
        else{
            // dd('incorrect');
            return back()->with('found','Not Found');
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
