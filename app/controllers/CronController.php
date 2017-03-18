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

        $report = OrdersService::generateReport(Input::get('level'));

        $message = new EmailMessage();
        $message->from(config('mail.username'), config('mail.displayName'));
        $message->to(config('email_orders.recipient'))->bcc(config('mail.username'));
        $message->html($report);

        $nextMonday = date('d', strtotime('next monday'));
        $nextFriday = date('d', strtotime('next friday'));

        $message->title('Zamówienie '.$nextMonday.'-'.$nextFriday);

        $retries = 0;
        while (($status = Email::sendEmail($message)) !== true){
            $retries++;
        }

        echo json_encode(['success' => $status, 'retries' => $retries]);

    }

}