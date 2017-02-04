<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Notes for GameAP
 *
 * @package		Notes
 * @author		Nikita Kuznetsov (ET-NiK)
 * @copyright	Copyright (c) 2013-2014, Nikita Kuznetsov (http://hldm.org)
 * @license		http://www.gameap.ru/license.html
 * @link		http://www.gameap.ru
 * @filesource
*/

use \Myth\Controllers\BaseController;

class Notes extends BaseController {
	
	var $tpl_data 	= array();
	
	/* Автоматически загружаемые модели */
	var $autoload = array('model' => array('users'), );
	
	// -----------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        
        if (!$this->users->check_user()) {
			return;
		}

        /* Load DataBase */
		$this->load->database();
		
		/* Загрузка языковых файлов */
		$this->lang->load('server_files');
        $this->lang->load('server_command');
		
		/* Templates */
		$this->tpl_data['title'] = 'GameAP :: Notes';
		$this->tpl_data['heading'] = 'Notes';
		$this->tpl_data['menu'] = $this->parser->parse('menu.html', $this->tpl_data, TRUE);
		$this->tpl_data['profile'] = $this->parser->parse('profile.html', $this->users->tpl_userdata(), true);
    }
    
    // -----------------------------------------------------------------
    
    public function load()
    {
		/* Авторизован ли юзер */
		if (!$this->users->auth_data) {
			exit;
		}
		
		if (isset($this->users->auth_data['modules_data']['notes'])) {
			$notes = $this->users->auth_data['modules_data']['notes'];
		} else {
			$notes = '';
		}
		
		$this->output->set_output($notes);
	}
    
    // -----------------------------------------------------------------
    
    public function save()
    {
		/* Авторизован ли юзер */
		if (!$this->users->auth_data) {
			$this->output->append();
			exit;
		}
		
		$note = htmlspecialchars($this->input->post('note', true));
		$this->users->update_modules_data($this->users->user_id, $note, 'notes');
		$this->output->set_output(1);
	}
    
    // -----------------------------------------------------------------
    
    public function index()
    {
		/* Авторизован ли юзер */
        if (!$this->users->auth_data) {
			redirect('auth/in');
		}
		
		$local_tpl = array();
		$this->tpl_data['content'] = $this->parser->parse('notes.html', $local_tpl, TRUE);
        $this->parser->parse('main.html', $this->tpl_data);
	}

}
