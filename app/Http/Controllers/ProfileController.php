<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\tbl_intern_attend;

use DateTime;
use DatePeriod;
use DateInterval;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
       
            
	if(session()->get('user')!=null){
	
	  if(session()->get('user')->int_status==1)
        {
            $date=date('Y-m-d');
        
            $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
            
            $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
        }
        elseif(session()->get('user')->int_status==-1){
                $date=date('Y-m-d');
            
                $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
                
                $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
            }else{
            
	
	$date=date('Y-m-d');
            $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
            
            $begin = new DateTime(session()->get('user')->int_join_date);
            $end = new DateTime($date);
            $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
            
            foreach($daterange as $d)
            {   
                if($d>$date)
                {
                    $att=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$d->format('Y-m-d'))->first();
                        if($att==null)
                        {
                            $add=tbl_intern_attend::create([
                                // 'att_marked_on'=>$request->input('att_marked_on'),
                                'att_intern'=>session()->get('user')->int_no,
                                'att_date'=>$d->format('Y-m-d'),
                                'att_marked_on'=>$d->format('Y-m-d'),
                                'att_in'=>"00:00:00",
                                'att_out'=>"00:00:00",
                                'att_holiday'=>"1",
                            ]);
                        }
                }
                else
                {
                    break;
                }
            }
            }
	
	 $interns=User::where('int_id',session()->get('user')->int_id)->get();
	
        return view('profile',compact('interns','attendance'));
	 }else {
            
             return redirect('login');
        }
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
    {   $att_holiday=('5');
	date_default_timezone_set('Asia/Karachi');
        $canMark=true;
        $currentDate=date('Y-m-d');

        $currentTime=date('H:i:s');
	
       
        $checkOutTime=date('H:i:s');
           $markerdon=date('Y-m-d H:i:s');
           // dd($markerdon);
      
        $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_id)->get();
      
        
        foreach ($attendance as $at) {
           if($currentDate==date('Y-m-d',strtotime($at->att_date))){
            $canMark=false;
            return back()->with('found','Already check in');
            break;
           }       

             }

       if($canMark){

       $add=tbl_intern_attend::create([

        // 'att_marked_on'=>$request->input('att_marked_on'),
        'att_intern'=>$request->input('att_intern'),
	'att_holiday'=>$att_holiday,
        'int_address'=>$request->input('int_address'),
        'int_lat'=>$request->input('int_lat'),
        'int_lng'=>$request->input('int_lng'),
        'att_date'=>$currentDate,
        'att_in'=>$currentTime,
        'att_marked_on'=>$markerdon,
        


        ]);




        return back()->with('success','Your are check in now');    
   }
   else{

   }
   return back();
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
    public function edit($int_id)
    {
    
      if(session()->get('user')->int_status==1)
        {
            $date=date('Y-m-d');
        
            $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
            
            $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
        }
        elseif(session()->get('user')->int_status==-1){
                $date=date('Y-m-d');
            
                $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
                
                $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
            }else{
            
    $date=date('Y-m-d');
            $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
            
            $begin = new DateTime(session()->get('user')->int_join_date);
            $end = new DateTime($date);
            $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
            
            foreach($daterange as $d)
            {   
                if($d>$date)
                {
                    $att=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$d->format('Y-m-d'))->first();
                        if($att==null)
                        {
                            $add=tbl_intern_attend::create([
                                // 'att_marked_on'=>$request->input('att_marked_on'),
                                'att_intern'=>session()->get('user')->int_no,
                                'att_date'=>$d->format('Y-m-d'),
                                'att_marked_on'=>$d->format('Y-m-d'),
                                'att_in'=>"00:00:00",
                                'att_out'=>"00:00:00",
                                'att_holiday'=>"1",
                            ]);
                        }
                }
                else
                {
                    break;
                }
            }
            
            }
            
        $intern = User::where('int_id',$int_id)->get();
         
       
        return view('updateprofile',compact('intern','attendance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$int_id)
    {  
    
    
            
        $intern=User::where('int_id',$int_id)->first();

        
          if($request->hasFile('int_photo')){

            

            //Get FileName with extension
            $fileNameWithExt = $request->file('int_photo')->getClientOriginalName();
           
            //get Only FileName
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
           
            //Get only Extension
            $extension = $request->file('int_photo')->getClientOriginalExtension();
            //FileName To Store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            
            //upload Image
            $path = $request->file('int_photo')->move(('../dist/img/intern'), $fileNameToStore);
           
            // $fileNameToStore=$request->input('int_photo');
            // dd($fileNameToStore);
            $intern->int_photo=$fileNameToStore;
           
            
        }

       
        if(!is_null($request->input('int_password'))){
            
            $password=$request->input('int_password');
            
        $pass=md5($password);
        $intern->int_password=$pass;
        }


        $intern->save();
        return redirect('dashboard');
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
