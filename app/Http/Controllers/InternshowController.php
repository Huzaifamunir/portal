<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\TblInternProjects;
use DB;
use App\tbl_intern_attend;
use App\Intnews;
use Illuminate\Support\Str;
use  App\Core\HelperFunction;
use App\TblIprojectStaff;
use App\Leave;
use DateTime;
use DatePeriod;
use DateInterval;
use App\TblProjectsData;
use App\TblInternProjectsTask;
use App\InternAccount;
use App\Bank;
use App\IntAmount;
use App\TblAmountSal;
use Carbon\Carbon;
use App\Notification;

class InternshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index(Request $request)
    {    
    
    	$search=$request->get('search');
    	$search2=$request->get('search2');
        // $int=DB::table('tbl_intern')->where('int_name','like','%'.$search.'%')->get();
             $internshow =DB::table('tbl_intern')->where('int_name','like','%'.$search.'%')->orderBy('int_id', 'DESC')->paginate(13);
        // $internshow=User::all()->sortByDesc("int_id");
        $interntest =DB::table('tbl_intern')->where('int_name','like','%'.$search2.'%')->orderBy('int_id', 'DESC')->where('int_status','-1')->get();

        $inporg =DB::table('tbl_intern')->where('int_name','like','%'.$search2.'%')->orderBy('int_id', 'DESC')->where('int_status','0')->get();

        $homebase =DB::table('tbl_intern')->where('int_name','like','%'.$search2.'%')->orderBy('int_id', 'DESC')->where('int_status','5')->get();

        
        
       
        return view('sup.intern',compact('internshow','interntest','inporg','homebase'));
    }
    
      public function account()
    {
        $totalamount=IntAmount::get()->sum('int_amount');
        $cmamount=IntAmount::whereYear('am_date',Carbon::now()->year)
        ->whereMonth('am_date',Carbon::now()->month)->get()->sum('int_amount');
         $lastmonth = IntAmount::whereMonth(
            'am_date', '=', Carbon::now()->subMonth()->month
        )->get()->sum('int_amount');
        $per=40;
       $totalper=100;

       $peramount=$per/$totalper*$cmamount;
    $sal=TblAmountSal::paginate(13);
    $totalsal=TblAmountSal::get()->sum('sal');
       
        return view('sup.account',compact('totalamount','lastmonth','cmamount','peramount','sal','totalsal'));
    }
    
     public function proupdate(Request $request)
    {
        // dd($request->id);
        $links= array();
        $proup=DB::table('tbl_intern')-> join('tbl_intern_projects', 'tbl_intern.int_id', '=', 'tbl_intern_projects.int_proj_internid')->where('int_proj_id',$request->id)->get();
        
        foreach($proup as $data)
        {
            $data->int_proj_title;
            $data->int_proj_tech;
            $data->int_proj_startedon;
            $data->int_proj_duration;
            $data->int_proj_description;
            $data->int_name;
            $data->int_id;
            
        array_push($links,$data->int_proj_title,$data->int_proj_tech,$data->int_proj_startedon,$data->int_proj_duration,$data->int_proj_description,$data->int_name,$data->int_id);
        }
       
        $user=User::all();
        return response()->json([
            "links"=> $links,
            "user"=> $user
         ],200);
    
    }


 public function interntest()
    {
        return view('interntest');
    }
    
     public function graphic()
    {
        return view('graphicstest');
    }
    
     public function wordpress()
    {
        return view('wordpresstest');
    }
    
    
       public function downloadpro(Request $request)
    {
        {   
        
            $search=$request->get('search1');
           
        //    $allpro=TblInternProjects::where('int_proj_title','like','%'.$search.'%')->where('int_proj_status','1')->orderBy('int_proj_id', 'DESC')->paginate(13);
   
           

           $tasks = array();
           $keys =array('data_id','int_name','int_proj_title','task_id','task_no','task_title','task_start_date','task_project_id','task_description','task_status','task_end_date','task_duration','task_delivered_on','int_proj_dropbox');
                $data=DB::table('tbl_intern')-> join('tbl_intern_projects', 'tbl_intern.int_id', '=', 'tbl_intern_projects.int_proj_internid')->where('int_proj_title','like','%'.$search.'%')->where('int_proj_status','1')
          -> join('tbl_intern_projects_tasks', 'tbl_intern_projects.int_proj_id', '=', 'tbl_intern_projects_tasks.task_project_id')->orderBy('int_proj_id', 'DESC')->paginate(13);
         
          
          foreach($data as $task){
           $dataid = TblProjectsData::where('data_related_to',$task->task_id)->pluck('data_id')->first();
           $datacount = TblProjectsData::where('data_related_to',$task->task_id)->pluck('data_id')->count();
          
           
        
        
  
              
               array_push($tasks, array_combine($keys, [$dataid,$task->int_name,$task->int_proj_title, $task->task_id, $task->task_no, $task->task_title, $task->task_start_date, $task->task_project_id, $task->task_description, $task->task_status, $task->task_end_date, $task->task_duration, $task->task_delivered_on,$task->int_proj_dropbox]));
               
              
          }
         
           
           
           
           return view('sup.downloadpro',compact('tasks','data'));
       }
    }

    public function propics(Request $request)
       {
       $links= array();
       $data=TblProjectsData::where('data_related_to',$request->id)->get();
       $datacount=TblProjectsData::where('data_related_to',$request->id)->count();
       
       foreach($data as $image)
       {
       	$image->data_located;
       array_push($links,$image->data_located);
       }
       
       return response()->json([
       "links"=> $links,
       "datacount"=>$datacount
       ],200);
       
       }
      public function project(Request $request)
    
    {   
        
         $search=$request->get('search1');
        
        $allpro=TblInternProjects::where('int_proj_title','like','%'.$search.'%')->orderBy('int_proj_id', 'DESC')->paginate(13);
        

        $intname= User::all();
        
        
        
        
        return view('sup.internproject',compact('allpro','intname'));
    }
    
    
    
  
     public function interntasks()
    {
        
        /**
        $tasks=DB::table('tbl_intern')-> join('tbl_intern_projects', 'tbl_intern.int_id', '=', 'tbl_intern_projects.int_proj_internid')
        -> join('tbl_intern_projects_tasks', 'tbl_intern_projects.int_proj_id', '=', 'tbl_intern_projects_tasks.task_project_id')->where('task_status','-1')
        -> join('tbl_projects_data',  'tbl_intern_projects_tasks.task_id', '=', 'tbl_projects_data.data_related_to')->orderBy('data_id', 'DESC')->groupBy('data_related_to')->paginate(13);
         */
         
        
         $tasks = array();
         $keys =array('data_id','int_name','int_proj_title','task_id','task_no','task_title','task_start_date','task_project_id','task_description','task_status','task_end_date','task_duration','task_delivered_on');
             $data=DB::table('tbl_intern')-> join('tbl_intern_projects', 'tbl_intern.int_id', '=', 'tbl_intern_projects.int_proj_internid')
        -> join('tbl_intern_projects_tasks', 'tbl_intern_projects.int_proj_id', '=', 'tbl_intern_projects_tasks.task_project_id')->where('task_status','-1')->orderBy('task_id', 'DESC')->paginate(13);
       
       
        foreach($data as $task){
         $dataid = TblProjectsData::where('data_related_to',$task->task_id)->pluck('data_id')->first();
          $datacount = TblProjectsData::where('data_related_to',$task->task_id)->pluck('data_id')->count();
         
         
      
      

	        
     		array_push($tasks, array_combine($keys, [$dataid,$task->int_name,$task->int_proj_title, $task->task_id, $task->task_no, $task->task_title, $task->task_start_date, $task->task_project_id, $task->task_description, $task->task_status, $task->task_end_date, $task->task_duration, $task->task_delivered_on]));
     		
	        
        }
  
        return view('sup.interntasks',compact('tasks','data','datacount'));
    }

    public function notification()
    { 
        // $noti=Notification::where('noti_status','0')->get();
        $message=array();
        $keys=array('name','image','discuss','date','id');
        $noti=DB::table('tbl_intern')-> join('tbl_sup_notification', 'tbl_intern.int_id', '=', 'tbl_sup_notification.user_id')->where('noti_status','0')->get();
        foreach($noti as $msg)
       {
           $name=$msg->int_name;
           $image=$msg->int_photo;
           $discuss=$msg->message;
           $id=$msg->id;
           $date=$msg->date;
           array_push($message,array_combine($keys,[$name,$image,$discuss,$date,$id]));
           
       }
       return response()->json([
        "message"=> $message
        ],200);
        
        
    }

    public function msgshow($id)
    {   
        $status=1;
        
        $notistatusupdate=Notification::where('id',$id)->first();
        $notistatusupdate->noti_status=$status;
        
      
        $notistatusupdate->save();
        $user_id=$notistatusupdate->user_id;
        

        // $shows=DB::table('tbl_intern')-> join('tbl_sup_notification', 'tbl_intern.int_id', '=', 'tbl_sup_notification.user_id')->where('user_id',$notistatusupdate->user_id)->get();
        // dd($shows);
        return view('sup.discussionreply',compact('user_id'));
    }

    public function msgreply($id)
    { 
        $messagereply=array();
        $keys=array('name','image','discuss','date','file');
        $shows=DB::table('tbl_intern')-> join('tbl_sup_notification', 'tbl_intern.int_id', '=', 'tbl_sup_notification.user_id')->where('user_id',$id)->get();
        foreach($shows as $show)
       {
           $name=$show->int_name;
           $image=$show->int_photo;
           $discuss=$show->message;
           $file=$show->db_file;
           $date=$show->date;
           array_push($messagereply,array_combine($keys,[$name,$image,$discuss,$date,$file]));
           
       }
       return response()->json([
        "messagereply"=> $messagereply
        ],200);
    }
    
    
       public function viewpics(Request $request)
       {
       $links= array();
       $data=TblProjectsData::where('data_related_to',$request->id)->get();
       
       foreach($data as $image)
       {
       	$image->data_located;
       array_push($links,$image->data_located);
       }
       
       return response()->json([
       "links"=> $links
       ],200);
       
       }
       
         public function projectupdate(Request $request, TblInternProjects $tblInternProjects,$int_proj_id)
    {
        $proj=TblInternProjects::where('int_proj_id',$int_proj_id)->first();
        $int_proj_title=$request->input("int_proj_title");
       
        $int_proj_tech=$request->input("int_proj_tech");
        $int_proj_startedon=$request->input("int_proj_startedon");
        $int_proj_duration=$request->input("int_proj_duration");

        $int_proj_description=$request->input("int_proj_description");
        

                    $proj->int_proj_title=$int_proj_title;
                    $proj->int_proj_tech =$int_proj_tech;
                    $proj->int_proj_startedon=$int_proj_startedon;
                    $proj->int_proj_duration=$int_proj_duration;
                    $proj->int_proj_description =$int_proj_description;
                    $proj->save();

                    return back();

    }

       
       
          public function rejtasks(Request $request,$task_id)
       {
        $status=0;

        $taskrej=TblInternProjectsTask::where('task_id',$task_id)->first();
        
        $taskrej->task_status=$status;
        
        $taskrej->save();
        
        
        
        return back();
       }

       public function taskapr(Request $request,$task_id)
       {

        
        $status=1;

        $taskapr=TblInternProjectsTask::where('task_id',$task_id)->first();
        
        $taskapr->task_status=$status;
        
        $taskapr->save();
        
        
        
        return back();
       }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     public function monsal()
    {	
    $sal=TblAmountSal::all();
        return view('sup.monsal',compact('sal'));
    }
     
     public function leaveapp()
    {
        $leaveapp=Leave::all()->sortByDesc('leave_id');
       
        return view('sup.leaveapp', compact('leaveapp'));
    }


    
   public function leaveapprove(Request $request, $leave_id)
    {
        $status=-1;

        $leaveupdate1=Leave::where('leave_id',$leave_id)->first();
        
        $leaveupdate1->leave_status=$status;
        
        $leaveupdate1->save();
        
        
        
        return back();
        
    }

     public function leaverej(Request $request, $leave_id)
    {
        $status=1;
        
        $leaveupdate1=Leave::where('leave_id',$leave_id)->first();
        
        
        $leaveupdate1->leave_status=$status;
        
        $leaveupdate1->save();

        $date=date('Y-m-d');
        $leaveupdate1=Leave::where('leave_id',$leave_id)->first();
        $fdate=$leaveupdate1->int_leave_fdate;
        $tdate=$leaveupdate1->int_leave_tdate;
        
        $user=User::where('int_id',$leaveupdate1->leave_int_id)->first();
        $int_no=$user->int_no;
        
        
        $begin = new DateTime($fdate);
        $end = new DateTime($tdate);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        

        foreach($daterange as $d)
        {   
            if($d>$date)
            {
                $att=tbl_intern_attend::where('att_intern',$int_no)->where('att_date',$d->format('Y-m-d'))->first();
             
                    if($att==null)
                    {   
                           
                            
                        $add=tbl_intern_attend::create([
                            // 'att_marked_on'=>$request->input('att_marked_on'),
                            'att_intern'=>$int_no,
                            'att_date'=>$d->format('Y-m-d'),
                            'att_marked_on'=>$d->format('Y-m-d'),
                            'att_in'=>"00:00:00",
                            'att_out'=>"00:00:00",
                            'att_holiday'=>"3",
                        ]);
                       
                    }
                    
            }
            else
            {
                break;
            }
        }
        
        
        return back();
    }
    
    public function create()
    {
        //
    }
    
      public function intstatuscomp(Request $request, $int_id)
    {
       
        $status=1;
        $int_review=$request->input('int_review');
        
        $intstatuscomp=User::where('int_id',$int_id)->first();
        
        $intstatuscomp->int_status=$status;
        $intstatuscomp->int_review=$int_review;
        
        $intstatuscomp->save();
        
        return back();
       
    }

    public function intstatusincomp(Request $request, $int_id)
    {
       
        $status=3;
        
        
        $intstatuscomp=User::where('int_id',$int_id)->first();
        
        $intstatuscomp->int_status=$status;
        $intstatuscomp->int_review='Incomplete';
        
        $intstatuscomp->save();
        
        return back();
    }
    
        public function attreport(Request $request, $int_id)
    {
        $date=date('Y-m-d');
        $from=$request->input('fdate');
        
        $to=$request->input('tdate');
        
        $attrep=User::where('int_id',$int_id)->first();
     
        $hours=array();
        $hoursworked="0:0:0";
               $s=0;
               $m=0;
               $h=0;
               $i=0;
        $views=tbl_intern_attend::where('att_intern',$attrep->int_no)->whereBetween('att_date', [$from, $to])->get();
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
        $att1=tbl_intern_attend::where('att_intern',$attrep->int_no)->whereBetween('att_date', [$from, $to])->where('att_holiday','1')->count();
        $att2=tbl_intern_attend::where('att_intern',$attrep->int_no)->whereBetween('att_date', [$from, $to])->where('att_holiday','0')->count();
        $att3=tbl_intern_attend::where('att_intern',$attrep->int_no)->whereBetween('att_date', [$from, $to])->where('att_holiday','5')->count();
         $att4=tbl_intern_attend::where('att_intern',$attrep->int_no)->whereBetween('att_date', [$from, $to])->where('att_holiday','3')->count();
        
        return view('sup.attreport',compact('views','hours','i','att1','att2','att3','att4'));
    
    }
    
        public function sms(Request $request, $int_id)
    {
        $int_cell=$request->input('int_cell');
       
        $int_message=$request->input('int_message');
        $int_name=$request->input('int_name');

        $message="Dear ".$int_name.",\n\n".$int_message." .\n\nRegards,\nEziline Software House";
       
        $sms=HelperFunction::send_sms($int_cell,$message);
        return back();
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 public function store(Request $request)
    {   



        
        if($request->input('int_message')){
        $add=Intnews::create([

       
            'int_message'=>$request->input('int_message'),
            
          
            
            
    
    
            ]);
            return back();
        }else{


            
      
            $pass=Str::random(6);
            
            $password=md5($pass);
          
           
        
            $status=$request->input('int_status');
            $date=date('Y');
            $def=substr($date,2);
            $date1=date('m');
            $date2=date('d');
          $date3='Ezi-intern-'.$date2.'/'.$date1.'/'.$def;
        
          
            $abc=$request->input('int_no');
            $cd=$abc+1;
            $int_no=$date3.$cd;
            $int_name=$request->input('int_name');
            $int_email=$request->input('int_email');
            $int_cell=$request->input('int_cell');
            $int_join_date=$request->input('int_join_date');
            $int_cnic=$request->input('int_cnic');
            $int_dob=$request->input('int_dob');
            $int_instituation=$request->input('int_instituation');
            $int_degree=$request->input('int_degree');
            $int_technology=$request->input('int_technology');
            $int_duration=$request->input('int_duration');
            $int_documents=$request->input('int_documents');
            $file_src='no_image.png';
         $int_paid_status=0;
      
            



         

           
            $addintern=User::create([
                
                'int_no'=>$int_no,
                'int_name'=>$int_name,
                'int_email'=>$int_email,
                'int_cell'=>$int_cell,
                'int_join_date'=>$int_join_date,
                'int_cnic'=>$int_cnic,
                'int_dob'=>$int_dob,
                'int_password'=>$password,
                'int_instituation'=>$int_instituation,
                'int_degree'=>$int_degree,
                'int_technology'=>$int_technology,
                'int_duration'=>$int_duration,
                'int_documents'=>$int_documents,
                'int_status'=>$status,
                'int_photo'=>$file_src,
            	  'int_paid_status'=>$int_paid_status,

            ]);

            $details=[
                'name'=>$request->input('int_name'),
                'email'=>$request->input('int_email'),
                'pass'=>$pass,
                
            ];
    
            \Mail::to($int_email)->send(new\App \Mail\TestMail($details));
    
            if($status==-1){
            
                $message="Dear ".$int_name.",\n\nWelcome to Eziline Software house Control Panel!\n\nLogin Url: www.eziline.com/cp/intern\n\nID: ".$int_email."\nPassword: ".$pass."\n\nYour are requested to look at the test given on Portal or sent on your email.\n\nRegards,\nEziline Software House";
                }
                else
                {
                
                          $message="Dear ".$int_name.",\n\nWelcome to Eziline Software house Control Panel!\n\nLogin Url: www.eziline.com/cp/intern\n\nID: ".$int_email."\nPassword: ".$pass."\n\nYour are requested to look at the instructions given on Intern Portal or sent on your email.\nJoin our Whatsapp group here https://chat.whatsapp.com/CuJl11kYvJmHeIXqjG3LRE\n\nRegards,\nEziline Software House.";
                }
    
           $sms=HelperFunction::send_sms($int_cell,$message);
                
               return back();
        }
       
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$int_id)
    {
        
            $users = User::where('int_id', $int_id)->first();
            
        if($users){

            $request->session()->put('user',$users);
        return redirect()->action('DashboadController@index');
        }
    }
    
        public function proadd(Request $request)
    {   


     $int_proj_title=$request->input('int_proj_title');
    $abc=TblInternProjects::where('int_proj_title',$int_proj_title)->first();
    if($abc)
    {
        return redirect()->back()->with('found','Already Taken'); 
    }else{
    
        $int_proj_tasks=0;
        $int_proj_progress=0;
        $int_proj_successrate=0;
        $int_proj_status=0;
        $int_proj_id=$request->input('int_proj_id');
        $gh=$int_proj_id+1;
        $ef='ezi-intern-proj-0'.$gh;
       
        
       
        $int_proj_title=$request->input('int_proj_title');
        $int_proj_tech=$request->input('int_proj_tech');
        $int_proj_internid=$request->input('int_proj_internid');

        $user = User::where('int_id', $request->int_proj_internid)->first();
        
        $def=$user->int_id;
        
        

       
       
     

        
        
        
        $int_proj_tech=$request->input('int_proj_tech');
        $int_proj_internid=$request->input('int_proj_internid');
        $int_proj_startedon=$request->input('int_proj_startedon');
        $int_proj_duration=$request->input('int_proj_duration');
        $int_proj_description=$request->input('int_proj_description');

        $addproj=TblInternProjects::create([
                'int_proj_no'=>$ef,
                'int_proj_title'=>$int_proj_title,
                'int_proj_tech'=>$int_proj_tech,
                'int_proj_internid'=>$def,
                'int_proj_startedon'=>$int_proj_startedon,
                'int_proj_duration'=>$int_proj_duration,
                'int_proj_description'=>$int_proj_description,
                'int_proj_status'=>$int_proj_status,
                'int_proj_tasks'=>$int_proj_tasks,
                'int_proj_progress'=>$int_proj_progress,
                'int_proj_successrate'=>$int_proj_successrate,

        ]);
        $int_proj_title=$request->input('int_proj_title');
        $int_proj_id=$request->input('int_proj_id');
        $gh=$int_proj_id+1;
        $tpsProject='ezi-intern-proj-0'.$gh;
        $tpsStaff=$gh;
        $user = User::where('int_id', $request->int_proj_internid)->first();
        
        $tp=$user->int_id;
        $tpsModule=$request->input('tpsModule');
        $tpsDate=$request->input('int_proj_startedon');
       
        $user = User::where('int_id', $request->int_proj_internid)->first();
        
        $op=$user->int_cell;
        

        $user = User::where('int_id', $request->int_proj_internid)->first();
        
        $pp=$user->int_name;
        

        $add=TblIprojectStaff::create([
                'tpsModule'=>$tpsModule,
                'tpsProject'=>$tpsProject,
                'tpsStaff'=>$tp,
                'tpsDate'=>$tpsDate,
                

        ]);

        $message="Dear ".$pp.",\n\nYou have been assigned to project ".$int_proj_title." for service 'Discussion'. Kindly, login to Eziline Intern Portal to view details. .\n\nRegards,\nEziline Software House";
        $sms=HelperFunction::send_sms($op,$message);
         return redirect()->back()->with('success','Successfully Assigned'); 
        }
       
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
      public function absent()
    {   
        $users=User::where('int_status','0')->get();
        $newusers=array();
       foreach ($users as $key => $value) {
          $att=tbl_intern_attend::where('att_intern',$value->int_no)->get();
          
            $count=0;
            
            foreach ($att as $k => $v) {

                if($v->att_holiday==1)
                {
                    $count++;
                }
            }
            if($count >=14)
            {
                $value->holidays=$count;
                array_push($newusers,$value);
            }
           
             
       }
       return response()->json([
        'users'=>$newusers,
        
     ],200);
        
    }
    
    public function edit($id)
    {
        //
    }
    
        public function attdelete($att_id)
    {
        
        tbl_intern_attend::where('att_id',$att_id)->delete();
        return back();
    }

    public function attupdate(Request $request, $att_id)
    {
        $upatt=tbl_intern_attend::where('att_id',$att_id)->first();
        $att_in=$request->input('att_in');
        $att_out=$request->input('att_out');
        $att_work=$request->input('work_done');
        $att_holiday=$request->input('att_holiday');

        $upatt->att_in=$att_in;
        $upatt->att_out=$att_out;
        $upatt->att_work=$att_work ;
        $upatt->att_holiday=$att_holiday;

        
        $upatt->save();

        return back();

        
    }
    
        public function addatt(Request $request)
    {

        $date=date('Y-m-d');
        $att_intern=$request->input('att_intern');
        $fdate=$request->input('fdate');
        $tdate=$request->input('tdate');
        $att_in=$request->input('att_in');
       
        $att_out=$request->input('att_out');
        $work_done=$request->input('work_done');


        $begin = new DateTime($fdate);
        $end = new DateTime($tdate);
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        

        foreach($daterange as $d)
        {   
            if($d>$date)
            {
                $att=tbl_intern_attend::where('att_intern',$att_intern)->where('att_date',$d->format('Y-m-d'))->first();
                
             
                    if($att==null)
                    {   
                           
                            
                        $add=tbl_intern_attend::create([
                            // 'att_marked_on'=>$request->input('att_marked_on'),
                            'att_intern'=>$att_intern,
                            'att_date'=>$d->format('Y-m-d'),
                            'att_marked_on'=>$d->format('Y-m-d'),
                            'att_in'=>$att_in,
                            'att_out'=>$att_out,
                            'att_holiday'=>"0",
                            'att_work'=>$work_done,
                        ]);
                       
                    }
                    
            }
            else
            {
                break;
            }
        }

        return back();
    }
    
    public function attsheet($int_id)
    {
        $attsheet=User::where('int_id',$int_id)->first();
      
        
        $hours=array();
 $hoursworked="0:0:0";
        $s=0;
        $m=0;
        $h=0;
        $i=0;
      $views=tbl_intern_attend::where('att_intern',$attsheet->int_no)->orderBy('att_id', 'DESC')->paginate(50);
      
      
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
   $count=tbl_intern_attend::where('att_intern',$attsheet->int_no)->where('att_out','00:00:00')->get();

        return view('sup.internatt',compact('views','hours','i','count','attsheet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function update(Request $request, $int_id)
    {  
        $int_name=$request->input('int_name');
        $int_email=$request->input('int_email');
        $int_cell=$request->input('int_cell');
        $int_join_date=$request->input('int_join_date');
        $int_cnic=$request->input('int_cnic');
        $int_dob=$request->input('int_dob');
        $int_instituation=$request->input('int_instituation');
        $int_degree=$request->input('int_degree');
        $int_technology=$request->input('int_technology');
        $int_duration=$request->input('int_duration');
        $int_documents=$request->input('int_documents');
        $int_status=$request->input('int_status');
        $weak_mem=$request->input('weak_mem');
	$mon_mem =$request->input('mon_mem');
	$int_job_status =$request->input('int_job_status');
        $int_video =$request->input('int_video');
        $int_hiredby =$request->input('int_hiredby');
          if($int_status==-1)
        {
            $int_paid_status=0;
            
        }else{
            $int_paid_status=1;
          
        }

    
        $internupdate=User::where('int_id',$int_id)->first();
        $internupdate->int_name=$int_name;
        $internupdate->int_email=$int_email;
        $internupdate->int_cell=$int_cell;
        $internupdate->int_join_date=$int_join_date;
        $internupdate->int_cnic=$int_cnic;
        $internupdate->int_dob=$int_dob;
        $internupdate->int_instituation=$int_instituation;
        $internupdate->int_degree=$int_degree;
        $internupdate->int_technology=$int_technology;
        $internupdate->int_duration=$int_duration;
        $internupdate->int_documents=$int_documents;
        $internupdate->int_status=$int_status;
        $internupdate->weak_mem=$weak_mem;
        $internupdate->mon_mem=$mon_mem ;
        $internupdate->int_job_status=$int_job_status ;
        $internupdate->int_video=$int_video ;
        $internupdate->int_hiredby=$int_hiredby ;
         $internupdate->int_paid_status=$int_paid_status ;

        
        $internupdate->save();
        
        
        $amount=$request->input('amount');

        $internaccount=InternAccount::where('bank_name','Interns Account')->first();
        $abc=$internaccount->bank_balance+$amount;
       

        $internaccount1=InternAccount::where('bank_name','Interns Account')->first();
        
        $internaccount1->bank_balance=$abc;
        

        $internaccount1->save();

        $intname=User::where('int_id',$int_id)->first();
        $name=$intname->int_name;
        $int_no=$intname->int_no;

        $date=date('Y-m-d');

        $amount=$request->input('amount');
        
          $amount=$request->input('amount');
        if($amount==null){


        }else{

        $desc=$int_no.'| '.$name.'| Registration Fee Paid Received by Ismail Sir';

        $addamount=Bank::create([
                
            'bank_id'=>'ezi-bank-012',
            'tra_date'=>$date,
            'tra_op'=>'+',
            'tra_desc'=>$desc,
            'tra_amount'=>$amount,
       
            
            

        ]);
         $intamount=IntAmount::create([
                
            'int_no'=>$int_no,
            'am_date'=>$date,
            'int_amount'=>$amount,
       
            
            

        ]);
        }
        
        
        return back();
    }
    
    
      public function msgstatus(Request $request)
    {   
        
        
        $messagetostatus=array();
// $response=array();
        $status=$request->input('status');
        $int_technology=$request->input('int_technology');
        
        $msg=$request->input('message');
        if($status=='selective'){
            $int_technology=$request->input('int_technology');
            
            $check=User::where('int_technology',$int_technology)->get();
            
            foreach ($check as $user) {
                $no=$user->int_cell;
                $name=$user->int_name;
    
                $message="Dear ".$name.",\n\n".$msg." .\n\nRegards,\nEziline Software House";
             // dd($message);
               
             $sms=HelperFunction::send_sms($no,$message);
             array_push($messagetostatus,$name);
               
            } 
            return response()->json([
               'name'=>$messagetostatus,
            ],200);
        }
        elseif($status=='all')
        {
           
            $check=User::all();
            
            foreach ($check as $user) {
                $no=$user->int_cell;
                $name=$user->int_name;
    
                $message="Dear ".$name.",\n\n".$msg." .\n\nRegards,\nEziline Software House";
             // dd($message);
               
             $sms=HelperFunction::send_sms($no,$message);
             array_push($messagetostatus,$name);
               
            } 
            return response()->json([
               'name'=>$messagetostatus,
            ],200);
        
        }elseif($request->input('int_technology')){
        
                
            $check=User::where('int_status',$status)->where('int_technology',$int_technology)->get();
            
            foreach ($check as $user) {
                $no=$user->int_cell;
                $name=$user->int_name;
    
                $message="Dear ".$name.",\n\n".$msg." .\n\nRegards,\nEziline Software House";
             // dd($message);
               
             $sms=HelperFunction::send_sms($no,$message);
             array_push($messagetostatus,$name);
               
            } 
            return response()->json([
               'name'=>$messagetostatus,
            ],200);
        }
        else
        {
       $check=User::where('int_status',$status)->get();
       
        foreach ($check as $user) {
            $no=$user->int_cell;
            $name=$user->int_name;

            $message="Dear ".$name.",\n\n".$msg." .\n\nRegards,\nEziline Software House";
         // dd($message);
           
         $sms=HelperFunction::send_sms($no,$message);
         array_push($messagetostatus,$name);
           
        } 
        return response()->json([
           'name'=>$messagetostatus,
        ],200);
    }
        
      
    

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($int_id)
    {
        User::where('int_id',$int_id)->delete();
        return back();
    }
    
     public function delete($int_proj_id)
    {

        TblInternProjects::where('int_proj_id',$int_proj_id)->delete();
        return back();
    }
}
