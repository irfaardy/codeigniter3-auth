<?php 
/**
 * Codeigniter 3 Auth
 * @author	Irfa Ardiasnyah
 * @link	https://github.com/irfaardy/codeigniter3-auth
 * @version	1.2.1
 */
class Auth{
	
	private $CI;

	function __construct(){
		$this->CI = & get_instance();
	}
	/**
     * Cek username dan password, jika password yang diinputkan sama dengan di database sama maka login sukses.
     *
     * @param string $username
     * @param string $password
     * @return boolean
     */
	public function verify($username,$password){
		$get = $this->CI->user->getBy(['username' => $username]);
		if(empty($get)){
			return false;
		}
		
		if(password_verify($password, $get->password)) {
			$user_datas = array(
			        'user_id'  => $get->id,
			        'logged_in' => TRUE,
			        'login_token' => sha1($get->id.time().mt_rand(1000,9999))
			);
			$this->CI->session->sess_regenerate();
			$this->CI->session->set_userdata($user_datas);

			return true;
		} else {
			return false;
		}
	}
	/**
     * Cek sudah login apa belum.
     *
     * @return boolean
     */
	public function check(){
		if($this->CI->session->logged_in) {
			return true;
		} 

		return false;
	}

	/**
     * Cek hak akses.
     *
     * @return mixed
     */
	public function hakAkses($hakAksesId){
		if($this->user()->level != $hakAksesId) {
			$this->CI->session->set_flashdata('warning','Anda tidak dapat mengakses halaman ini.');
			return redirect(array_key_exists('HTTP_REFERER',$_SERVER)?$_SERVER['HTTP_REFERER']:base_url('/'));
		} 
	}

	/**
     * Ambil data user sesuai dengan id yang login.
     *
     * @return mixed
     */
	public function user(){
		if($this->check()) {
			$get = $this->CI->user->getBy(['id' => $this->CI->session->user_id]);
			return $get;
		}

		return false;
	}

	/**
     * Keluar dari sesi.
     *
     * @return void
     */
	public function logout(){
		if(empty($this->CI->input->get('token'))){
			return redirect($_SERVER['HTTP_REFERER']);
		}

		if($this->CI->session->login_token === $this->CI->input->get('token')){
			$this->destroy();
		} else{
			return redirect($_SERVER['HTTP_REFERER']);
		}
	}

	private function destroy(){
		$this->CI->session->sess_regenerate(TRUE);
		$this->CI->session->sess_destroy();
		return redirect(base_url('login'));
	}
	/** 
	* Mencegah guest untuk mengakses halaman
	* @return void
	*/
	public function protect($hakAksesId = null){
		if(!$this->check()){
			$this->destroy();
		} else{
		   if(!empty($hakAksesId)){
			   	 if(is_array($hakAksesId)){
			   	 	if(!in_array($this->user()->level, $hakAksesId)) { 
						$this->CI->session->set_flashdata('warning','Anda tidak dapat mengakses halaman ini.');
						return /** @scrutinizer ignore-call */ redirect(array_key_exists('HTTP_REFERER',$_SERVER)?$_SERVER['HTTP_REFERER']:/** @scrutinizer ignore-call */base_url('/'));
					} 
			   	 }else{
					if($this->user()->level != $hakAksesId) {
						$this->CI->session->set_flashdata('warning','Anda tidak dapat mengakses halaman ini.');
						return /** @scrutinizer ignore-call */redirect(array_key_exists('HTTP_REFERER',$_SERVER)?$_SERVER['HTTP_REFERER']:/** @scrutinizer ignore-call */base_url('/'));
					} 
				}
			}
		}
	}
}