<?php
require_once(__DIR__.'/BaseSession.php');

class GuestSession extends BaseSession
{
	public $prefix = '';

	/**
	 * 建置prefix  session_start  $_SESSION[$prefix]
	 */
	public  function __construct($prefix = SESSION_PREFIX.'.guest')
	{
		$this->chkSession();

		$this->prefix = $prefix;
	}
}
