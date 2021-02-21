<?php

namespace App\Http\Controllers;

use App\TblInternProjectsTask;
use Illuminate\Http\Request;
use App\TblInternProjects;
use Storage;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use App\TblProjectsData;

class TblInternProjectsTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $projects=TblInternProjects::where('int_proj_internid',session()->get('user')->int_no)->get();

        return view('project',compact('projects'));
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
        //       $add=TblInternProjectsTask::create([

        // // 'att_marked_on'=>$request->input('att_marked_on'),
        // 'task_proj_id'=>$request->input('task_proj_id'),
        // // 'int_address'=>$request->input('int_address'),
        // // 'int_lat'=>$request->input('int_lat'),
        // // 'int_lng'=>$request->input('int_lng'),
        // // 'att_date'=>$currentDate,
        // // 'att_in'=>$currentTime,
        // // 'att_marked_on'=>$markerdon,
        


        // ]);dd($add);

       

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TblInternProjectsTask  $tblInternProjectsTask
     * @return \Illuminate\Http\Response
     */
    public function show(TblInternProjectsTask $tblInternProjectsTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TblInternProjectsTask  $tblInternProjectsTask
     * @return \Illuminate\Http\Response
     */
    public function edit(TblInternProjectsTask $tblInternProjectsTask)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TblInternProjectsTask  $tblInternProjectsTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TblInternProjectsTask $tblInternProjectsTask,$task_id)
       {    
       
       ini_set('default_socket_timeout', 6000);
        
        date_default_timezone_set('Asia/Karachi');
             $datadeliveredon   =date('Y-m-d H:i:s');
            
          $projid = $request['data_related_to'];

           $projName=$request->input("projtitle");
            $projtitle=$request->input("tasktitle");


         if($request->hasFile('file')){
          
$var=array();
$keys=array();
$count=0;
            foreach( $request->file as $file){
                $extension=$file->getClientOriginalName();
             
                $filename=session()->get('user')->int_name.' '.'Task Title:('.$projtitle.')'.' '.'Project:('.$projName.')'.time().'.'.$extension;

                $client = new Client(env('DROPBOX_TOKEN'));


        $adapter = new DropboxAdapter($client);

        $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

        $uploaded=$filesystem->put('intern-NS/attachments/'.$filename,file_get_contents($file));
        $link=$client->createSharedLinkWithSettings('intern-NS/attachments/'.$filename);
                array_push($var, $link);

            $abc=$var[$count++]['url'];
        $def=substr($abc,0,-1);
        $ghi=$def."1";
   
        $data=TblProjectsData::create([
 


      'data_related_to'=>$projid,
      'data_located'=>$ghi,
      'data_delivered_on'=>$datadeliveredon,
    
      
     
   
        
      
        
        


        ]);
         }
        }
         $proj=TblInternProjectsTask::where('task_id',$task_id)->first();
         // dd($proj);
           
          $projendedon=date('Y-m-d');
            $task_status='-1';

         // dd($proj);
   
                   // dd($proj->task_status);

                    $proj->task_end_date=$projendedon;
                    
                    $proj->task_status=$task_status;
                    

                   



        $proj->save();


        $noti=Notification::create([

       
            'message'=>'Task uploaded',
            'user_id'=>session()->get('user')->int_id,
            'proj_id'=>$proj_id,
            'noti_status'=>$noti_status,
            'date'=>$date,
            'db_file'=>$file_src,
            
            
            
            
            
    
    
            ]);

       

        return back();
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TblInternProjectsTask  $tblInternProjectsTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(TblInternProjectsTask $tblInternProjectsTask)
    {
        //
    }
}
