<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Workgroup;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class CustomLoginController extends Controller
{
    public function index()
    {
        $result = Workgroup::where('WGTYPE', 'EXTERNAL')->where('WGSTATUS', 'ACTIVE')->groupBy('wgname')->pluck('wgname');
        $sno = null;
        $conname = null;
        return view('auth.login', compact('result', 'sno', 'conname'));
    }

    public function doLogin()
    {
        if ($_POST['submitbtn'] == 'login') {
            if (Auth::attempt(request()->only('sno', 'password', 'conname'))) {

                return redirect('/maininventory');
            }
            return back()->withErrors([
                'genaral' => Session::get('loginerr'),
            ])->withInput(request()->input());
        } else if ($_POST['submitbtn'] == 'otp') {
            $reply =  $this->getOTP(request());
            if ($reply['responce']) {
                return back()->withInput(request()->input());
               
            } else {
                return back()->withErrors([
                    'genaral' => $reply['message'],
                ])->withInput(request()->input());
            }
        } else {
            return back()->withErrors([
                'genaral' => "Invalid Action",
            ]);
        }
    }



    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }


    private function getOTP(Request $request)
    {
        $client = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $url = 'http://172.25.2.134:7650/ftthcore/otp/';

        try {
            $response = $client->request('POST', $url, [
                \GuzzleHttp\RequestOptions::JSON => [
                    'username' => $request->input('sno')
                ],
            ]);
        } catch (ClientException  $e) {
            $notify_msg = array(
                'responce' => false,
                'message' => 'Client Exception Occured. Please contact Admin',
                'alert-type' => 'danger'
            );
        } catch (ConnectException $e) {
            $notify_msg = array(
                'responce' => false,
                'message' => 'Connection Exception Occured. Please contact Admin',
                'alert-type' => 'danger'
            );
        }
        $array = json_decode($response->getBody()->getContents(), true);
        if ($array["ERROR"] == false) {
            $notify_msg = array(
                'responce' => true,
                'message' => 'Success',
                'alert-type' => 'success'
            );
        } else {
            $notify_msg = array(
                'responce' => false,
                'message' => $array["MSG"] ?: "Something went wrong. Please try again",
                'alert-type' => 'danger'
            );
        }



        return $notify_msg;
    }
}
