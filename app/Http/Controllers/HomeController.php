<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\tbl_intern_attend;
use DateTime;
use DatePeriod;
use DateInterval;

class HomeController extends Controller
{
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
 
        if(session()->get('user')!=null){
        
        
         if(session()->get('user')->int_status==-1){
                $date=date('Y-m-d');
            
                $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
                
                $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
            }else{
            
            $interns=User::where('int_id',session()->get('user')->int_id)->get();
            $date=date('Y-m-d');
            $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date)->first();
      
       $begin = new DateTime(session()->get('user')->int_join_date);
       $end = new DateTime($date);
$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
foreach($daterange as $d){
   
    if($d>$date){
                  $att=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$d->format('Y-m-d'))->first();
                     if($att==null){
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
                 else{
                    break;
                 }
             }
             }
                
        
        return view('home',compact('interns','attendance'));
    }
        else {
            
             return redirect('login');
        }
    }

    public function edit($att_id)
    {
    
     if(session()->get('user')->int_status==-1){
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
        $intern = tbl_intern_attend::where('att_id',$att_id)->get();
      
         return view('work',compact('intern','attendance'));
    }

    public function update(Request $request, $att_intern)


    {  
     date_default_timezone_set('Asia/Karachi');
	$att_holiday=('0');
         $canMark=true;
        $currentDate=date('Y-m-d');
        $checkOutTime=date('H:i:s');
       
     
       
                
        $attendance=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$currentDate)->first();
       
        
           if($attendance->att_out!=null){
            $canMark=false;
            return redirect('home')->with('found','Already check out');
            
           }       

             

       if($canMark){



        


            $att_work=$request->input('att_work');

       
        $attendance->att_out=$checkOutTime;
        $attendance->att_holiday=$att_holiday;
        $attendance->att_work=$att_work;


        $attendance->save();
             return redirect('dashboard')->with('success','You are check out now');    
   }
   else{

   }
   return back();

    }
}
