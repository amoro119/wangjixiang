<?php
@set_time_limit(1000);
/**
 * FTP ������
 * ��֧�� SFTP �� SSL FTP Э��, ��֧�ֱ�׼ FTP Э��.
 * ��Ҫ����һ����������
 * ʾ��:
 * $config['hostname'] = 'ftp.example.com';
 * $config['username'] = 'your-username';
 * $config['password'] = 'your-password';
 * $config['debug'] = TRUE;
 *
 * Exp.Tianya<tianya@dedecms.com>
 */
class FTP {

	var $hostname	= '';
	var $username	= '';
	var $password	= '';
	var $port		= 21;
	var $passive	= TRUE;
	var $debug		= FALSE;
	var $conn_id	= FALSE;


	/**
	 * �������� - ���ò���
	 *
	 * ���캯���򴫵�һ����������
	 */
	function FTP($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * ��ʼ������
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}

		// ׼��������
		$this->hostname = preg_replace('|.+?://|', '', $this->hostname);
	}

	// --------------------------------------------------------------------

	/**
	 * FTP ����
	 *
	 * @access	public
	 * @param	array	 ����ֵ
	 * @return	bool
	 */
	function connect($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}

		if (FALSE === ($this->conn_id = @ftp_connect($this->hostname, $this->port)))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�����');
			}
			return FALSE;
		}

		if ( ! $this->_login())
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷���¼');
			}
			return FALSE;
		}

		// �����Ҫ�����ô���ģʽ
		if ($this->passive == TRUE)
		{
			ftp_pasv($this->conn_id, TRUE);
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * FTP ��¼
	 *
	 * @access	private
	 * @return	bool
	 */
	function _login()
	{
		return @ftp_login($this->conn_id, $this->username, $this->password);
	}

	// --------------------------------------------------------------------

	/**
	 * ��֤����ID
	 *
	 * @access	private
	 * @return	bool
	 */
	function _is_conn()
	{
		if ( ! is_resource($this->conn_id))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�����');
			}
			return FALSE;
		}
		return TRUE;
	}

	// --------------------------------------------------------------------


	/**
	 * ����Ŀ¼
	 * �ڶ�������������������ʱ�رգ��Ա����
	 * �˹��ܿ����ڼ���Ƿ����һ���ļ���
	 * �׳�һ������û��ʲô��FTP�൱��is_dir()
	 * ��ˣ�������ͼ�ı�ĳһ�ض�Ŀ¼��
	 *
	 * @access	public
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function changedir($path = '', $supress_debug = FALSE)
	{
		if ($path == '' OR ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_chdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE AND $supress_debug == FALSE)
			{
				$this->_error('�޷�����Ŀ¼');
			}
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * ����һ��Ŀ¼
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function mkdir($path = '', $permissions = NULL)
	{
		if ($path == '' OR ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_mkdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷������ļ���');
			}
			return FALSE;
		}

		// �����Ҫ����Ȩ��
		if ( ! is_null($permissions))
		{
			$this->chmod($path, (int)$permissions);
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * �����Ŀ¼
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function rmkdir($path = '', $pathsymbol = '/')
	{
		$pathArray = explode($pathsymbol,$path);
		$pathstr = $pathsymbol;
		foreach($pathArray as $val)
		{
			if(!empty($val))
			{
				//�����ļ���·��
				$pathstr = $pathstr.$val.$pathsymbol;
				if (! $this->_is_conn())
				{
					return FALSE;
				}
				$result = @ftp_chdir($this->conn_id, $pathstr);
				if($result === FALSE)
				{
					//������������Ŀ¼�򴴽�
					if(!$this->mkdir($pathstr))
					{
						return FALSE;
					}
				}
			}
		}
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * �ϴ�һ���ļ���������
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
	{
		if (!$this->_is_conn())
		{
			return FALSE;
		}

		if (!file_exists($locpath))
		{
			$this->_error('������Դ�ļ�');
			return FALSE;
		}

		// δָ��������ģʽ
		if ($mode == 'auto')
		{
			// ��ȡ�ļ���չ�����Ա㱾���ϴ�����
			$ext = $this->_getext($locpath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷��ϴ�');
			}
			return FALSE;
		}

		// �����Ҫ�����ļ�Ȩ��
		if ( ! is_null($permissions))
		{
			$this->chmod($rempath, (int)$permissions);
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * ������(�����ƶ�)һ���ļ�
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function rename($old_file, $new_file, $move = FALSE)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_rename($this->conn_id, $old_file, $new_file);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$msg = ($move == FALSE) ? '�޷�������' : '�޷��ƶ�';

				$this->_error($msg);
			}
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * �ƶ�һ���ļ�
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function move($old_file, $new_file)
	{
		return $this->rename($old_file, $new_file, TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * �����������ƶ�һ���ļ�
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function delete_file($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_delete($this->conn_id, $filepath);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�ɾ��');
			}
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * ɾ��һ���ļ��У��ݹ�ɾ��һ�У��������ļ��У�������
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function delete_dir($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// �����Ҫ��β������β��"/"
		$filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

		$list = $this->list_files($filepath);

		if ($list !== FALSE AND count($list) > 0)
		{
			foreach ($list as $item)
			{
				// ������ǲ���ɾ������Ŀ,���������һ���ļ���
				// ������ delete_dir()
				if ( ! @ftp_delete($this->conn_id, $item))
				{
					$this->delete_dir($item);
				}
			}
		}

		$result = @ftp_rmdir($this->conn_id, $filepath);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�ɾ��');
			}
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * �����ļ�Ȩ��
	 *
	 * @access	public
	 * @param	string 	�ļ���ַ
	 * @param	string	Ȩ��
	 * @return	bool
	 */
	function chmod($path, $perm)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// ��PHP5��������
		if ( ! function_exists('ftp_chmod'))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�����Ȩ��');
			}
			return FALSE;
		}

		$result = @ftp_chmod($this->conn_id, $perm, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('�޷�����Ȩ��');
			}
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * ��ָ����Ŀ¼��FTP�ļ��б�
	 *
	 * @access	public
	 * @return	array
	 */
	function list_files($path = '.')
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		return ftp_nlist($this->conn_id, $path);
	}
	
	// --------------------------------------------------------------------

	/**
	 * ����ָ��Ŀ¼���ļ�����ϸ�б�
	 *
	 * @access	public
	 * @return	array
	 */
	function list_rawfiles($path = '.', $type='dir')
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}
		
		$ftp_rawlist = ftp_rawlist($this->conn_id, $path, TRUE);
	  foreach ($ftp_rawlist as $v) {
	    $info = array();
	    $vinfo = preg_split("/[\s]+/", $v, 9);
	    if ($vinfo[0] !== "total") {
	      $info['chmod'] = $vinfo[0];
	      $info['num'] = $vinfo[1];
	      $info['owner'] = $vinfo[2];
	      $info['group'] = $vinfo[3];
	      $info['size'] = $vinfo[4];
	      $info['month'] = $vinfo[5];
	      $info['day'] = $vinfo[6];
	      $info['time'] = $vinfo[7];
	      $info['name'] = $vinfo[8];
	      $rawlist[$info['name']] = $info;
	    }
	  }
	  
	  $dir = array();
	  $file = array();
	  foreach ($rawlist as $k => $v) {
	    if ($v['chmod']{0} == "d") {
	      $dir[$k] = $v;
	    } elseif ($v['chmod']{0} == "-") {
	      $file[$k] = $v;
	    }
	  }

	  return ($type == 'dir')? $dir : $file;
	}

	// ------------------------------------------------------------------------

	/**
	 * ����һ������Ŀ¼�µ���������(������Ŀ¼�������ļ�)����ͨ��FTPΪ���Ŀ¼����һ�ݾ���
	 * Դ·���µ��κνṹ���ᱻ�������������ϡ���������Դ·����Ŀ��·��
	 *
	 * @access	public
	 * @param	string	����β��"/"��Դ·��
	 * @param	string	Ŀ��·�� - ����β��"/"���ļ���
	 * @return	bool
	 */
	function mirror($locpath, $rempath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		// �򿪱����ļ�·��
		if ($fp = @opendir($locpath))
		{
			// ���Դ�Զ���ļ���·��.
			if ( ! $this->changedir($rempath, TRUE))
			{
				// ������ܴ��򴴽�
				if ( ! $this->rmkdir($rempath) OR ! $this->changedir($rempath))
				{
					return FALSE;
				}
			}

			// �ݹ��ȡ����Ŀ¼
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.')
				{
					$this->mirror($locpath.$file."/", $rempath.$file."/");
				}
				elseif (substr($file, 0, 1) != ".")
				{
					// ��ȡ�ļ���չ�����Ա㱾���ϴ�����
					$ext = $this->_getext($file);
					$mode = $this->_settype($ext);

					$this->upload($locpath.$file, $rempath.$file, $mode);
				}
			}
			return TRUE;
		}

		return FALSE;
	}


	// --------------------------------------------------------------------

	/**
	 * ȡ���ļ���չ��
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _getext($filename)
	{
		if (FALSE === strpos($filename, '.'))
		{
			return 'txt';
		}

		$x = explode('.', $filename);
		return end($x);
	}


	// --------------------------------------------------------------------

	/**
	 * �����ϴ�����
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _settype($ext)
	{
		$text_types = array(
							'txt',
							'text',
							'php',
							'phps',
							'php4',
							'js',
							'css',
							'htm',
							'html',
							'phtml',
							'shtml',
							'log',
							'xml'
							);


		return (in_array($ext, $text_types)) ? 'ascii' : 'binary';
	}

	// ------------------------------------------------------------------------

	/**
	 * �ر�����
	 *
	 * @access	public
	 * @param	string	Դ·��
	 * @param	string	Ŀ�ĵ�·��
	 * @return	bool
	 */
	function close()
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		@ftp_close($this->conn_id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * ��ʾ������Ϣ
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
	function _error($msg)
	{
		$errorTrackFile = dirname(__FILE__).'/../data/ftp_error_trace.inc';
		$emsg = '';
		$emsg .= "<div><h3>DedeCMS Error Warning!</h3>\r\n";
		$emsg .= "<div><a href='http://bbs.dedecms.com' target='_blank' style='color:red'>Technical Support: http://bbs.dedecms.com</a></div>";
		$emsg .= "<div style='line-helght:160%;font-size:14px;color:green'>\r\n";
		$emsg .= "<div style='color:blue'><br />Error page: <font color='red'>".$this->GetCurUrl()."</font></div>\r\n";
		$emsg .= "<div>Error infos: {$msg}</div>\r\n";
		$emsg .= "<br /></div></div>\r\n";
		
		echo $emsg;
		
		$savemsg = 'Page: '.$this->GetCurUrl()."\r\nError: ".$msg;
		//���������־
		$fp = @fopen($errorTrackFile, 'a');
		@fwrite($fp, '<'.'?php  exit();'."\r\n/*\r\n{$savemsg}\r\n*/\r\n?".">\r\n");
		@fclose($fp);
	}

	/**
	 * ��õ�ǰ�Ľű���ַ
	 *
	 * @access	public
	 * @return	string
	 */
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$scriptName = $_SERVER["REQUEST_URI"];
			$nowurl = $scriptName;
		}
		else
		{
			$scriptName = $_SERVER["PHP_SELF"];
			if(empty($_SERVER["QUERY_STRING"])) {
				$nowurl = $scriptName;
			}
			else {
				$nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
			}
		}
		return $nowurl;
	}

}
?>