<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_intern_attend;
use Input;
use DateTime;

use DatePeriod;
use DateInterval;

class AttViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
            
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
            
        $hours=array();
 $hoursworked="0:0:0";
        $s=0;
        $m=0;
        $h=0;
        $i=0;
      $views=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->orderBy('att_id', 'DESC')->paginate(20);
    if($views!=null){
foreach ($views as $view) {
$hoursworked="0 Hours 0 Minutes 0 Sec ";
$s=0;
        $m=0;
        $h=0;
                if($view->att_out!=null){
                $att_out=DateTime::createFromFormat('H:i:s',$view->att_out);
                $att_in=DateTime::createFromFormat('H:i:s',$view->att_in);
              
               $s=intval(round(($att_out->format('U') - $att_in->format('U'))));
               if($s>=60){
                $m=intval($s/60);
                $s=$s-($m*60);
               }
               if($m>=60){
                $h=intval($m/60);
                $m=$m-($h*60);
               }
               $hoursworked=$h." Hours ".$m." Minutes ".$s." Sec ";
            
           }
           $hours[]=$hoursworked;
       }
       
   }
        return view('attview',compact('views','hours','i','attendance','interns'));
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
        //
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
         $view = User::where('int_id',$int_id)->get();
         dd($view);
         
       
        // return view('updateprofile',compact('intern'));
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
