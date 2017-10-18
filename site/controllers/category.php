<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controller Joomla.
jimport('joomla.application.component.controller');

use Joomla\Registry\Registry;
 
/**
 * Category контроллер.
 */
class JShopMenuControllerCategory extends JControllerLegacy
{    
    function view_file (){
        $menu_type_id = JRequest::getVar('menu_type_id', 0);
        $category_id = JRequest::getVar('category_id', 0);
        if($menu_type_id == 0 || $category_id == 0){
            echo 'Файл не найден!';
            exit();
        }
        
        $model = $this->getModel('category');
        $pathFile = $model->getPathFile($menu_type_id,$category_id);
        
        
        $exp = explode('/',$pathFile);
        $name = end($exp);
        
        $filename = JPATH_BASE.DIRECTORY_SEPARATOR.$pathFile;
        
        if(!file_exists($filename)){
            echo 'Файл не найден!';
            exit();
        }
        
        $this->showPDF($filename);
        
        exit();
    }
    
    function open_form(){
        $document = JFactory::getDocument();
        $input = JFactory::getApplication()->input;
        $input->set('view', 'form');
        
        return parent::display();
    }
    
    function loadDetals(){
        $document = JFactory::getDocument();
        $input = JFactory::getApplication()->input;
        $document->setType('detals');
        $input->set('view', 'category');
        $input->set('layout', 'detals');
        
        return parent::display();
    }
    
