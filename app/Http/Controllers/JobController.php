<?php

namespace App\Http\Controllers;

use App\Job;
use App\Jobs\Work;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {        
        $client = new \GearmanClient();
        $client->addServer("gearman");
        $something = $client->doBackground("wait","10,1");
        return $something;
        return Job::all();
    }
    
    public function store(Request $request)
    {
        $client = new \GearmanClient();
        $client->addServer("gearman");
        $queued_jobs = [];
        foreach($request->input('jobs') as $new_job){
            $job = new Job;
            $job->user_id = $new_job['user_id'];
            $job->priority = $new_job['priority'];
            $job->command = $new_job['command'];
            
            switch ($job->priority) {
            case "high":
                $work_id = $client->doHighBackground("wait",$job->command.",".$job->id , $job->id);
                break;
            case "medium":
                $work_id = $client->doBackground("wait",$job->command.",".$job->id , $job->id);
                break;
            case "low":
                $work_id = $client->doLowBackground("wait",$job->command.",".$job->id , $job->id);
                break;
            }
            $job ->work_id = $work_id;
            $job->save();
            array_push($queued_jobs,$job->id); 
        }
        return $queued_jobs;
    }


    public function show($id)
    {   
        $client = new \GearmanClient();
        $client->addServer("gearman");
        $job = Job::find($id);
        $job->status = $client->jobStatus($job->work_id);
        return $job;
    }


    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        if($job->processor_id !== null){
            return response('process already running',401);
        }
        $job->user_id = $request->input('user_id');
        $job->priority = $request->input('priority');
        $job->command = $request->input('command');
        $job->save();
        return response('ok',200);
        
    }

    public function working(Request $request, $id)
    {
        $date = new \DateTime();
        $datetime= date_format($date, 'Y-m-d H:i:s');
        $job = Job::findOrFail($id);
        if($job->processor_id !== null){
            return response('process already running',401);
        }
        $job->processor_id = $request->input('processor_id');
        $job->started_at = $request->input('started_at');
        $job->ended_at = $datetime;
        $job->save();
        return $job;
        return response('ok',200);
        
    }

    public function destroy($id)
    {
        Job::find($id)->delete();
        return response('ok',204);
    }
}
