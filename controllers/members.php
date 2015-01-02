<?php

class Members extends Controller {

	public function __construct(){
		parent::__construct();
	}	

	public function index(){

		if(isset($_POST['submit'])){

			$username        = $_POST['username'];
			$email           = $_POST['email'];
			$password        = $_POST['password'];
			$passwordConfirm = $_POST['passwordConfirm'];

			if(strlen($username) < 3){
				$error[] = 'Username is too short';
			} else {
				$check = $this->_model->get_username($username);
				if(strtolower($check[0]->username) == strtolower($username)){
					$error[] = 'Username already taken';
				}
			}


			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			     $error[] = 'Please enter a valid email address';
			} else {
				$check = $this->_model->get_email($email);
				if(strtolower($check[0]->email) == strtolower($email)){
					$error[] = 'Email already taken';
				}
			}

			if(strlen($password) < 5){
				$error[] = 'Password is too short';
			} elseif($password != $passwordConfirm){
				$error[] = 'Password do not match';
			}

			if(!isset($error)){

				$activasion = md5(uniqid(rand(),true));	

				$hash = Password::password_hash($password, PASSWORD_BCRYPT);

				//insert
				$postdata = array(
					'username' => $username,
					'email' => $email,
					'password' => $hash,
					'active' => $activasion
				);

				$id = $this->_model->insert_member($postdata);

				$to = $email;
				$from  = 'dave@simplemvcframework.com';
				$subject = 'Registration confirm';
				$body = "Thank you for registering to activate your account please click on this link. ".DIR."members/activate/$id/$activasion";

				Mailer::sendmail($to,$from,$subject,$body);

				$data['success'] = true;
			}
		}

		$data['title'] = 'Members';

		$this->_view->rendertemplate('header',$data);
		$this->_view->render('members/home',$data,$error);
		$this->_view->rendertemplate('footer',$data);	
	}

	public function activate($memberID,$activasion){

		if(($memberID > 0) && (strlen($activasion) == 32)){

			$user = $this->_model->get_memberID($memberID,$activasion);

			if($user[0]->memberID == 0){
				$error[] = 'No such account';
			} elseif($user[0]->active == 'Yes'){
				$error[] = 'Account has already been activated';
			} else {

				$postdata = array('active' => 'Yes');
				$where = array('memberID' => $memberID);
				$this->_model->update_member($postdata,$where);

				$data['success'] = 'Your account is now active you may now <a href='.DIR.'members/login>Login</a>';
			}

		} else {
			$error[] = 'Invalid link provided';
		}

		$data['title'] = 'Activate Account';

		$this->_view->rendertemplate('header',$data);
		$this->_view->render('members/activate',$data,$error);
		$this->_view->rendertemplate('footer',$data);	
	}

	public function login(){

		if(Session::get('loggedin') == true){
			Url::redirect('members/memberspage');
		}

		if(isset($_POST['submit'])){

			$data = $this->_model->get_member_hash($_POST['username']);

			if(Password::password_verify($_POST['password'], $data[0]->password)){

				Session::set('memberID',$data[0]->memberID);
				Session::set('username',$data[0]->username);
				Session::set('loggedin',true);

				Url::redirect('members/memberspage');
			} else {
				$error[] = 'Wrong username or password or account has not been activated yet';
			}

		}

		$data['titie'] = 'Login';

		$this->_view->rendertemplate('header',$data);
		$this->_view->render('members/login',$data,$error);
		$this->_view->rendertemplate('footer',$data);	
	}

	public function memberspage(){

		if(Session::get('loggedin') == false){
			Url::redirect('members/login');
		}

		$data['title'] = 'Members Page';

		$this->_view->rendertemplate('header',$data);
		$this->_view->render('members/memberspage',$data);
		$this->_view->rendertemplate('footer',$data);	
	}

	public function logout(){
		Session::destroy();
		Url::redirect('members');
	}

}