<?php
$worker= new GearmanWorker();
$worker->setId('1');
$worker->addServer("gearman");
$worker->addFunction("wait", "job_function");

while ($worker->work());


function job_function($job)
{
    $date = new DateTime();
    $datetime= date_format($date, 'Y-m-d H:i:s');
    $payload = explode(",",$job->workload());
    echo "Hola";
    shell_exec($payload[0]);
    echo "adios";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://webserver/api/jobs/".$payload[1]."/work",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => "processor_id="."1"."&started_at=".$datetime,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  return 0;
}
?>