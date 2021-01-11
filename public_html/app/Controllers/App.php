<?php namespace App\Controllers;

use App\Models\AppModel;

class App extends BaseController
{
	private $cache;
	function __construct()
	{
		$this->cache = \Config\Services::cache();
		
	}
	public function index(){
		
		$data = [];
		helper(['form']);
		$model = new AppModel();

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'appName' => 'required|min_length[3]|max_length[20]',
				];
			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$this->cache->save('App Created',session()->get('id'),3600);				
				$newData = [
					'appName' => $this->request->getPost('appName'),
					'clientid' => uniqid(session()->get('firstname')),
					'clientkey' => uniqid(session()->get('id')),
					'userId' => intval(session()->get('id')),
					];
					
				$model->save($newData);

				session()->setFlashdata('success', 'Successfuly Updated');
				// return redirect()->to('/');

			}
		}
		
		$data['apps'] = $model->where('userId', session()->get('id'))->findAll();
		
		$this->cache->save('Apps Page Loaded',session()->get('id'),3600);
		echo view('templates/header', $data);
		echo view('apps');
		echo view('templates/footer');
	}
}