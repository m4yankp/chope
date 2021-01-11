<?php namespace App\Controllers;

use App\Models\UserModel;


class Users extends BaseController
{
	private $cache;
	protected $request;
	function __construct()
	{
		$this->cache = \Config\Services::cache();
	}
	public function index()
	{
		$data = [];
		helper(['form']);


		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			
			$user = $this->login();
			if($user)
			{
				$this->setUserSession($user);
				return redirect()->to('./dashboard');
			}
			else
			{
				$data['validation'] = $this->validator;
			}
		}
		if(session()->get('id'))
		{
			return redirect()->to('./dashboard');
		}
		echo view('templates/header', $data);
		echo view('login');
		echo view('templates/footer');
	}

	public function login(){
		$rules = [
				'email' => 'required|min_length[6]|max_length[50]|valid_email',
				'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
			];

		$errors = [
			'password' => [
				'validateUser' => 'Email or Password don\'t match'
			]
		];

		if (!$this->validate($rules, $errors)) {
			return false;
		}else{
			$model = new UserModel();

			$user = $model->where('email', $this->request->getVar('email'))
										->first();
			return $user;

		}
	}

	private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'firstname' => $user['firstname'],
			'lastname' => $user['lastname'],
			'email' => $user['email'],
			'isLoggedIn' => true,
		];
		$this->cache->save('Login done by user'.$data['id'],$data['id'],3600);
		session()->set($data);
		return true;
	}

	public function register(){
		$data = [];
		helper(['form']);

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
				'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
				'password' => 'required|min_length[8]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];
			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new UserModel();

				$newData = [
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
				];
				$model->save($newData);
				$this->cache->save('registeration done by '.$newData['firstName'],$newData['firstName'],3600);
				$session = session();
				$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('./');

			}
		}


		echo view('templates/header', $data);
		echo view('register');
		echo view('templates/footer');
	}

	public function profile(){
		
		$data = [];
		helper(['form']);
		$model = new UserModel();

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
				];

			if($this->request->getPost('password') != ''){
				$rules['password'] = 'required|min_length[8]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
			}


			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$this->cache->save('Profile Updated',session()->get('id'),3600);
				$newData = [
					'id' => session()->get('id'),
					'firstname' => $this->request->getPost('firstname'),
					'lastname' => $this->request->getPost('lastname'),
					];
					if($this->request->getPost('password') != ''){
						$newData['password'] = $this->request->getPost('password');
					}
				$model->save($newData);

				session()->setFlashdata('success', 'Successfuly Updated');
				return redirect()->to('/profile');

			}
		}
		
		$data['user'] = $model->where('id', session()->get('id'))->first();
		$this->cache->save('Profile Opened by',$data['user'],3600);
		echo view('templates/header', $data);
		echo view('profile');
		echo view('templates/footer');
	}

	public function logout(){
		$this->cache->save('Logged Out By User'.session()->get("id"),session()->get("id"),3600);
		session()->destroy();
		return redirect()->to('/');
	}

	//--------------------------------------------------------------------

}