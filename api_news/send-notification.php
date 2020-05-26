<?php

    function sendMessage($title, $cat_name, $description, $external_link, $big_image) 
    {
        $content = array(
                         "en" => $description                                                 
                         );

        $fields = array(
                        'app_id' => "e35764e1-d593-4576-9525-18e8ff3d43f4",
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=> "0","cat_name"=>$cat_name, "external_link"=>$external_link),
                        'headings'=> array("en" => $title),
                        'contents' => $content,
                        'big_picture' => $big_image         
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic ZjkyODY4Y2YtZTMyMy00NjQ2LTk3NTItYTAzNjllOWYxM2Fh'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);        
        
        echo "Congratulations, push notification sent...";

        return $response;
    }

    echo sendMessage("daily title", "sport", "the daily news notification", "www.eradroids.com", "http://i.imgur.com/N8SN8ZS.png");
?>