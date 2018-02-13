<?php
class AdvController extends MainController{
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		$action = $p ? $p : $section;
		
		#	БРЕНДЫ
		if($section == 'brands' )
		{
			$action = 'brandsIndex';	# 	по умлочанию
		
			if($p == 'list')
				$action = 'brandsList';
			if($p == 'edit')
				$action = 'brandsEdit';
			if($p == 'editSubmit')
				$action = 'brandsEditSubmit';
			if($p == 'listSubmit')
				$action = 'brandsListSubmit';
			if($p == 'delete' )
				$action = 'brandsDelete';
			if($p == 'switchBrandStatus' )
				$action = 'switchBrandStatus';
		}
		
		#	АРТИКУЛЬНЫЕ НОМЕРА
		if($section == 'article_numbers' )
		{
			$action = 'articleNumbersIndex';	# 	по умлочанию
		
			if($p == 'list')
				$action = 'articleNumbersList';
			if($p == 'edit')
				$action = 'articleNumbersEdit';
			if($p == 'editSubmit')
				$action = 'articleNumbersEditSubmit';
			if($p == 'listSubmit')
				$action = 'articleNumbersListSubmit';
			if($p == 'delete' )
				$action = 'articleNumbersDelete';
			if($p == 'articleNumbersSwitchStatus' )
				$action = 'articleNumbersSwitchStatus';
		}
		
		#	КАТЕГОРИЯ + БРЕНД
		if($section == 'cat_brand_combine' )
		{
			$action = 'CatBrandIndex';	# 	по умлочанию
		
			if($p == 'brands_list_ajax')
				$action = 'CatBrandBrandsListAjax';
			if($p == 'check_brand')
				$action = 'CatBrandCheckBrand';
		}
		
		#	БРЕНД + АРТ.НОМЕР
		if($section == 'brand_artnum_combine' )
		{
			$action = 'BrandArtnumIndex';	# 	по умлочанию
		
			if($p == 'artnums_list_ajax')
				$action = 'BrandArtnumArtnumsListAjax';
			if($p == 'check_artnum')
				$action = 'BrandArtnumCheckArtnum';
		}
		
		
		#	КАТЕГОРИЯ + БРЕНД + АРТ.НОМЕР
		if($section == 'cat_brand_artnum_combine' )
		{
			$action = 'CatBrandArtnumIndex';	# 	по умлочанию
		
			if($p == 'brands_list_ajax')
				$action = 'CatBrandArtnumBrandsListAjax';
			if($p == 'artnums_list_ajax')
				$action = 'CatBrandArtnumArtnumsListAjax';
			if($p == 'check_artnum')
				$action = 'CatBrandArtnumCheckArtnum';
		}
		
		
		/*#	КАТЕГОРИИ
		if($section == 'cats' )
		{
			$action = 'catsIndex';	# 	по умлочанию
		
			if($p == 'list')
				$action = 'catsList';
			if($p == 'catsSwitchStatus')
				$action = 'catsSwitchStatus';
			if($p == 'edit')
				$action = 'catEdit';
			if($p == 'catEditSubmit')
				$action = 'catEditSubmit';
			if($p == 'catsListSubmit')
				$action = 'catsListSubmit';
		}*/
		
		/*#	СВОЙСТВА
		if($section == 'props')
		{
			if($p == 'list')
				$action = 'propsListAjax';
			if($p == 'edit')
				$action = 'propsEdit';
			if($p == 'editSubmit')
				$action = 'propsEditSubmit';
			if($p == 'delete')
				$action = 'propsDelete';
			if($p == 'optionValueSubmit')
				$action = 'propsOptionValueSubmit';
			if($p == 'optionDelete')
				$action = 'propsOptionDelete';
			if($p == 'switchStatus')
				$action = 'propsSwitchStatus';
			if($p == 'switchTableColumnStatus')
				$action = 'propsSwitchTableColumnStatus';
			if($p == 'сolumnDelete')
				$action = 'propsTableColumnDelete';
		}*/
		
		/*#	КЛАССЫ
		if($section == 'classes' )
		{
			if($p == 'list' )
				$action = 'classesListAjax';
			if($p == 'edit')
				$action = 'classesEdit';
			if($p == 'editSubmit')
				$action = 'classesEditSubmit';
			if($p == 'delete')
				$action = 'classesDelete';
			if($p == 'classesSwitchStatus')
				$action = 'classesSwitchStatus';
			if($p == 'classesListSaveChanges')
				$action = 'classesListSaveChanges';
		}*/
		
