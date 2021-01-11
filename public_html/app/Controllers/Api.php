<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\IncomingRequest;
use Config\Services;
use App\Models\AppModel;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Api extends ResourceController
{
    protected $format = 'json';
   

    public function create()
	{
        $clientId = $this->request->getPost('clientId');
        $clientKey = $this->request->getPost('clientKey');
        $model = new AppModel();
        $response=[];
        if(!$clientId || !$clientKey)
        {
            $response['status'] = 'Error';
            $response['message'] = 'Please make sure you provide client id and client key';
            return $this->respond($response);
        }
        else
        {
            $app = $model->where('clientid', $clientId)->where('clientkey',$clientKey)->first();
            if($app)
            {
                $key = Services::getSecretKey();
                $response['status'] = 'Success';
                $jwt = JWT::encode($app, $key);
                $response['data']['token']=$jwt;
                return $this->respond($response);
            }
            else
            {
                $response['status'] = 'Error';
                $response['message'] = 'Invalid Key or ID';
                return $this->respond($response);
            }
        }
    }

    public function auth()
    {
     
        $request = service('request');
        $token = explode(" ",$request->getHeaderLine('AUTHORIZATION'))[1];
        if($token)
        {
            $key = Services::getSecretKey();
            $decoded = JWT::decode($token, $key, array('HS256'));
            
            if($decoded)
            {
            
                $model = new UserModel();
                $user = $model->where('email', $this->request->getPost('email'))
                            ->first();

                if(!$user)
                {
                    $response['status'] = 'Error';
                    $response['message'] = 'Invalid Email';
                    return $this->respond($response);
                }
                $verify = password_verify($this->request->getPost('password'), $user['password']);
                if(!$verify)
                {
                    $response['status'] = 'Error';
                    $response['message'] = 'Invalid Password';
                    return $this->respond($response);
                }
                else
                {
                    $response['status'] = 'Success';
                    $response['message'] = 'Login Success';
                    unset($user['password']);
                    $response['data'] = $user;
                    return $this->respond($response);
                } 
            }
            else
            {
                $response['status'] = 'Error';
                $response['message'] = 'Please provide valid token';
                return $this->respond($response);
            }
        }
        else
        {
            $response['status'] = 'Error';
            $response['message'] = 'Please provide valid token';
            return $this->respond($response);
        }
    }
    // Register a new User
    public function register()
    {
     
        $request = service('request');
        $token = explode(" ",$request->getHeaderLine('AUTHORIZATION'))[1];
        if($token)
        {
            $key = Services::getSecretKey();
            $decoded = JWT::decode($token, $key, array('HS256'));
            
            if($decoded)
            {
                if(!$this->request->getPost('firstname') || !$this->request->getPost('lastname') || !$this->request->getPost('email') || !$this->request->getPost('password'))
                {
                    $response['status'] = 'Error';
                    $response['message'] = 'Please provide all fields';
                    return $this->respond($response);
                
                }else{
                    $model = new UserModel();
                    $user = $model->where('email', $this->request->getPost('email'))
                            ->first();
                    if($user)
                    {
                        $response['status'] = 'Error';
                        $response['message'] = 'Email already registered';
                        return $this->respond($response);
                    }
                    else
                    {
                        $newData = [
                        'firstname' => $this->request->getPost('firstname'),
                        'lastname' => $this->request->getPost('lastname'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        ];
             
                        $model->save($newData);
                        $user = $model->where('email', $this->request->getPost('email'))
                            ->first();
                        $response['status'] = 'Success';
                        $response['message'] = 'User Created';
                        unset($user['password']);
                        $response['data'] = $user;
                        return $this->respond($response);
                    }
                }
            }
            else
            {
                $response['status'] = 'Error';
                $response['message'] = 'Please provide valid token';
                return $this->respond($response);
            }
        }
        else
        {
            $response['status'] = 'Error';
            $response['message'] = 'Please provide valid token';
            return $this->respond($response);
        }
    }

}