<?php

namespace App\Http\Controllers;

use App\KbInformation;
use Illuminate\Http\Request;
use App\tbl_intern_attend;
use DateTime;
use DatePeriod;
use DateInterval;

class KbInformationController extends Controller
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
            
    $interns=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->first();
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
    
     $kbinfos=KbInformation::where("authority","intern")->get();
        
        return view ('kbinfo',compact('kbinfos','attendance','interns'));
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
     * @param  \App\KbInformation  $kbInformation
     * @return \Illuminate\Http\Response
     */
    public function show(KbInformation $kbInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KbInformation  $kbInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(KbInformation $kbInformation,$id)
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
            
        $kbviews= KbInformation::where('id',$id)->get();
            
         return view('kbview',compact('kbviews','attendance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KbInformation  $kbInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KbInformation $kbInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KbInformation  $kbInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(KbInformation $kbInformation)
    {
        //
    }
}