    function send_mail(){
        $app = JFactory::getApplication();
        $post = $_POST;
        $params = $app->getParams();
        $url_return = JRequest::getVar('url_return','/');
        $model = $this->getModel('category');
        $template_params = getTemplateParams();
        
        $_SESSION['jshopmenu']['form'] = $post;
        
        
        $category_id = $post['category_id'];
        $menu_type_id = $post['menu_type_id'];
        
        if($params->get('show_captcha',0)){
            // Get the user data.
            $model_form = $this->getModel('Form', 'JShopMenuModel');
            // Validate captcha.
    		$form = $model_form->getForm();
            
            if (!$form)
    		{
    			JError::raiseError(500, $model_form->getError());
    
    			return false;
    		}
            
            $data = $model_form->validate($form, $post);
            
            if($data === false){
                $this->setMessage('Вы не подтвердили что вы не робот!<br/>Пройдите проверку и отправьте заявку снова.','error');
                $this->setRedirect($post['url_return']);
                return true;
            }
        }
        
        //  id категории
        $model->setState('category.category_id', $category_id); 
        
        //  id 
        $model->setState('filter.menu_type_id', $menu_type_id);
        $model->setState('params', $params);
        
        $category = $model->getCategory($category_id);
        
        $menu_types = $model->getMenuTypes();
        foreach($menu_types as $key=>$menu_type){
            if($menu_type->menu_type_id != $menu_type_id){
                unset($menu_types[$key]);
            }
        }
        
        $model->updateMenuTypes($menu_types);        
        sort($menu_types);
        $menu_type = $menu_types[0];
        
        $params = $menu_type->params;
        
        $persons = isset($post['kol_person']) && !empty($post['kol_person']) ? $post['kol_person'] : $category->koll_person;

        // GENERATE XSL FILE
        $excel_file = $this->genarateExcelFile($menu_type,$params,$category,$persons,$post);
        // END GENERATE XSL FILE
        if($menu_type->create_your){
            $session = JFactory::getSession();
            $session->clear('jshopmenu.form.koll');
        }
        
        $email_to = $params->get('email_type')==1 || $params->get('email_to')=='' ? $app->get('mailfrom') : $params->get('email_to');
        
        $fromname = $app->get('fromname');
		$sitename = $app->get('sitename');
        $sitemail = $app->get('mailfrom');
        $subject = $params->get('email_subject_admin');
        $description = $params->get('email_desc_admin');
        $from = 'user@'.$_SERVER['SERVER_NAME'];
        
        $name    = $post['name'];
		$email   = isset($post['email']) ? $post['email'] : '';
        $telefon = $post['telefon'];
        $organization = $post['organization'];
        $date    = $post['date'];
        $time_begin = $post['time_begin'];
        $time    = $post['time'];
        $mesto = $post['mesto'];
        $adress = $post['adress'];
        $dop_uslugi =  array(
            //(isset($post['mesto']) && $post['mesto']==1 ? 'подбор места проведения' : false),
            (isset($post['arenda']) && $post['arenda']==1 ? 'аренда мебели' : false),
            (isset($post['ukrashenie']) && $post['ukrashenie']==1 ? 'украшение зала' : false),
            //(isset($post['disco']) && $post['disco']==1 ? 'дискотека' : false),
            (isset($post['razvlekalka']) && $post['razvlekalka']==1 ? 'развлекательная программа' : false)
        );
        
        $message = '';
        
        $msg = '<h1>'.$subject.'</h1>';
        $msg .= '<h4>Данные заказчика<h4>';
        $msg .= '<table style="width: 100%;">';
        $msg .= '<tr style="background-color: #dddddd;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Имя</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$name.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #dddddd;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Телефон</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$telefon.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #ffffff;">';
        $msg .= '<td style="padding: 10px;text-align: left;">E-mail</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$email.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #ffffff;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Организация</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$organization.'</td>';
        $msg .= '</tr>';
        $msg .= '</table>';
        $msg .= '<h4>Данные заказа<h4>';
        $msg .= '<table style="width: 100%;">';
        $msg .= '<tr style="background-color: #dddddd;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Дата мероприятия</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$date.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #ffffff;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Время начала мероприятия</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$time_begin.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #dddddd;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Продолжительность мероприятия</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$time.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #ffffff;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Место проведения мероприятия</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$mesto.'</td>';
        $msg .= '</tr>';
        if($mesto=='Есть своя площадка'){
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Адрес</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$adress.'</td>';
            $msg .= '</tr>';
        }
        $msg .= '<tr style="background-color: #ffffff;">';
        $msg .= '<td style="padding: 10px;text-align: left;">Количество гостей</td>';
        $msg .= '<td style="padding: 10px;text-align: left;">'.$persons.'</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #fafafa;">';
        $msg .= '<td style="padding: 10px;text-align: center;" colspan="2">Дополнительные услуги</td>';
        $msg .= '</tr>';
        $msg .= '<tr style="background-color: #dddddd;">';
        foreach($dop_uslugi as $dop){
            if($dop)
                $msg .= '<td style="padding: 10px;text-align: center;">'.$dop.'</td>';
        }
        $msg .= '</tr>';
        $msg .= '</table>';
        
        $msg .= '<p>';
        $msg .= $description;
        $msg .= '</p>';
        
        // Start capturing output into a buffer
		ob_start();
        $content = $msg;
		// Include the requested template filename in the local scope
		// (this will execute the view logic).
		include JPATH_BASE.'/templates/teplohod/email_tmpl/default_admin.php';

		// Done with the requested template; get the buffer and
		// clear it.
		$message = ob_get_contents();
		ob_end_clean();
        
        
        // Отправка почтовых сообщений
        $mail = JFactory::getMailer();
		$mail->addRecipient($email_to);
        
        $sender = array( $from, $name );
        $mail->setFrom($from, $name);
        $mail->setSender($sender);
        
        // Создание письма
        $mail->setSubject($sitename . ': ' . $subject);
        $mail->setBody($message);
        $mail->isHTML(true);
        $mail->Encoding = 'base64';
        // Optional file attached
        if($excel_file)
            $mail->addAttachment($excel_file);
        // Optionally add embedded image
        $mail->AddEmbeddedImage(JPATH_BASE.DS.$template_params->get('logo_bot'), 'logo_footer', 'footer-logo.png', 'base64', 'image/png');
        
        // exit();     
        if ($mail->Send()) {
            $this->setMessage($params->get('email_success_text','Сообщение отправлено, спасибо!'));
        } else {
            $this->setMessage('Извените, но при отправке сообщения произошла ошибка, попробуйте снова!','error');
            $this->setRedirect($post['url_return']);
            if($excel_file) unlink($excel_file);
            return true;
        }
        
        if($email && !empty($email)){
            
            $subject = $params->get('email_subject_user');
            $description = $params->get('email_desc_user');
            
            $message = '';
            
            $msg = '<h1>'.$subject.'</h1>';
            $msg .= '<h4>Ваши данные<h4>';
            $msg .= '<table style="width: 100%;">';
            $msg .= '<tr style="background-color: #dddddd;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Ваше имя</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$name.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #dddddd;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Ваш телефон</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$telefon.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Ваш E-mail</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$email.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Ваша организация</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$organization.'</td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '<h4>Данные заказа<h4>';
            $msg .= '<table style="width: 100%;">';
            $msg .= '<tr style="background-color: #dddddd;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Дата мероприятия</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$date.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Время начала мероприятия</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$time_begin.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #dddddd;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Продолжительность мероприятия</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$time.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Место проведения мероприятия</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$mesto.'</td>';
            $msg .= '</tr>';
            if($mesto=='Есть своя площадка'){
                $msg .= '<tr style="background-color: #ffffff;">';
                $msg .= '<td style="padding: 10px;text-align: left;">Адрес</td>';
                $msg .= '<td style="padding: 10px;text-align: left;">'.$adress.'</td>';
                $msg .= '</tr>';
            }
            $msg .= '<tr style="background-color: #ffffff;">';
            $msg .= '<td style="padding: 10px;text-align: left;">Количество гостей</td>';
            $msg .= '<td style="padding: 10px;text-align: left;">'.$persons.'</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #fafafa;">';
            $msg .= '<td style="padding: 10px;text-align: center;" colspan="2">Дополнительные услуги</td>';
            $msg .= '</tr>';
            $msg .= '<tr style="background-color: #dddddd;">';
            foreach($dop_uslugi as $dop){
                if($dop)
                    $msg .= '<td style="padding: 10px;text-align: center;">'.$dop.'</td>';
            }
            $msg .= '</tr>';
            $msg .= '</table>';
            
            $msg .= '<p>';
            $msg .= $description;
            $msg .= '</p>';
            
            // Start capturing output into a buffer
    		ob_start();
            $content = $msg;
    		// Include the requested template filename in the local scope
    		// (this will execute the view logic).
    		include JPATH_BASE.'/templates/teplohod/email_tmpl/default_user.php';
    
    		// Done with the requested template; get the buffer and
    		// clear it.
    		$message .= ob_get_contents();
    		ob_end_clean();
            
            $from = JStringPunycode::emailToPunycode($email);
            $mail_site = $app->get('mailfrom',false) ? $app->get('mailfrom') : 'admin@'.$_SERVER['SERVER_NAME'];
            // Отправка почтовых сообщений
            $mail2 = JFactory::getMailer();
            $sender = array( $mail_site, $sitename );
            $mail2->setFrom($mail_site, $sitename);
            $mail2->setSender($sender);
    		$mail2->addRecipient($from);
            $mail2->setSubject($subject);
            $mail2->setBody($message);
            $mail2->isHTML(true);
            $mail2->Encoding = 'base64';
            // Optional file attached
            if($excel_file && isset($post['excel']) && $post['excel']==1)
                $mail2->addAttachment($excel_file);
            // Optionally add embedded image
            $mail2->AddEmbeddedImage(JPATH_BASE.DS.$template_params->get('logo'), 'logo_header', 'logo-white.png', 'base64', 'image/png');
            
            if(!$mail2->Send()){
                $this->setMessage('Предупреждение, обратное сообщение о подтверждении вам не было отправлено, из-за ошибки!','warning');
            }
        }
                
        $this->setRedirect($post['url_return']);
        
        if($excel_file) unlink($excel_file);
        if(isset($_SESSION['jshopmenu'])) unset($_SESSION['jshopmenu']);
        return true;
    }
    
