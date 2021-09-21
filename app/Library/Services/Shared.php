<?php
namespace App\Library\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Logs;
use App\Product;
use PDF;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
  
class Shared
{
    
    public function logs($msg)
    {
        try{
            $data = new Logs;

            $data->created_by = 'SYSTEM';
            $data->message =$msg;            
            $data->save();
            Log::info('Save log succesfully : '.json_encode($data));
        }
        catch (Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function getDropdown()
    {
        $data = array(
            'product' =>  Product::latest()->get()->toArray(),
        );
        return $data;
    }

    public function getLink($order)
    {
        return base64_encode($order->order_no.$order->phone.$order->email.$order->transaction_date);
    }

    public function sendEmail($email,$datas)
    {
        $data = array('data'=>$datas);        
        try{
            Mail::send('mail', $data, function($message) use ($email){
                $message->subject('Your Order');
                $message->to($email);             
             });

            Log::info('Succesfully send email to: '.json_encode($email));
            return array('status' => true, 'message' => 'Berhasil kirim email');
        }
        catch (Exception $e){
            Log::error($e->getMessage());
            return response (['status' => false,'errors' => $e->getMessage()]);
        }
    }

}