		#	ОБЪЯВЛЕНИЯ
		if($section == 'items' )
		{
			if($p == 'listAjax' )
				$action = 'itemsListAjax';
			if($p == 'list' )
				$action = 'itemsList';
		}
		
		
		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('adv/indexView.php', $model);
	}
	
	
	
	
	#####################
	###### 	БРЕНДЫ	#####
	#####################
	function brandsIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		Core::renderView('adv/brands/indexView.php', $MODEL);
	}
	
	
	function brandsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['list'] = Brand::getList();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
			
	
		Core::renderView('adv/brands/list.php', $MODEL);
	}
	
	
	
	function brandsEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['item'] = Brand::get($_REQUEST['id']);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('adv/brands/edit.php', $MODEL);
	}
	
	
	
	function brandsEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$brand = Brand::get($_REQUEST['id']);
			if(!$brand)
				$brand = new Brand(); 
		
			$brand->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
			$brand->name = strPrepare($_REQUEST['name']);
		
			if($brand->pic)
				$prevFile = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$brand->pic;
			//return;
			if($_REQUEST['delete_pic']) 	// 	удаление картинки
			{
				if($brand->pic)
					unlink($prevFile);
				$brand->pic='';
			}
			
			# 	обработка файлов
			if($_FILES['pic']['name'])
			{
				$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.Brand::MEDIA_SUBDIR.'/';
				$newFileName = uniqid().'_'.Funx::correctFileName($_FILES['pic']['name']);
				//vd($newFileName);
				$savingResult = Slonne::saveFile($_FILES['pic'], $destDir, $newFileName);
				
				if(!$savingResult['problem'])	// 	сохранение новой картинки
				{
					if($brand->pic)
						unlink($prevFile);
					
					$brand->pic = /*Brand::MEDIA_SUBDIR.'/'.*/$newFileName;
				}
			}
			
			$errors = $brand->validate();
			
			if(!$errors)
			{
				if($brand->id)
					$brand->update();
				else
				{
					$brand->idx = Brand::getNextIdx();
					$brand->insert();
				}
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		
		echo '<script>window.top.editSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	function brandsDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				Brand::delete($id);
			}
			else
				$errors[]  = new Error('Ошибка! Не передан id!');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	
	function brandsListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			foreach($_REQUEST['idx'] as $key=>$val)
			{
				if($val = intval($val))
					Brand::setIdx($key, $val);
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		echo '<script>window.top.listSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	function switchBrandStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$brand = Brand::get($_REQUEST['brandId']);
			if($brand)
			{
				$statusToBe = $brand->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
					
				$brand->status = $statusToBe;
				$brand->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден бренд '.$_REQUEST['catId'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;
	
		echo json_encode($json);
	}
	
	
	
	
	
	
	
	
	
	
	
	###########################
	###	АРТИКУЛЬНЫЕ НОМЕРА	###
	###########################
	function articleNumbersIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		if(isset($_REQUEST['from_file']))
		{
			$file = ROOT.'/'.INCLUDE_DIR.'/content/artNumbers.txt';
			$str = file_get_contents($file);
			$tmp = explode("\r\n", $str);
			foreach($tmp as $key=>$val)
			{
				if(trim($val))
					$artNums[] = mb_strtoupper(mb_substr(trim($val), 0, 1)) .  mb_substr(trim($val), 1);
			}
			vd($artNums);
			
			if($_REQUEST['grab_from_file'])
			{
				foreach($artNums as $key=>$artNum)
				{
					if(!ArtNum::getByName($artNum))
					{
						$a = new ArtNum();
						$a->status = Status::$items[Status::ACTIVE];
						$a->name = $artNum;
						$a->insert();
					}
				}
			}
		}
		
	
		Core::renderView('adv/articleNumbers/indexView.php', $model);
	}
	
	
	
	
	function articleNumbersList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['list'] = ArtNum::getList();
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('adv/articleNumbers/list.php', $MODEL);
	}
	
	
	
	function articleNumbersEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['item'] = ArtNum::get($_REQUEST['id']);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('adv/articleNumbers/edit.php', $MODEL);
	}
	
	
	
	function articleNumbersEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']))
			{
				if(!($item = ArtNum::get($_REQUEST['id'])))
					$errors[] = new Error('Ошибка! Не найден арт. номер '.$_REQUEST['id'].'');
			}
			else
				$item = new ArtNum();
		
			$item->status = ($_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE));
			$item->name = strPrepare(trim($_REQUEST['name']));
			
			if($item->pic)
				$prevFile = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$item->pic;
		
			if($_REQUEST['delete_pic']) 	// 	удаление картинки
			{
				if($item->pic)
					unlink($prevFile);
				$item->pic='';
			}
		
			$errors = Error::merge($errors, $item->validate());
	
			if(!$errors)
			{
				# 	обработка файлов
				if($_FILES['pic']['name'])
				{
					$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.ArtNum::MEDIA_SUBDIR;
					$newFileName = uniqid().'_'.Funx::correctFileName($_FILES['pic']['name']);
					//vd($newFileName);
					$savingResult = Slonne::saveFile($_FILES['pic'], $destDir, $newFileName);
						
					if(!$savingResult['problem'])	// 	сохранение новой картинки
					{
						if($item->pic)
							unlink($prevFile);
								
						$item->pic = /*ArtNum::MEDIA_SUBDIR.'/'.*/$newFileName;
					}
				}
					
				if($item->id)
					$item->update();
				else
				{
					$item->idx = ArtNum::getNextIdx();
					$item->id = $item->insert();
				}
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		echo '<script>window.top.editSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	function articleNumbersDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				ArtNum::delete($id);
			}
			else
				$errors[] = new Error('Ошибка! Не передан id!');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	
	function articleNumbersListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			foreach($_REQUEST['idx'] as $key=>$val)
			{
				if($val = intval($val))
					ArtNum::setIdx($key, $val);
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		echo '<script>window.top.listSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	function articleNumbersSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$artnum = ArtNum::get($_REQUEST['id']);
			if($artnum)
			{
				$statusToBe = $artnum->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
		
				$artnum->status = $statusToBe;
				$artnum->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден арт. номер '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	#############################
	###  КАТЕГОРИЯ + БРЕНД  #####
	#############################
	function CatBrandIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['catsTree'] = AdvCat::getListTree(0, $status=null); 
			
			$allCatBrandCmbs = CatBrandCmb::getList();
			foreach($MODEL['catsTree'] as $key=>$cat)
			{
				foreach($allCatBrandCmbs as $cmb)
					if($cat->id == $cmb->catId)
						$cat->catBrandCombines[] = $cmb;
				# 	для сабс
				foreach($cat->subs as $sub)
					foreach($allCatBrandCmbs as $cmb)
						if($sub->id == $cmb->catId)
							$sub->catBrandCombines[] = $cmb;
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adv/cmbCatBrand/index.php', $MODEL);
	}
	
	
	function CatBrandBrandsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($catId = $_REQUEST['cat'])
			{
				if($cat = AdvCat::get($catId))
				{
					$MODEL['catBrands'] = CatBrandCmb::getByCatId($cat->id);
					$MODEL['brandsList'] = Brand::getList();				
				}
				else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;		
			}
			else 
				$MODEL['error'] = 'Ошибка! не передан cat id.';
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adv/cmbCatBrand/brandsList.php', $MODEL);
	}
	
	
	function CatBrandCheckBrand()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = AdvCat::get($_REQUEST['cat']);
			$brand = Brand::get($_REQUEST['brand']);
			$checked = $_REQUEST['checked'];
	
			if($cat && $brand )
			{
				$catBrandCmb = new CatBrandCmb();
				$catBrandCmb->catId = $cat->id;
				$catBrandCmb->brandId = $brand->id;
				if($checked)
				{
					if(!CatBrandCmb::get($cat->id, $brand->id))
						$catBrandCmb->insert();
				}
				else 
					$catBrandCmb->delete();
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors'] = $errors;
		$json['checked'] = $checked;
		echo json_encode($json); 
	}
	
	
	
	
	
	
	##########################
	## 	БРЕНД + АРТ.НОМЕР	##
	##########################
	function BrandArtnumIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['brands'] = Brand::getList();
		
			$allbrandArtnumsCmbs = BrandArtnumCmb::getList();
			foreach($MODEL['brands'] as $key=>$brand)
			{
				foreach($allbrandArtnumsCmbs as $cmb)
					if($brand->id == $cmb->brandId)
						$brand->brandArtnumCombines[] = $cmb;
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('adv/cmbBrandArtnum/index.php', $MODEL);
	}
	
	
	
	function BrandArtnumArtnumsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['fromExcelStr'] = $_REQUEST['fromExcel'];
			$MODEL['fromExcelArr'] = explode("\n", $MODEL['fromExcelStr']);
			$process = $_REQUEST['process'];
			//vd($_REQUEST);
			if($brandId = $_REQUEST['brand'])
			{
				if($brand = Brand::get($brandId))
				{
					$MODEL['artnumsList'] = ArtNum::getList();
					
					foreach($MODEL['artnumsList'] as $key=>$artnum)
					{
						if(in_array($artnum->name, $MODEL['fromExcelArr']))
						{
							if(in_array($process, array('on', 'off')))
							{
								$brandArtnumCmb = new BrandArtnumCmb();
								$brandArtnumCmb->brandId = $brand->id;
								$brandArtnumCmb->artnumId = $artnum->id;
								//vd($brandArtnumCmb);
								if($process == 'on')
								{
									if(!BrandArtnumCmb::get($brand->id, $artnum->id))
										$brandArtnumCmb->insert();
								}
								elseif($process == 'off')
									$brandArtnumCmb->delete();
							}
							
							$MODEL['found'][] = $artnum->name;
						}
					}
					
					$MODEL['brandArtnums'] = BrandArtnumCmb::getByBrandId($brand->id);
				}
				else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
			}
			else $MODEL['error'] = 'Ошибка! не передан cat id.';
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		
		Core::renderView('adv/cmbBrandArtnum/artnumsList.php', $MODEL);
	}
	
	
	
	function BrandArtnumCheckArtnum()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$brand = Brand::get($_REQUEST['brand']);
			$artnum = ArtNum::get($_REQUEST['artnum']);
			$checked = $_REQUEST['checked'];
		
			if($brand && $artnum )
			{
				$brandArtnumCmb = new BrandArtnumCmb();
				$brandArtnumCmb->brandId = $brand->id;
				$brandArtnumCmb->artnumId = $artnum->id;
				if($checked)
				{
					if(!BrandArtnumCmb::get($brand->id, $artnum->id))
						$brandArtnumCmb->insert();
				}
				else
				{
					$brandArtnumCmb->delete();
					$tmp = new CatBrandArtnumCmb();
				}
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['checked'] = $checked;
		echo json_encode($json); 
	}
	
	
	
	
	
	
	###########################################
	### 	КАТЕГОРИЯ + БРЕНД + АРТ.НОМЕР	###
	###########################################
	function CatBrandArtnumIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['catsTree'] = AdvCat::getListTree(0, $status=null);
			
			# 	кол-во брендов
			$allCatBrandCmbs = CatBrandCmb::getList();
			foreach($MODEL['catsTree'] as $key=>$cat)
			{
				foreach($allCatBrandCmbs as $cmb)
					if($cat->id == $cmb->catId)
						$cat->catBrandCombines[] = $cmb;
				# 	для сабс
				foreach($cat->subs as $sub)
					foreach($allCatBrandCmbs as $cmb)
						if($sub->id == $cmb->catId)
							$sub->catBrandCombines[] = $cmb;
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adv/cmbCatBrandArtnum/index.php', $MODEL);
	}
	
	
	function CatBrandArtnumBrandsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($catId = $_REQUEST['cat'])
			{
				if($cat = AdvCat::get($catId))
				{
					# 	требуются только определённые бренды, подвязанные к выбранной категории
					$catBrandCmbs = CatBrandCmb::getByCatId($cat->id);
					foreach($catBrandCmbs as $cmb)
						$tmp[] = $cmb->brandId;
					$allBrands = Brand::getList();
					foreach($allBrands as $brand)
						if(in_array($brand->id, $tmp))
							$MODEL['brandsList'][] = $brand;
						
					$allbrandArtnumCmbs = BrandArtnumCmb::getList();
					foreach($MODEL['brandsList'] as $key=>$brand)
					{
						foreach($allbrandArtnumCmbs as $cmb)
							if($brand->id == $cmb->brandId)
								$brand->brandArtnumCombines[] = $cmb;
						//vd($brand);
					}
				}
				else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
			}
			else $MODEL['error'] = 'Ошибка! не передан cat id.';
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adv/cmbCatBrandArtnum/brandsList.php', $MODEL);
	}
	
	
	function CatBrandArtnumArtnumsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['chosenCat'] = AdvCat::get($_REQUEST['cat']);
	
			if($brandId = $_REQUEST['brand'])
			{
				if($brand = Brand::get($brandId))
				{
					$MODEL['brandArtnums'] = BrandArtnumCmb::getByBrandId($brand->id);
					
					$brandArtnumCmb = BrandArtnumCmb::getByBrandId($brand->id);
					foreach($brandArtnumCmb as $cmb)
						$requiredArtnumIds[] = $cmb->artnumId;
					
					if($requiredArtnumIds)
						$MODEL['artnumsList'] = ArtNum::getListByIds($requiredArtnumIds);
					
					# 	выделенные арт номера
					$tmp = CatBrandArtnumCmb::getByCatIdAndBrandId($MODEL['chosenCat']->id, $brand->id);
					foreach($tmp as $cmb)
						$MODEL['chosenArtnumIds'][] = $cmb->artnumId;
				}
				else $MODEL['error'] = 'Ошибка! Категория не найдена! '.$catId;
			}
			else $MODEL['error'] = 'Ошибка! не передан cat id.';
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('adv/cmbCatBrandArtnum/artnumsList.php', $MODEL);
	}
	
	

	function CatBrandArtnumCheckArtnum()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = AdvCat::get($_REQUEST['cat']);
			$brand = Brand::get($_REQUEST['brand']);
			$artnum = ArtNum::get($_REQUEST['artnum']);
			$checked = $_REQUEST['checked'];
			/*vd($_REQUEST);
			vd($cat);
			vd($brand);
			vd($artnum);*/
			if($cat && $brand && $artnum )
			{
				
				$catBrandArtnumCmb = new CatBrandArtnumCmb();
				$catBrandArtnumCmb->catId = $cat->id;
				$catBrandArtnumCmb->brandId = $brand->id;
				$catBrandArtnumCmb->artnumId = $artnum->id;
				if($checked)
				{
					if(!CatBrandArtnumCmb::get($cat->id, $brand->id, $artnum->id))
						$catBrandArtnumCmb->insert();
				}
				else
					$catBrandArtnumCmb->delete();
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors'] = $errors;
		$json['checked'] = $checked;
		echo json_encode($json); 
	}
	
	
	
	#################
	# 	КАТЕГОРИИ	#
	#################
	/*function catsIndex()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		//self::catsList();
		Core::renderView('adv/cats/catsIndex.php', $MODEL);
	}
	
	function catsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['cat'] = AdvCat::get($_REQUEST['catId']);
			
			if($MODEL['cat'])
			{
				$MODEL['cat']->initClass();
				$MODEL['cat']->getElderCats();
				$MODEL['cat']->initProductVolumeUnits();
			}
			
			$MODEL['list'] = AdvCat::getList(intval($_REQUEST['catId']));
			foreach($MODEL['list'] as $cat)
			{
				$cat->initClass();
				$cat->subCatsCount = AdvCat::getCount($cat->id);
				$cat->advsCount = AdvItem::getCount($cat->id);
				$cat->advsCountByStatus = AdvItem::getCountGroupByStatus($cat->id);
				$cat->initProductVolumeUnits();
			}
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		//vd($MODEL);
	
		Core::renderView('adv/cats/catsList.php', $MODEL);
	}
	
	
	function catsSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		$errors = null;
		//vd($_REQUEST);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = AdvCat::get($_REQUEST['catId']);
			if($cat)
			{
				$statusToBe = $cat->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
				
				$cat->status = $statusToBe;
				$cat->update();
			}
			else 
				$errors[] = new Error('Ошибка! Не найдена категория '.$_REQUEST['catId'].'');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;
		
		echo json_encode($json);
	}
	
	
	function catEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['cat'] = AdvCat::get($_REQUEST['catId']);
			if($MODEL['cat'])
			{
				$MODEL['cat']->initClass();
				if($MODEL['cat']->class)
					$MODEL['cat']->class->initProps(Status::code(Status::ACTIVE));
			}
			
			$MODEL['classes'] = AdvClass::getList($status=null);
			foreach($MODEL['classes'] as $class)
				$class->initProps(Status::code(Status::ACTIVE));
			
			$MODEL['currentCatId'] = $_REQUEST['currentCat'];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adv/cats/catEdit.php', $MODEL);
	}
	
	
	
	function catEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		//usleep(800000);
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($_REQUEST['catId'] = intval($_REQUEST['catId']))
			{
				$cat = AdvCat::get($_REQUEST['catId']);
				if(!$cat)
					$errors[] = Slonne::setError('', 'Ошибка! Категория не найдена '.$_REQUEST['catId'].'');
			}
			else
				$cat = new AdvCat();
			
			if(!count($errors))
			{
				$cat->name = strPrepare($_REQUEST['name']);
				$cat->pid = $_REQUEST['pid'];
				$cat->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
				$cat->classId = intval($_REQUEST['classId']);
				
				$errors = $cat->validate();
				if(!count($errors))
				{
					if($cat->id)
						$cat->update();
					else 
					{
						$cat->idx = AdvCat::getNextIdx($cat->pid); 
						$cat->id = $cat->insert();
					}
				}
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors']=$errors;
		echo '<script>window.top.catEditSubmitComplete('.json_encode($json).')</script>';
		
	}
	
	
	function catsListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//usleep(800000);
			foreach($_REQUEST['idx'] as $catId=>$val)
			{
				if($val = intval($val))
					AdvCat::setIdx($catId, $val);
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors']=$errors;
		
		echo '<script>window.top.catsListSubmitComplete('.json_encode($json).')</script>';
	}*/
	
	
	
	
	
	#	СВОЙСТВА
	#	индекс
	/*function props()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		Core::renderView('adv/props/propsIndex.php', $model);
	}
	
	#	список типов
	function propsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$list = AdvProp::getList();
		foreach($list as $key=>$prop)
		{
			if($prop->type == 'select')
				$prop->initOptions($status=null);
			if($prop->type == 'table')
				$prop->initTableColumns($status=null);
		}
		$MODEL['list'] = $list;

		Core::renderView('adv/props/propsList.php', $MODEL);
	}
	
	
	#	редактирование типа (добавление)
	function propsEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$prop = AdvProp::get($_REQUEST['propId']);
		if($prop)
		{
			if($prop->type == 'select')
				$prop->initOptions($status=null);
			if($prop->type == 'table')
				$prop->initTableColumns($status=null);
		}
		//vd($prop);

		$MODEL['prop'] = $prop;
	
		Core::renderView('adv/props/propEdit.php', $MODEL);
	}
	
	
	function propsEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$warnings = null;
		$errors = null;
		
		$prop = AdvProp::get($_REQUEST['id']);
		
		if(!$prop->id)
			$prop = new AdvProp();
	
		if(!$prop->id)
		{
			$prop->code = $_REQUEST['code'];
			$prop->type = $_REQUEST['type'];
		}
		else
		{
			
		}
	
		$prop->name = $_REQUEST['name'];
		$prop->nameOnSite = $_REQUEST['nameOnSite'];
	
		$prop->size = $_REQUEST['size'];
		if($_REQUEST['width'] && $_REQUEST['height'])
			$prop->size = $_REQUEST['width'].'x'.$_REQUEST['height'];
	
		//$prop->options = $_REQUEST['options'];
		$prop->multiple = ($_REQUEST['pic_multiple'] || $_REQUEST['select_multiple']) ? 1 : 0;
		$prop->required = $_REQUEST['required'] ? 1 : 0;
		$prop->global = $_REQUEST['global'] ? 1 : 0;
		$prop->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
		
	
		$errors = $prop->validate();
		//vd($problems);
		//vd($error);
		if(!count($errors))
		{
			if($prop->id)
				$prop->update();
			else
				$prop->id = $prop->insert();
	
			#	заводим новые опции
			if($prop->type == 'select')
			{
				$newOptions = trim($_REQUEST['options']);
				if($newOptions)
				{
					$arr = explode("\r\n", $newOptions);
					foreach($arr as $key=>$val)
					{
						if(trim($val))
						{
							$opt = new AdvCatSelectOption();
							$opt->propId = $prop->id;
							$opt->value = strPrepare($val);
							$opt->status = Status::code(Status::ACTIVE);
							//vd($opt);	
							$optSimilar = $opt->getSimilarByValue();
							//vd($optSimilar);
							if(!$optSimilar)
								$opt->insert();
							else
							{
								$warnings[] = 'Опция <b>"'.$opt->value.'"</b> уже есть в этом списке! НЕ ДОБАВЛЕНА!';
							}
						}
					}
				}
			}
			
			
			# 	обработка столбцов типа ТАБЛИЦА
			if($prop->type=='table')
			{
				//vd($_REQUEST);
				# 	НОВЫЕ
				foreach($_REQUEST['insert'] as $num=>$colName)
				{
					$colName = trim($colName);
					if($colName && !AdvPropTableColumn::getByPropIdAndName($prop->id, $colName))
					{
						$col = new AdvPropTableColumn();
						$col->name = trim($colName);
						$col->idx = intval($_REQUEST['insert_idx'][$num]);
						$col->status = Status::code(Status::ACTIVE);
						$col->propId = $prop->id;

						$col->insert();
					}
				}
				
				# 	АПДЕЙТ
				//vd($_REQUEST);
				foreach($_REQUEST['update'] as $colId=>$colName)
				{
					$colName = trim($colName);
					if($colName && !AdvPropTableColumn::getByPropIdAndName($prop->id, $colName, $colId))
					{
						$col = AdvPropTableColumn::get($colId);
						$col->name = trim($colName);
						$col->idx = intval($_REQUEST['update_idx'][$colId]);

						$col->update();
					}
				}
				
			}
				
		}
		//vd($warnings);
	
		$result['errors'] = $errors;
		$result['warnings'] = $warnings;
		vd($result);
		echo '<script>window.top.propEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	
	function propsDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			AdvProp::delete($id);
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	
	function propOptionValueSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
	
		//vd($_REQUEST);
		$opt = AdvCatSelectOption::get($_REQUEST['id']);
		if($opt)
		{
			if($val = trim($_REQUEST['val']))
			{
				$opt->value = $val;
				#	проверка, существует ли такое значение уже в этом списке
				$similarByValue = $opt->getSimilarByValue();
				if($similarByValue)
					$error = "Опция с таким значением уже существует в этом списке!";
				else
				{
						
					$opt->update();
				}
			}
			else
				$error = 'Ошибка! Не передано значение! ';
		}
		else
			$error = 'Ошибка! Не удалось найти option id='.$_REQUEST['id'].' ';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	
	function propsOptionDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		//vd($_REQUEST);
		if($id = intval($_REQUEST['id']) )
		{
			AdvCatSelectOption::delete($id);
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	function propsSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$error = '';
		//vd($_REQUEST);
		
		$prop = AdvProp::get($_REQUEST['propId']);
		//vd($prop);
		if($prop)
		{
			$statusToBe = $prop->status->code == Status::code(Status::ACTIVE)->code ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
			//vd($statusToBe);
			
			$prop->status = $statusToBe;
			$prop->update();
		}
		else 
			$error='Ошибка! Не найдено свойство '.$_REQUEST['catId'].'';
		
		$json['error'] = $error;
		$json['status'] = $statusToBe;
		
		echo json_encode($json);
	}
	
	
	
	
	function propsSwitchTableColumnStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		//vd($_REQUEST);
	
		$col = AdvPropTableColumn::get($_REQUEST['columnId']);
		//vd($prop);
		if($col)
		{
			$statusToBe = $col->status->code == Status::ACTIVE? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
			//vd($statusToBe);
				
			$col->status = $statusToBe;
			$col->update();
		}
		else
			$error='Ошибка! Не найден столбец '.$_REQUEST['columnId'].'';
	
		$json['error'] = $error;
		$json['status'] = $statusToBe;
	
		echo json_encode($json);
	}
	
	
	
	
	function propsTableColumnDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		//vd($_REQUEST);
		if($id = intval($_REQUEST['id']) )
		{
			AdvPropTableColumn::delete($id);
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}*/
	
	
	
	
	#############
	#	КЛАССЫ	#
	#############
	/*function classes()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
	
		Core::renderView('adv/classes/classesIndex.php', $model);
	}
	
	#	список классов
	function classesListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$list = AdvClass::getList();
		foreach($list as $key=>$class)
			$class->initProps($status=null);
			
		$MODEL['list'] = $list;
	
		Core::renderView('adv/classes/classesList.php', $MODEL);
	}
	
	
	function classesEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		
		$class = AdvClass::get($_REQUEST['classId']);
		if($class)
			$class->initProps($status=null);
	
		//vd($class);
		$MODEL['class'] = $class;
		$MODEL['props'] = AdvProp::getList($status=null);
	
		Core::renderView('adv/classes/classesEdit.php', $MODEL);
	}
	
	
	function classesEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	//vd($_REQUEST);
		$errors = null;
		
		$class = AdvClass::get($_REQUEST['id']);
		
		if(!$class->id)
			$class = new AdvClass();
	
		$class->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
		$class->name = strPrepare($_REQUEST['name']);
	
		$errors = $class->validate();
	
		if(!count($errors))
		{
			if($class->id)
				$class->update();
			else
				$class->id = $class->insert();
	
			#	сохранение связей
			AdvClassPropRelation::deleteRelationsOfClass($class->id);
				
			foreach($_REQUEST['props'] as $propId=>$val)
			{
				$rel = new AdvClassPropRelation();
				$rel->classId = $class->id;
				$rel->propId = $propId;
				
				$rel->insert();
			}
		}
		
		$result['errors'] = $errors;
		//vd($result);
		echo '<script>window.top.classEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	
	
	function classesDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			$class = CatClass::get($id);
			if($class)
			{
				CatClass::delete($id);
	
				ClassPropRelation::deleteRelationsOfClass($id);
			}
		}
		else
			$error = 'Ошибка! Не передан id!';
	
		$result['error'] = $error;
	
		echo json_encode($result);
	}
	
	
	function classesSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$error = '';
		//vd($_REQUEST);
	
		$class = AdvClass::get($_REQUEST['classId']);
		//vd($prop);
		if($class)
		{
			$statusToBe = $class->status->code == Status::code(Status::ACTIVE)->code ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
			//vd($statusToBe);
				
			$class->status = $statusToBe;
			$class->update();
		}
		else
			$error='Ошибка! Не найдено свойство '.$_REQUEST['catId'].'';
	
		$json['error'] = $error;
		$json['status'] = $statusToBe;
	
		echo json_encode($json);
	}
	
	
	function classesListSaveChanges()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	//vd($_REQUEST);
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $classId=>$tmp)
			foreach($tmp as $propId=>$idx)
				AdvClassPropRelation::setIdx($classId, $propId, $idx);
		
			
		//vd($result);
		echo '<script>window.top.notice("Изменения сохранены"); window.top.classesList(); window.top.$.scrollTop()</script>';
	}*/
	
	
	
	
	#################
	# 	ОБЪЯВЛЕНИЯ	#
	#################
	function itemsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//usleep(800000);
		$elPP = AdvItem::ADMIN_ELEMENTS_PER_PAGE;
		$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
		$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
		$MODEL['elPP'] = $elPP;
		$MODEL['p'] = $page;
		
		//vd($_REQUEST);
		//vd($_REQUEST['catId']);
		$MODEL['cats'] = AdvCat::getFullCatsTree($status=Status::code(Status::ACTIVE));
		
		$MODEL['cities'] = City::getList(Country::KAZAKHSTAN_ID, Status::code(Status::ACTIVE), $orderBy='isLarge DESC, name');
		$MODEL['chosenCity'] = $MODEL['cities'][$_REQUEST['city']];
		
		$MODEL['cat'] = AdvCat::get($_REQUEST['catId']);
		
		$MODEL['dateFrom'] = $_REQUEST['dateFrom'];
		$MODEL['dateTo'] = $_REQUEST['dateTo'];
		
		$MODEL['chosenId'] = $_REQUEST['chosenId'];
		$MODEL['chosenUserId'] = $_REQUEST['chosenUserId'];
		//vd($_REQUEST);
		
		if($_REQUEST['status'])
			$MODEL['status'] = Status::num($_REQUEST['status']);
		if($_REQUEST['dealType'])
			$MODEL['dealType'] = DealType::num($_REQUEST['dealType']);
		
		$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
		$MODEL['desc'] = $_REQUEST['desc'];
		//vd($MODEL['status']);
		$params = array(
				'catId'=>$MODEL['cat']->id,
				'statuses'=>$MODEL['status'] ? array($MODEL['status']) : null,
				'dealType'=>$MODEL['dealType'],
				'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''), 
				'cityId'=>$MODEL['chosenCity']->id, 
				'dateFrom'=>$MODEL['dateFrom'],
				'dateTo'=>$MODEL['dateTo'],
				'id'=>$MODEL['chosenId'],
				'userId'=>$MODEL['chosenUserId'],
				'limit'=>$limit,
		);
		//vd($params);
		$MODEL['items'] = AdvItem::getList($params);
		$params['limit'] = '';
		$MODEL['totalCount'] = AdvItem::getCount($params);
		
		foreach($MODEL['items'] as $item)
		{
			// 	наполняем айдишники  
			$userIdsToTake[] = $item->userId;
			$catIdsToTake[] = $item->catId;
			$cityIdsToTake[] = $item->cityId;
		}
		// 	инитим юзеров
		$users = User::getByIdsList(array_unique($userIdsToTake));
		foreach($MODEL['items'] as $item)
			$item->user = $users[$item->userId];
		
		// 	инитим категории
		$cats = AdvCat::getByIdsList(array_unique($catIdsToTake));
		foreach($MODEL['items'] as $item)
			$item->cat = $cats[$item->catId];
		
		// 	инитим города
		$cities = City::getByIdsList(array_unique($cityIdsToTake));
		foreach($MODEL['items'] as $item)
			$item->city = $cities[$item->cityId];
		
		
		$MODEL['noTop'] = $_REQUEST['noTop'];
		
		$MODEL['totalCount'] = AdvItem::getCount($params); 
		
	
		Core::renderView('adv/items/itemsList.php', $MODEL);
	}
	
	
	function itemsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		Core::renderView('adv/items/itemsListIndex.php', $MODEL);
	}
	
	
	
	
	
}




?>