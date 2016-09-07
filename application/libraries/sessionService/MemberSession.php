<?php
require_once(__DIR__.'/BaseSession.php');
class MemberSession extends BaseSession
{
	public $prefix = '';

	/**
	 * 建置prefix  session_start  $_SESSION[$prefix]
	 */
	public  function __construct($prefix = SESSION_PREFIX.'.member')
	{
		$this->chkSession();

		$this->prefix = $prefix;
		//$this->init($this->prefix, array());

	}

	/**
	 * 設 _SESSION['memberData']
	 */
	public function setMemberData($value){
		return $this->set("memberData" ,$value);
	}

	/**
	 * 刪除 SESSION['memberData']
	 */
	public function destroyMemberData(){
		return $this->delete("memberData");
	}

	/**
	 * 取得 SESSION['memberData'][$key]
	 * @param  string $key
	 * @param  string $default 如果目標是空  返回預設值$default
	 */
	public function getMemberData($key = '', $default = ''){
		if(!empty($key)){
			$MemberData=$this->get("memberData");
			if($MemberData){
				return (!empty($MemberData[$key]))?$MemberData[$key]:$default;
			}else{
				return false;
			}
		}else{
			return $this->get("memberData");
		}
	}

	/**
	 * 取得使用者ID
	 */
	public function getMemberUserId(){
		$user_id='';
		if ($memberData=$this->get("memberData")){
			return (!empty($memberData['user_id']))?$memberData['user_id']:false;
		}
		return false;
	}

	/**
	 * 更新_SESSION[memberStatus]
	 */
	public function setMemberStatus($value){
		return $this->set("memberStatus", $value);
	}

	/**
	 * 刪除_SESSION[memberStatus]
	 */
	public function destroyMemberStatus(){
		return $this->delete("memberStatus");
	}

	/**
	 * 取得_SESSION[memberStatus][$key]
	 * @param  string $key
	 * @param  string $default
	 */
	public function getMemberStatus($key = '', $default = ''){
		if(!empty($key)){
			$MemberStatus=$this->get("memberStatus");
			if($MemberStatus){
				return (!empty($MemberStatus[$key]))?$MemberStatus[$key]:$default;
			}else{
				return false;
			}
		}else{
			return $this->get("memberStatus");
		}
	}

	/**
	 * 更新_SESSION[memberStatus][$key]
	 * @param  [type] $key
	 * @param  [type] $value
	 */
	public function updateMemberStatus($key , $value){
		if($this->has("memberStatus")&& isset($this->get("memberStatus")[$key])){
			$memberStatus=$this->get("memberStatus");
		 	$memberStatus[$key]=$value;
		 	$this->setMemberStatus($memberStatus);
		 	return true;
		}
		return false;
	}
}
