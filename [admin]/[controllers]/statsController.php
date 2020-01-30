<?php
class StatsController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];

		if($section == 'list')
			$action='list1';

	
		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::DOCTOR) )
        {

        }
        else
            $MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('stats/indexView.php', $model);
	}





    function list1()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::DOCTOR) )
        {
            //vd($_REQUEST);
            $params = $_REQUEST;

            $params['dateFrom'] = $params['dateFrom'] ? $params['dateFrom'] : date('Y-m-d', strtotime(date('Y-m-d'))-3600*24*14);
            $params['dateTo'] = $params['dateTo'] ? $params['dateTo'] : date('Y-m-d');
//            vd($params);

            if($params['serviceId1'])
            {
                $params['serviceIds'] = join(', ', array_unique(Service::getAllSubIds($params['serviceId1'])));
            }
//            vd($params);


            $MODEL['totalCount'] = ScheduleEntry::getCount($params);

            $MODEL['list'] = ScheduleEntry::getList($params);
            if($MODEL['list'])
                foreach ($MODEL['list'] as $item)
                {
                    $item->initDoctor();
                    $item->initService();
                    $item->initClient();
                }

            $MODEL['params'] = $params;


            $MODEL['doctors'] = Admin::getActiveDoctors();
            $MODEL['services'] = Service::getList(['active'=>1, ]);
        }
        else
            $MODEL['error'] = Error::NO_ACCESS_ERROR;

        Core::renderView('stats/list.php', $MODEL);
    }




}




?>