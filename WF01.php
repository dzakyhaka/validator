<?php 
error_reporting(0);
date_default_timezone_set('GMT');
require('WF01.setup.php');
require('phpmailer/PHPMailerAutoload.php');
$setUP = new setUP();
$account = $setUP->authenticate();
$year  = date(", Y");
$month  = date("M");
$day = (date("d"));
$tanggalan = ($day." ".$month.$year) ;
$banner = "   
=====================================================================
 _       ________   _________    __  ___________  __          ___ 
| |     / / ____/  / ____/   |  /  |/  /  _/ /\ \/ /  _   __ |__ \
| | /| / / __/    / /_  / /| | / /|_/ // // /  \  /  | | / / __/ /
| |/ |/ / /___   / __/ / ___ |/ /  / // // /___/ /   | |/ / / __/ 
|__/|__/_____/  /_/   /_/  |_/_/  /_/___/_____/_/    |___(_)____/ 
                                                                  
=====================================================================
Creator : Febriyanto Hamonangan Manurung
https://www.facebook.com/founder.wefamily
(c) 2017 Powered by We Family.
Update : 
- Random Email Send, Random Name Send, Random Subject Send.
- Use 2 - 3 SMTP
- Automatically replace smtp if smtp exceeds send limit in settings
Version ".$setUP->version()."
\n";

print $banner;
echo "[ Input You List                ]   	:  ";
$list       = rtrim( fgets( STDIN));
echo "[ Remove Duplicate 0=Yes / 1=No ]	:  ";
$duplicate  = rtrim( fgets( STDIN));
$mailist    = $setUP->cekfile($list, $duplicate);
echo "[ Input Letter                  ]   	:  ";
$letter     = rtrim( fgets( STDIN));
$no         = 1;
$nomail     = 1;
print "
=====================================================================
INFO SEND : 
- From Email : ".$account['from']."
- From Name  : ".$account['name']."
- Subject    : ".$account['subject']."
- Reconnect  : ".$account['delay']['email']." Email / ".$account['delay']['time']." Detik
- Total List : ".$mailist['total']."
=====================================================================\n";
$emailist = $mailist['list'];
foreach ($emailist as $email){
    $fiture = array(
            '[WFemail]' => $email, //auto get email terget
            '[WFcountry]' => $setUP->random_country(), //auto get Random Country
            '[WFIP]' => $setUP->random_IP(), //auto get Random IP
            '[WFnumber]' => $setUP->random_number(9), //auto get case ID
            '[WFtime]' => $tanggalan, //auto get day
			'[WFbrowser]' => $setUP->random_browser(), //auto get random browser
			'[WFLink]' => "https://www.facebook.com/founder.wefamily", //gunakan direct link scam dengan flyt.it / ow.ly / biyly
            );
            
	    if ($no === $account['delay']['email']) { 
            echo "=== Delay in ".$account['delay']['time']." ===\n"; 
	        sleep($account['delay']['time']);
            echo trim("[".$nomail." / ".$mailist['total']."] Email : ".$email."");
	        $no = 0;
	    }else{
            echo trim("[".$nomail." / ".$mailist['total']."] Email : ".$email."");
        }
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPDebug        = 0; 
    $mail->SMTPAuth         = true; 
    $mail->SMTPKeepAlive    = true;
    $mail->SMTPSecure       = $account['secure'];
    $mail->Host             = $account['server'];
    $mail->Port             = $account['port']; 
	$mail->Limit			= $account['limit'];
    $mail->Username         = $account['username'];
    $mail->Password         = $account['password'];
    $mail->Subject          = $setUP->checksubject($account['subject'], 5);
    $mail->SetFrom($account['from'], $account['name']);
    $mail->Priority         = 1;
    $mail->Encoding         = 'base64';
    $mail->CharSet          = 'UTF-8';
    $mail->ContentType      = 'text/html';
    $mail->Body             = $setUP->getLetter($fiture, $letter);
    $mail->AddAddress($email);
        if($mail->send()){
            echo " [ Success Spammed ! ]\r\n";
        }else{
            echo " [ Failed Spammed ! ]\r\n";
            $file = fopen("Failed.txt", "a");
            fwrite($file,"Failed => ". $email." | ".$mail->ErrorInfo." | Tanggal : ".$tanggalan."\r\n");
            fclose($file);
        }
$nomail++;
$no++;
}
print 
"+=========== Done ===========+
+Success Send ".$tanggalan." ".$waktu."
+=== Powered by We Family ===+\n";
?>