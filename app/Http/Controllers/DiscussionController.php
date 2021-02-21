<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TblInternProjects;
use App\Discussion;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use App\User;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use App\tbl_intern_attend;
use App\Notification;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       

       

      
        
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



        $request->validate([
          
            'db_message' => 'required:tbl_dboard',
            
        ]);

        $noti_status=0;
        $db_sales=0;
        $db_type='3';
        $file_src=$request->file("db_file");
        $projName='abc';
        
        $filename= pathinfo($file_src,PATHINFO_FILENAME);
        $extension=$file_src->getClientOriginalExtension();
        
        $filename=session()->get('user')->int_name.' '.'(Discussion)'.' '.'Project:('.$projName.')'.time().'.'.$extension;
        
        
       
       
      


        

       $client = new Client(env('DROPBOX_TOKEN'));


      $adapter = new DropboxAdapter($client);

      $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

      $uploaded=$filesystem->put('intern-NS/attachments/'.$filename,file_get_contents($file_src));
      
 	$link=$client->createSharedLinkWithSettings('intern-NS/attachments/'.$filename);
      // dd($link);
      $abc=$link['url'];
      $def=substr($abc,0,-1);
      $ghi=$def."1";
     

     
         
        $date=date('Y-m-d H:i:s');
        $add=Discussion::create([

       
            'db_message'=>$request->input('db_message'),
            'use_id'=>$request->input('use_id'),
            'project_id'=>$request->input('project_id'),
            'db_sales'=>$db_sales,
            'db_type'=>$db_type,
             'date'=>$date,
             'db_file'=>$ghi,
            
            
            
            
            
    
    
            ]);

            $noti=Notification::create([

       
                'message'=>$request->input('db_message'),
                'user_id'=>$request->input('use_id'),
                'proj_id'=>$request->input('project_id'),
                'noti_status'=>$noti_status,
                'date'=>$date,
                'db_file'=>$ghi,
                
                
                
                
                
        
        
                ]);
            
                  $details=[
            'title'=>$request->input('mailfrom'),
            'project'=>$request->input("projtitle"),
            'body'=>$request->input('db_message'),
            'Attachment'=>$ghi,
        ];
       // \Mail::to('me@eziline.com')->send(new\App \Mail\TestMail($details));
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
    public function edit($int_proj_no)
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
       

          
          
      
        
        
      
        
       
          
          
        
        return view('discussionboard',compact('project','int_proj_no','attendance'));

            }

            public function intdisc($id)
            { 
                
                $messagereply=array();
                $keys=array('name','image','discuss','date','file');
                // $project = TblInternProjects::where('int_proj_no',$id)->first();

      
              
                $shows=DB::table('tbl_intern')->join('tbl_sup_notification', 'tbl_intern.int_id', '=', 'tbl_sup_notification.user_id')->where('proj_id',$id)->get();
                
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


            public function discreply(Request $request,$id)
            { 
                

                $noti_status=0;
                
                
              
                $file_src=$request->file("db_file");
                $comment=$request->input("comment");
                $user_id=$request->input("user_id");
                $proj_id=$request->input("proj_id");
              
            //     $filename= pathinfo($file_src,PATHINFO_FILENAME);
            //     $extension=$file_src->getClientOriginalExtension();
                
            //     $filename=session()->get('user')->int_name.' '.'(Discussion)'.time().'.'.$extension;
               
                
               
               
              
        
        
                
        
            //    $client = new Client(env('DROPBOX_TOKEN'));
                
        
            //   $adapter = new DropboxAdapter($client);
                
            //   $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);
              
            //   $uploaded=$filesystem->put('intern-NS/attachments/'.$filename,file_get_contents($file_src));
             
            //  $link=$client->createSharedLinkWithSettings('intern-NS/attachments/'.$filename);
            //   dd($link);
            //   $abc=$link['url'];
            //   $def=substr($abc,0,-1);
            //   $ghi=$def."1";
            //   dd($ghi);
             
        
             
                 
                $date=date('Y-m-d H:i:s');
                $noti=Notification::create([

       
                    'message'=>$comment,
                    'user_id'=>$user_id,
                    'proj_id'=>$proj_id,
                    'noti_status'=>$noti_status,
                    'date'=>$date,
                    'db_file'=>$file_src,
                    
                    
                    
                    
                    
            
            
                    ]);

                    $messagereply=array();
                $keys=array('name','image','discuss','date','file');
                // $project = TblInternProjects::where('int_proj_no',$id)->first();

      
              
                $shows=DB::table('tbl_intern')->join('tbl_sup_notification', 'tbl_intern.int_id', '=', 'tbl_sup_notification.user_id')->where('proj_id',$id)->orderBy('tbl_sup_notification.created_at', 'DESC')->get();
            //    dd($shows);
               
                $name=$shows[0]->int_name;
                $image=$shows[0]->int_photo;
                $discuss=$shows[0]->message;
                $file=$shows[0]->db_file;
                $date=$shows[0]->date;
                array_push($messagereply,array_combine($keys,[$name,$image,$discuss,$date,$file]));

                
               return response()->json([
                "messagereply"=> $messagereply
                ],200);

                    
                

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
