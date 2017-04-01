<?php


class CronController extends Controller
{

    public function send(){

        header('Content-Type: application/json');

        $whitelist = [
            '127.0.0.1',
            '::1'
        ];

        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            echo json_encode(['error' => 'Access denied']);
            die();
        }

        $nextMonday = date("Y-m-d", strtotime('Next Monday'));
        $report = OrdersService::generateReport(Input::get('level'), $nextMonday);

        if (empty($report)){
            $report = "Brak zamówień";
        }

        $message = new EmailMessage();
        $message->from(config('mail.username'), config('mail.displayName'));

        if (Input::has('target')){
            $recipients = $message->to(Input::get('target'));
        }else{
            $recipients = $message->to(config('email_orders.recipient'));
        }

        $recipients->bcc(config('mail.username'));

        foreach (config('email_orders.bcc') as $bcc_email){
            $recipients->bcc($bcc_email);
        }

        $message->html($report);

        $nextMonday = date('d', strtotime('next monday'));
        $nextFriday = date('d', strtotime('next friday'));

        $message->title('Zamówienie '.$nextMonday.'-'.$nextFriday);

        $retries = 0;
        while (($status = Email::sendEmail($message)) !== true){
            $retries++;
            sleep(2);
        }

        echo json_encode(['success' => $status, 'retries' => $retries]);

    }

}