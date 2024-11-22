<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ExternalApiUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return new GenericUser([
            'id' => $identifier,
            'email' => $identifier,
            'name' => $identifier,
        ]);
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!array_key_exists('sno', $credentials)) {
            return null;
        }

        // GenericUser is a class from Laravel Auth System
        return new GenericUser([
            'id' => $credentials['sno'],
            'sno' => $credentials['sno'],
        ]);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($credentials['sno'] and $credentials['password']) {
            //$email = $credentials['email'];
           // $password = $credentials['password'];
            $client = new \GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $url = 'http://172.25.2.134:7650/ftthcore/login/';//'https://serviceportal.slt.lk/ApiNeylie/public/userlogin';

            try {
                $response = $client->request('POST', $url, [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'uname' => $credentials['sno'],
                        'passwd' => $credentials['password'],
                        'conname' => strtoupper($credentials['conname'])
                    ],
                ]);
            } catch (ClientException  $e) {
               // Session::put('loginerr',  $e->getMessage());
                Session::put('loginerr',  "Client Exception Occured. Please contact Admin");
                return false;
            } catch (ConnectException $e){
               // Session::put('loginerr',  $e->getMessage());
               Session::put('loginerr',  "Connection Exception Occured. Please contact Admin");
                return false;
            }

            $array = json_decode($response->getBody()->getContents(), true);
             //dd($array["ERROR"]);
            if ($array["ERROR"] == false) {


                $userInfo = $array["DATA"];
                //dd($userInfo);
                $userx = new GenericUser($userInfo);
                Session::forget('loginerr');
                Session::put('user', $userx);
                return true;
            } else {
                Session::put('loginerr', $array["MSG"] ?: "Something went wrong. Please try again");
                return false;
            }
        }
    }
}
