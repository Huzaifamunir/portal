<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TblInternProjects;
use App\tbl_intern_attend;
use App\TblInternProjectsTask;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use App\User;
use App\Intnews;
use App\Core\HelperFunction;
use App\Leave;
use App\IntAmount;
use Carbon\Carbon;
use App\TblAmountSal;
use App\Query;


class DashboadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // $cdate=date('y-m-d')


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


        $projects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->get();
        $cprojects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','1')->get();
	$projectsprog=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','0')->get();
	
        $countatt=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_holiday','1')->get();
        $countattin=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_holiday','0')->get();
        $countnoattout=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_holiday','3')->get();

 $project = TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->first();
          $tasks=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();


           $project1 = TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->first();
          $taskscom=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('task_status','1')->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();

          $project2 = TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->first();
          $tasksprog=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('task_status','0')->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();

                    $news=Intnews::get();
         
          $var=array();

$count=0;
            foreach( $news as $n){

                $id = $n->news_id;
                
            }
            $abc=$id;
            
            $intnews=Intnews::where('news_id',$abc)->get();
            
            
            
      if(session()->get('user')->int_duration=='3 Months')
            {
                $attdate=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_holiday','0')->count();
                
                $io=$attdate>66;
                if(session()->get('user')->int_status==0){
                if($io){
                    $internre=User::where('int_id',session()->get('user')->int_id)->first();
                    $status=1;

                    $internre->int_status=$status;
                    $internre->save();
                    $message1="Dear ".session()->get('user')->int_name.",\n\nYour internship has been completed.For more detail visit our office.\n\nRegards,\nEziline Software House";
       
            $sms=HelperFunction::send_sms(session()->get('user')->int_cell,$message1);
                    
                    $phone='03135128953';
                    

                    $message3="Dear Khuzaifa Munir,\n\n".session()->get('user')->int_name. "'s internship has been completed.\n\nRegards,\nEziline Software House";
                    
       
                    $sms=HelperFunction::send_sms($phone,$message3);
                    
                }
            }

                
                
            }elseif(session()->get('user')->int_duration=='2 Months')
            {
                $attdate1=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_holiday','0')->count();
                
                
            $io1=$attdate1>44;
            
            if(session()->get('user')->int_status==0){
            if($io1){
                $internre1=User::where('int_id',session()->get('user')->int_id)->first();
                $status=1;

                $internre1->int_status=$status;
                $internre1->save();
                
                $message1="Dear ".session()->get('user')->int_name.",\n\nYour internship has been completed.For more detail visit our office.\n\nRegards,\nEziline Software House";
       
            $sms=HelperFunction::send_sms(session()->get('user')->int_cell,$message1);
            
            $phone='03135128953';
                    

                    $message3="Dear Khuzaifa Munir,\n\n".session()->get('user')->int_name. "'s internship has been completed.\n\nRegards,\nEziline Software House";
                    
       
                    $sms=HelperFunction::send_sms($phone,$message3);
            }
        }
            
        }
        $int=User::count();
         $data=User::where('int_job_status','1')->get();

           
       
        return view('dashboard1',compact('projects','cprojects','countatt','countattin','countnoattout','int','tasks','taskscom','tasksprog','projectsprog','attendance','interns','intnews','data'));

	 }else {
            
             return redirect('login');
        }
    }
    
    
        public function leave()
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
                                'att_holiday'=>"0",
                            ]);
                        }
                }
                else
                {
                    break;
                }
            }
        }
             $leaveshow=Leave::where('leave_int_id',session()->get('user')->int_id)->orderBy('leave_id', 'DESC')->paginate(10);
        
            return view('leave',compact('attendance','leaveshow'));

        }else {
            
            return redirect('login');
       }
    }

    public function leaveadd(Request $request)
    {   
        $date1=date('Y-m-d');
        
        $att=tbl_intern_attend::where('att_intern',session()->get('user')->int_no)->where('att_date',$date1)->first();
        if($att){
            return redirect()->back()->with('error', 'Already applied ');
        }else{
            
            
        $date=date('Y-m-d');
        if($request->input('int_leave_fdate')<$date || $request->input('int_leave_tdate')<$request->input('int_leave_fdate')){
            return redirect()->back()->with('error', 'Invalid date '); 
        }else{
        $leave=Leave::create([
            // 'att_marked_on'=>$request->input('att_marked_on'),
            'leave_int_id'=>session()->get('user')->int_id,
            'int_leave_fdate'=>$request->input('int_leave_fdate'),
            'int_leave_tdate'=>$request->input('int_leave_tdate'),
            'leave_reason'=>$request->input('leave_reason'),
            'leave_status'=>"0",
            
        ]);


        return back();
        }
    
    }
    }
    
    
        public function getvalue()
    {   
        
        $sub = array();
        $days=array();
       
        $barcprojects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','1')->get();
        $cprojects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','1')->get();
        foreach($cprojects as $cp)
        {
            $fdate = $cp->int_proj_startedon;
            $tdate = $cp->int_proj_endedon;
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval = $datetime1->diff($datetime2);
            $day = $interval->format('%a');
           
            array_push($days,(int)$day);

            // $value1=$days

        }
       
        
      
        
        foreach($barcprojects as $bp)
         {  
            

            $value = $bp->int_proj_title;

            array_push($sub,$value);
            
         }
        //  return response()->json($sub);
        //  $koib=implode(",",$sub);
         return response()->json([
             'sub'=>$sub,
             'days'=>$days
         ],200);

         
    }
    
      public function supdashboard()
    {
    	$interns=User::all();
        $interntest=User::where('int_status','-1')->get();
        $internc=User::where('int_status','1')->get();
        $internic=User::where('int_status','0')->get();
        $totalamount=IntAmount::get()->sum('int_amount');
        $cmamount=IntAmount::whereYear('am_date',Carbon::now()->year)
        ->whereMonth('am_date',Carbon::now()->month)->get()->sum('int_amount');
         $lastmonth = IntAmount::whereMonth(
            'am_date', '=', Carbon::now()->subMonth()->month
        )->get()->sum('int_amount');
        $per=40;
       $totalper=100;

       $peramount=$per/$totalper*$cmamount;
        $pervioamount=$per/$totalper*$lastmonth;
       
           $tprojects=TblInternProjects::all();
       $inproproject=TblInternProjects::where('int_proj_status','0')->get();
       $cproject=TblInternProjects::where('int_proj_status','1')->get();
       
       
            $date1 = \Carbon\Carbon::now();
    //   $crntmonth=$date1->format('F-y'); // July
      
      $previmonth=$date1->subMonth()->format('F-y'); // June
      
       $nextMonthNumber = date('M', strtotime('first day of +1 month'));
       $nextMonthDate = new DateTime();
       $nextMonthDate->add(new DateInterval('P1M'));
       while ($nextMonthDate->format('M') != $nextMonthNumber) {
           $nextMonthDate->sub(new DateInterval('P1D'));
       }
      $check=TblAmountSal::where('month',$previmonth)->first();
       
       if($check){

        

       }elseif($nextMonthDate)
       {
        $addsal=TblAmountSal::create([
                
            'month'=>$previmonth,
            'total_amount'=>$lastmonth,
            'sal'=>$pervioamount,
            
            
            

        ]);
       }
      
       
        

        return view ('sup.dashboard',compact('interns','interntest','internc','internic','totalamount','cmamount','lastmonth','peramount','tprojects','inproproject','inproproject','cproject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
         public function count()
    {
        $unpaidinternees=array();
        $paidinternees=array();

        for($m=1;$m<=(int)date('m');$m++)
        {
            
            $count=User::where('int_paid_status','0')->whereRaw('Month(int_join_date)=?',(string)$m)->whereYear('int_join_date',Carbon::now()->year)->count();
            array_push($unpaidinternees,$count);

            $count=User::where('int_paid_status','1')->whereRaw('Month(int_join_date)=?',(string)$m)->whereYear('int_join_date',Carbon::now()->year)->count();
            array_push($paidinternees,$count);
            
        }
 
        $monthsname=array();
        for($m=1;$m<=(int)date('m');$m++)
        {
            
           
            array_push($monthsname,date('F',mktime(0,0,0,$m,1)));
            
        }
        return response()->json([
            'unpaidinternees'=>$unpaidinternees,
            'paidinternees'=>$paidinternees,
            'monthsname'=>$monthsname
        ],200);
    }
    
    
       public function filtercount(Request $request)
    {   
        // $year=Carbon::now()->subYear()->year;
        
        $unpaidinternees=array();
        $paidinternees=array();

        for($m=1;$m<=12;$m++)
        {
            
            $count=User::where('int_paid_status','0')->whereRaw('Month(int_join_date)=?',(string)$m)->whereYear('int_join_date',Carbon::now()->subYear()->year)->count();
            
            array_push($unpaidinternees,$count);

            
            $count=User::where('int_paid_status','1')->whereRaw('Month(int_join_date)=?',(string)$m)->whereYear('int_join_date',Carbon::now()->subYear()->year)->count();
            
            array_push($paidinternees,$count);
            
        }
 
        $monthsname=array();
        for($m=1;$m<=12;$m++)
        {
            
           
            array_push($monthsname,date('F',mktime(0,0,0,$m,1)));
            
        }
        return response()->json([
            'unpaidinternees'=>$unpaidinternees,
            'paidinternees'=>$paidinternees,
            'monthsname'=>$monthsname
        ],200);
       
    }
    
      public function projectcount()
    {
        $incompletepro=array();
        $completepro=array();

        for($m=1;$m<=(int)date('m');$m++)
        {
            
           $count=TblInternProjects::where('int_proj_status','0')->whereRaw('Month(int_proj_startedon)=?',(string)$m)->whereYear('int_proj_startedon',Carbon::now()->year)->count();
            array_push($incompletepro,$count);

            $count=TblInternProjects::where('int_proj_status','1')->whereRaw('Month(int_proj_startedon)=?',(string)$m)->whereYear('int_proj_startedon',Carbon::now()->year)->count();
            array_push($completepro,$count);
            
        }
 
        $monthsname=array();
        for($m=1;$m<=(int)date('m');$m++)
        {
            
           
            array_push($monthsname,date('F',mktime(0,0,0,$m,1)));
            
        }
        return response()->json([
            'incompletepro'=>$incompletepro,
            'completepro'=>$completepro,
            'monthsname'=>$monthsname
        ],200);
    }
    
      public function projfilter()
    {   
        
        $incompletepro=array();
        $completepro=array();

        for($m=1;$m<=12;$m++)
        {
            
           $count=TblInternProjects::where('int_proj_status','0')->whereRaw('Month(int_proj_startedon)=?',(string)$m)->whereYear('int_proj_startedon',Carbon::now()->subYear()->year)->count();
            array_push($incompletepro,$count);

            $count=TblInternProjects::where('int_proj_status','1')->whereRaw('Month(int_proj_startedon)=?',(string)$m)->whereYear('int_proj_startedon',Carbon::now()->subYear()->year)->count();
            array_push($completepro,$count);
            
        }
 
        $monthsname=array();
        for($m=1;$m<=12;$m++)
        {
            
           
            array_push($monthsname,date('F',mktime(0,0,0,$m,1)));
            
        }
        return response()->json([
            'incompletepro'=>$incompletepro,
            'completepro'=>$completepro,
            'monthsname'=>$monthsname
        ],200);
    }
    public function query()
    {
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
                                'att_holiday'=>"0",
                            ]);
                        }
                }
                else
                {
                    break;
                }
            }
            
            $query=Query::paginate(3);
            
        return view('query',compact('attendance','query'));
    }
    public function queryadd(Request $request)
    {
        $title=$request->input('title');
        $dis=$request->input('dis');
        $tags=$request->input('tags');
        $add=Query::create([
            
            'user_id'=>session()->get('user')->int_id,
            'query_title'=>$title,
            'query_dis'=>$dis,
            'query_tags'=>$tags,
            'date'=>date('Y-d-m'),
            
        ]);

        return back();
    }
    public function querycmnt(Request $request,$id)
    {
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
                                'att_holiday'=>"0",
                            ]);
                        }
                }
                else
                {
                    break;
                }
            }
       
        

      
       
            $query=Query::where('id',$id)->get();

        return view('querycmnt',compact('attendance','query'));
    }

    public function querysearch(Request $request)
    {
       
        $search2=$request->get('search');
        $query=DB::table('tbl_int_query')->where('query_title','like','%'.$search2.'%')->get();
        dd($query);
    }
    
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
