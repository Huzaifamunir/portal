<?php

namespace App\Http\Controllers;

use App\TblInternProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use DB;
use App\TblInternProjectsTask;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use App\tbl_intern_attend;
use DateTime;
use DatePeriod;
use DateInterval;
use App\TblProjectsData;



class TblInternProjectsController extends Controller
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
        $projectsprogs=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','0')->get();
        $cprojects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->where('int_proj_status','1')->get();
        //$project = TblInternProjects::where('int_proj_internid',session()->get('user')->int_id)->first();
     
       // $tasks=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')
       // ->where('tbl_intern_projects.int_proj_no',$project->int_proj_no)->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();
        
      

        return view('project',compact('projects','attendance','projectsprogs','cprojects','interns'));
           }else {
            
             return redirect('login');
        }
    }
    public function screenshots(Request $request)
    {
        $links= array();
        $projects=TblInternProjectsTask::where('task_project_id',$request->id)->get();
        
        foreach($projects as $pro)
       {
        $links= array();
        $data=TblProjectsData::where('data_related_to',$pro->task_id)->get();
       
        $datacount=TblProjectsData::where('data_related_to',$pro->task_id)->count();
        
        foreach($data as $image)
        {
            $image->data_located;
        array_push($links,$image->data_located);
        }
       }

       
       
       return response()->json([
       "links"=> $links,
       "datacount"=>$datacount
       ],200);
        
    }

    public function uploadToDropboxFile(Request $request, TblInternProjects $tblInternProjects,$int_proj_id)
    {
        $proj=TblInternProjects::where('int_proj_id',$int_proj_id)->first();
        
         
    

          $projName=$request->input("imagetitle");

         // dd($proj);

          $file_src=$request->file("int_proj_image");


          $filename= pathinfo($file_src,PATHINFO_FILENAME);
          $extension=$file_src->getClientOriginalExtension();
          $filename=session()->get('user')->int_name.' '.'Project Image:'.$projName.time().'.'.$extension;
         
         
        


          

         $client = new Client(env('DROPBOX_TOKEN'));


        $adapter = new DropboxAdapter($client);

        $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

        $uploaded=$filesystem->put('intern-NS/attachments/'.$filename,file_get_contents($file_src));
        
        $link=$client->createSharedLinkWithSettings('intern-NS/attachments/'.$filename);
         $abc=$link['url'];
        $def=substr($abc,0,-1);
        $ghi=$def."1";

        
         



   
            if($uploaded){
                   $proj->int_proj_image=$ghi;
                  
                  

              
                    
                   



        $proj->save();
            return back()->withErrors(['msg'=>'Succesfuly file uploaded to dropbox']);
             } else {
            return back()->withErrors(['msg'=>'file failed to uploaded on dropbox']);
            } 

            // $is_file_uploaded=$request->input('upload_file');
            // dd($is_file_uploaded);

       

        return back();
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
        
        $task_status='0';
        $request->validate([
          
            'task_description' => 'required:tbl_intern_projects_tasks',
            
        ]);

          $add=TblInternProjectsTask::create([

       
        'task_no'=>$request->input('task_no'),
        
        'task_title'=>$request->input('task_title'),
        'task_start_date'=>$request->input('task_start_date'),
        'task_project_id'=>$request->input('task_project_id'),

        'task_description'=>$request->input('task_description'),
        'task_duration'=>$request->input('task_duration'),
       
        'task_delivered_on'=>$request->input('task_delivered_on'),
        'task_status'=>$task_status,
        
        


        ]);
       return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TblInternProjects  $tblInternProjects
     * @return \Illuminate\Http\Response
     */
    public function show(TblInternProjects $tblInternProjects)
    {
        //  $task=TblInternProjectsTask::all();
        // return view('project',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TblInternProjects  $tblInternProjects
     * @return \Illuminate\Http\Response
     */
public function edit(TblInternProjects $tblInternProjects,$int_proj_id)
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
            
        $project = TblInternProjects::where('int_proj_id',$int_proj_id)->first();
     
        $tasks=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('tbl_intern_projects.int_proj_no',$project->int_proj_no)->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();
       
$project1 = TblInternProjects::where('int_proj_id',$int_proj_id)->first();
     
        $task1s=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('tbl_intern_projects.int_proj_no',$project1->int_proj_no)->where('task_status','1')->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();
       
         $project2 = TblInternProjects::where('int_proj_id',$int_proj_id)->first();
     
        $taskdelivered=DB::table('tbl_intern_projects')->join('tbl_intern_projects_tasks','tbl_intern_projects_tasks.task_project_id','=','tbl_intern_projects.int_proj_id')->where('tbl_intern_projects.int_proj_no',$project2->int_proj_no)->where('task_status','-1')->where('tbl_intern_projects.int_proj_internid',session()->get('user')->int_id)->get();
       
        return view('projectdetails',compact('project','tasks','task1s','taskdelivered','attendance'));

          }else {
            
             return redirect('login');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TblInternProjects  $tblInternProjects
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TblInternProjects $tblInternProjects,$int_proj_id)
    {

         $proj=TblInternProjects::where('int_proj_id',$int_proj_id)->first();

          $projendedon=date('Y-m-d');
          $projstatus='1';

    
          $projName=$request->input("abc");
           $projtasks=$request->input("int_proj_tasks");

         // dd($proj);

          $file_src=$request->file("upload_file");


          $filename= pathinfo($file_src,PATHINFO_FILENAME);
          $extension=$file_src->getClientOriginalExtension();
          $filename=session()->get('user')->int_name.' '.'Project:'.$projName.time().'.'.$extension;
         
        


          

         $client = new Client(env('DROPBOX_TOKEN'));


        $adapter = new DropboxAdapter($client);

        $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

        $uploaded=$filesystem->put('intern-NS/attachments/'.$filename,file_get_contents($file_src));
        
        $link=$client->createSharedLinkWithSettings('intern-NS/attachments/'.$filename);
 	$abc=$link['url'];
        $def=substr($abc,0,-1);
        $ghi=$def."1";



   
            if($uploaded){
                   $proj->int_proj_dropbox=$ghi;
                 
                  

                   
                    $proj->int_proj_endedon=$projendedon;
                    $proj->int_proj_status=$projstatus;
                    $proj->int_proj_tasks=$projtasks;
                    
                   



        $proj->save();
            return back()->withErrors(['msg'=>'Succesfuly file uploaded to dropbox']);
             } else {
            return back()->withErrors(['msg'=>'file failed to uploaded on dropbox']);
            } 

            // $is_file_uploaded=$request->input('upload_file');
            // dd($is_file_uploaded);

       

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TblInternProjects  $tblInternProjects
     * @return \Illuminate\Http\Response
     */
    public function destroy(TblInternProjects $tblInternProjects)
    {
        //
    }
}