    function onsesion(){
        if(isset($_GET['value']) && !empty($_GET['value']))
        {
            if($_GET['product_id']){
                $_SESSION['jshopmenu'][$_GET['category_id']][$_GET['menu_type_id']]['col_bld'][$_GET['product_id']] = $_GET['value'];
            }else{
                $_SESSION['jshopmenu'][$_GET['category_id']][$_GET['menu_type_id']]['col_per'] = $_GET['value'];
            }
        }
        if(isset($_GET['clear']))
        {
            if(isset($_SESSION['jshopmenu'])) unset($_SESSION['jshopmenu']);
        }
        exit();
    }
    
    private function showPDF($filename) {
         $pdf=fopen($filename,'r');
         $content=fread($pdf,filesize($filename));
         fclose($pdf);
         header('Content-type: application/pdf');
         print($content);
    }
    
    private function genarateExcelFile($menu_type,$params,$category,$persons=20,$post=array()){
        
        $session = JFactory::getSession();

        $kolls = $menu_type->create_your ? $session->get('jshopmenu.form.koll',array()) : array();
        
        $category_name = $category->name;
        $obslujivanie = $params->get('category_koef_obslujivanie', 0.10);
        
        require_once JPATH_BASE.'/administrator/components/com_jshopmenu/phpexcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("gc-briz")
        				->setLastModifiedBy("gc-briz")
        				->setTitle("Office 2007 XLS Document")
        				->setSubject("Office 2007 XLS Document")
        				->setDescription("")
        				->setKeywords("")
        				->setCategory("");
        $objPHPExcel->getActiveSheet()->setTitle('Общее меню');
        
        $objPHPExcel->setActiveSheetIndex(0)
                    /*->mergeCells('C2:F2')->setCellValue('C2', '+7 (495) 979-84-87')
                    ->mergeCells('C3:F3')->setCellValue('C3', '+7 (495) 972-88-87')*/
                    ->mergeCells('A1:F1')->setCellValue('A1', mb_strtoupper($category_name).' - '.$menu_type->name)
                    /*->mergeCells('A7:B7')->setCellValue('A7', 'Дата мероприятия:')
                    ->mergeCells('A8:B8')->setCellValue('A8', 'Время мероприятия')
                    ->mergeCells('A9:B9')->setCellValue('A9', 'Формат мероприятия')
                    ->mergeCells('A10:B10')->setCellValue('A10', 'Вариант рассадки / накрытия')*/
                    ->mergeCells('A3:B3')->setCellValue('A3', 'Кол-во персон:')
                    ->mergeCells('C3:F3')->setCellValue('C3', $persons)
                    ->setCellValue('A5', 'Выход гр.')
                    ->setCellValue('B5', 'Наименование блюда (состав)')
                    ->setCellValue('C5', 'Цена')
                    ->setCellValue('D5', 'Кол-во')
                    ->setCellValue('E5', 'Сумма')
                    ->setCellValue('F5', 'Выход гр/мл на персону');
                    
        $rowCount = 6;
        $gramm = 0;
        $ml = 0;
        $summ = 0;
        
        // for all cells
        $style = array(
          'font'  => array(
            //'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 10,
            'name'  => 'Calibri'
        ));
        /*$style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );*/
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        
        foreach($menu_type->product_types as $p_type){
            $koll_indekt=0;
            foreach($p_type->products as $p){
                $koll_indekt = $menu_type->create_your ? (isset($kolls[$p->product_id]) ? $kolls[$p->product_id] : $p->default_koll) : (($p->koll > 0) ? $p->koll : $p->default_koll);
                if($koll_indekt) break;
            }
            if(!$koll_indekt) continue;
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($p_type->name));
            $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':F'.$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
            $rowCount++;
            
            foreach($p_type->products as $p){
                $weigth = ((int)$p->ves) ? (int)$p->ves : (int)$p->default_ves;
                $price = ($p->price > 0) ? $p->price : $p->default_price;
                $koll = $menu_type->create_your ? (isset($kolls[$p->product_id]) ? ($kolls[$p->product_id]/$persons) : $p->default_koll) : (($p->koll > 0) ? $p->koll : $p->default_koll);
                if(!$koll) continue;
                $quantity = $koll*$persons;
                
                $objRichText = new PHPExcel_RichText();
                $objBold = $objRichText->createTextRun($p->name);
                $objBold->getFont()->setBold(true);
                $objRichText->createText(' (' . $p->short_description . ')');
                
                
                $objPHPExcel->getActiveSheet()
                    ->getStyle('A'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('B'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('C'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('D'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('E'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('F'.$rowCount)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    
                $objPHPExcel->getActiveSheet()
                    ->getStyle('A'.$rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()
                    ->getStyle('C'.$rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00_-"₽"');
                $objPHPExcel->getActiveSheet()
                    ->getStyle('E'.$rowCount)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00_-"₽"');
                $objPHPExcel->getActiveSheet()
                    ->getStyle('F'.$rowCount)
                    ->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A'.$rowCount, $weigth)
                    ->SetCellValue('B'.$rowCount, $objRichText)
                    ->SetCellValue('C'.$rowCount, $price)
                    ->SetCellValue('D'.$rowCount, round($quantity))
                    ->SetCellValue('E'.$rowCount, $price*$quantity)
                    ->SetCellValue('F'.$rowCount, $weigth*$koll);
                if($p_type->napitki)
                    $ml += $weigth*$koll;
                else
                    $gramm += $weigth*$koll;
                $summ += $price*$quantity;
                
                    
                $rowCount++;
            }
        }
     
        $rowCount++;
        
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.$rowCount)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00_-"₽"');
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($rowCount+1))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00_-"₽"');
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($rowCount+2))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($rowCount+3))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($rowCount+4))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00_-"₽"');
            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($rowCount+5))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00_-"₽"');
        
        $objPHPExcel->getActiveSheet()
            ->SetCellValue('B'.$rowCount, 'Полная стоимость организации питания:')
            ->SetCellValue('B'.($rowCount+1), 'Стоимость питания на персону:')
            ->SetCellValue('B'.($rowCount+2), 'Выход грамм на персону:')
            ->SetCellValue('B'.($rowCount+3), 'Выход мл. на персону:')
            ->SetCellValue('B'.($rowCount+4), 'Обслуживание '.floatval($obslujivanie*100).'%')
            ->SetCellValue('B'.($rowCount+5), 'Итого к оплате:')
            ->SetCellValue('E'.$rowCount, $summ)
            ->SetCellValue('E'.($rowCount+1), round($summ/$persons,2))
            ->SetCellValue('E'.($rowCount+2), round($gramm,2))
            ->SetCellValue('E'.($rowCount+3), round($ml,2))
            ->SetCellValue('E'.($rowCount+4), round($summ*$obslujivanie,2))
            ->SetCellValue('E'.($rowCount+5), round($summ*$obslujivanie + $summ,2));
        
        /*while($row = mysql_fetch_array($result)){
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['age']);
            $rowCount++;
        }*/
        
        
        
        /*$style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A16:A'.$rowCount)->applyFromArray($style);*/
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A7:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B7:B'.$rowCount)->getAlignment()->setWrapText(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':F'.($rowCount+5))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FDE9D9');;
        $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount+5).':F'.($rowCount+5))->getFont()->setBold(true);
        
        /*$objPHPExcel->getActiveSheet()->getStyle("A5:F".$rowCount+5)->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
        );*/
        $BStyle = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_MEDIUM
            ),
            'inside' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
          )
        );
        $objPHPExcel->getActiveSheet()->getStyle("A5:F".($rowCount+5))->applyFromArray($BStyle);
        // autosize
        /*for($col = 'A'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }*/
        
        require_once JPATH_BASE.'/administrator/components/com_jshopmenu/phpexcel/PHPExcel/IOFactory.php';
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        
        $filename = JPATH_BASE.'/tmp/output.xlsx';
        
        $writer->save($filename);
        if(file_exists($filename)){
            return $filename;
        }else{
            return false;
        }
    }
    
    
    function raschet() {
        $price = 0;
        $app = JFactory::getApplication();
        $post = $_POST;
        $params = $app->getParams();
        $model = $this->getModel('category');
        
        $category_id = $post['category_id'];
        $menu_type_id = $post['menu_type_id'];
        
        $koll_person = $post['kol_person'];
        
        
        //  id категории
        $model->setState('category.category_id', $category_id); 
        
        //  id 
        $model->setState('filter.menu_type_id', $menu_type_id);
        $model->setState('params', $params);
        
        $category = $model->getCategory($category_id);
        
        $menu_types = $model->getMenuTypes();
        foreach($menu_types as $key=>$menu_type){
            if($menu_type->menu_type_id != $menu_type_id){
                unset($menu_types[$key]);
            }
        }
        
        
        
        $model->updateMenuTypes($menu_types);        
        sort($menu_types);
        $menu_type = $menu_types[0];
        
        foreach($menu_type->product_types as $product_type){
            foreach($product_type->products as $product){
                $koll = $koll_person*$product->koll;
                $price += $product->price*$koll;
            }
        }
        
        
        $mesto = JRequest::getVar('mesto',0);
        $arenda = JRequest::getVar('arenda',0);
        $ukrashenie = JRequest::getVar('ukrashenie',0);
        $disco = JRequest::getVar('disco',0);
        $razvlekalka = JRequest::getVar('razvlekalka',0);
        
        
        $price += $price*$params->get('category_koef_obslujivanie',0.10);
        
        if($mesto){
            $price += 8000;
        }
        if($arenda){
            $price += 8000;
        }
        if($ukrashenie){
            $price += 8000;
        }
        if($disco){
            $price += 8000;
        }
        if($razvlekalka){
            $price += 8000;
        }
        
        $price = round($price);
        
        echo $price;
        exit();
    }
}
?>