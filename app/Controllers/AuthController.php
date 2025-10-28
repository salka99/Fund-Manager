<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
	protected $session;

	public function __construct()
	{
		$this->session = session();
	}

	public function login()
	{
		
		if ($this->request->getMethod(true) === 'POST') {
			
			$email = trim($this->request->getPost('email'));
			$password = (string)$this->request->getPost('password');

			$users = new UserModel();
			$user = $users->findByEmail($email);
			if (!$user) {
				return redirect()->to(site_url('login'))->with('error', 'Email not registered');
			}

			if (!password_verify($password, $user['password_hash'])) {
				return redirect()->to(site_url('login'))->with('error', 'Incorrect password');
			}

			$this->session->set('user', [
				'id' => $user['id'],
				'name' => $user['name'],
				'email' => $user['email'],
				'role' => $user['role'],
			]);
			return redirect()->to(site_url('dashboard'));
		}

		return view('auth/login');
	}

	public function register()
	{
		if ($this->request->getMethod(true) === 'POST') {
			$name = trim($this->request->getPost('name'));
			$email = trim($this->request->getPost('email'));
			$password = (string)$this->request->getPost('password');
			$role = $this->request->getPost('role') ?: 'staff';

			$users = new UserModel();
			if ($users->findByEmail($email)) {
				return redirect()->back()->with('error', 'Email already registered');
			}

			$users->insert([
				'name' => $name,
				'email' => $email,
				'password_hash' => password_hash($password, PASSWORD_DEFAULT),
				'role' => $role,
			]);

			return redirect()->to(site_url('login'))->with('success', 'Registration successful. Please login.');
		}

		return view('auth/register');
	}

	public function logout()
	{
		$this->session->destroy();
		return redirect()->to(site_url('login'));
	}
} 