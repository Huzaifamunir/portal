<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Hash;
use App\Suplogin;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        session()->forget('user');

        return view('auth.login');
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
public function login(Request $request)
    {
        $email=$request->input('email');
        $password=$request->input('password');
            $users = User::where('int_email', $request->email)
                  ->where('int_password',md5($request->password))
                  ->first();
        if($users){

            $request->session()->put('user',$users);
        return redirect()->action('DashboadController@index');
        }
        else{
            // dd('incorrect');
               $email=$request->input('email');
            $password=$request->input('password');
            $sups = Suplogin::where('sup_email', $request->email)
            ->where('sup_password',md5($request->password))
            ->first();
           
            
            if($sups){

                $request->session()->put('sup',$sups);
            return redirect()->action('DashboadController@supdashboard');
            }else{
                return back()->with('error', 'Not Found');
            }
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
