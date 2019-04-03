<?php

namespace application\controller;


use \application\service\Service;
use \application\service\FrontController;
use \application\model\AdminModel;

class BaseController extends FrontController {
    public $date_start, $date_finish;
    public function __construct()
    {
        parent::__construct();



        if ($this->request->getGet('date_start')) {
            $this->date_start = $this->request->getGet('date_start');
        } else {
            if ($this->request->getGet('month')=='True'){
                $d = new \DateTime('first day of this month');
                $start_this_month = $d->format('d.m.Y');

                $this->date_start =$start_this_month;
            } else {
                $d = new \DateTime('first day of january this year');
                $start_this_year = $d->format('d.m.Y');

                $this->date_start = $start_this_year;
            }
        }
        if ($this->request->getGet('date_finish')) {
            $this->date_finish = $this->request->getGet('date_finish');
        } else {
            $d = new \DateTime('today');
            $today = $d->format('d.m.Y');

            $this->date_finish =$today;

        }

    }
    protected function before() {
		$this->view->addGlobal('title', $this->config->get("title"));
		$this->view->addGlobal('user', $this->session->get("user"));


		return true;
	}

	protected function checkRight($user,$id_department,$type='dep'){
        $admin_model = new AdminModel();

        if ($type=='dep') {
            $rigth = $admin_model->checkRightOnDepartment($user['id'], $id_department);

            if (($rigth == false) or ($rigth['right_u'] == 0)) {

                return false;
            }
        } else {

        }
        return $rigth;

    }


}