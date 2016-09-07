<?php

class BaseSession
{
	public $prefix = '';
	public $session;

	/**
	 * 建置prefix  session_start  $_SESSION['test']
	 */
	public  function __construct($prefix = 'test')
	{
		$this->chkSession();
		$this->prefix = $prefix;
		//$this->init($this->prefix, array());
	}

    /**
     * Flush 一次性資料set
     * @param  string $name
     * @param  mix $value
     * @return mix
     */
    public function flush($name , $value)
    {
        $this->realSet($this->prefix.'.flush.'.$name, $value);
    }

    /**
     * Falush 取完後將刪除該值，一次性使用
     * @param  string $name
     * @return mix
     */
    public function getFlush($name)
    {
        $name = $this->prefix.'.flush.'.$name;
        $res = $this->realGet($name);
        $this->realDelete($name);
        return $res;
    }

	/**
	 * 設定SESSION
	 * @param string $key  [v1.v2.v3]
	 * @param string $data value
	 * @return  boolen [成功判斷]
	 */
	public function set($name, $value)
    {
    	$name = (!empty($name))?$this->prefix.'.'.$name:$this->prefix;
    	$this->realSet($name,$value);
    }

	/**
	 * 取得SESSION
	 * @param  string  $key  KEY值
	 * @return array   $_SESSION
	 */
	public function get($name= '')
    {
        $name = (!empty($name))?$this->prefix.'.'.$name:$this->prefix;
        return $this->realGet($name);
    }

	/**
	 * 清除資料
	 * @param  string $key KEY值
	 */
	public  function delete($name = '')
	{
		$name = (!empty($name))?$this->prefix.'.'.$name:$this->prefix;
		return $this->realDelete($name);
	}

    /**
     * 有無這個值
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        $parsed = explode('.', $name);
        $result = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return false;
            }
        }
        return true;
    }

	/**
	 * 有無這個值
	 * @param  [type]  $key
	 * @return boolean
	 */
	// public function has($name = '')
	// {
	// 	$name = (!empty($name))?$this->prefix.'.'.$name:$this->prefix;
 //        if(!empty($this->realGet($name))){
 //        	return true;
 //        }else{
 //        	return false;
 //        }
	// }

	/**
	 * drop
	 * @return [type] [description]
	 */
	public function destory()
    {
			return $this->realDelete($this->prefix);
	}

	/**
	 * 取得本層所有值[$prefix]
	 */
	public function getAll()
    {
		return $this->realGet($this->prefix);
	}

	/**
	 * 檢查SESSION
	 */
	public  function chkSession()
	{
		if(isset($_SESSION)){
			return true;
		}else{
			try{
				session_start();
				return true;
			}catch(Exception $e){
				return false;
			}
		}
	}

	/**
	 * Set 實際執行設置
	 * @param  string $name
	 * @param  mix $value
	 * @return mix
	 */
	public function realSet($name, $value)
    {
		$parsed = explode('.', $name);
        $session = &$_SESSION;
        while (count($parsed) > 1) {
            $next = array_shift($parsed);
            if ( ! isset($session[$next]) || ! is_array($session[$next])) {
                $session[$next] = [];
            }
            $session = &$session[$next];
        }
        $session[array_shift($parsed)] = $value;
	}

    /**
     * Get 實際取得設置
     * @param  string $name
     * @return mix
     */
	public function realGet($name)
    {
		$parsed = explode('.', $name);
		$result = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return false;
            }
        }
        return $result;
	}

     /**
     * Delete 實際取得設置
     * @param  string $name
     * @return mix
     */
	public function realDelete($name = '')
	{
		$parsed = explode('.', $name);
        $session = &$_SESSION;

        while (count($parsed) > 1) {
            $next = array_shift($parsed);
            if ( ! isset($session[$next]) || ! is_array($session[$next])) {
                return false;
            }
            $session = &$session[$next];
        }
        unset($session[array_shift($parsed)]);
        return true;
	}
}
