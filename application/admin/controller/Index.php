<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Request;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class Index extends Common
{
    public function index()
    {
       return $this->fetch();
    }
    public function test(){
    	$arr=Db::query("select id,user_name,password from user");
		   $helper = new Sample();
		if ($helper->isCli()) {
		    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

		    return;
		}
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		    ->setLastModifiedBy('Maarten Balliauw')
		    ->setTitle('Office 2007 XLSX Test Document')
		    ->setSubject('Office 2007 XLSX Test Document')
		    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		    ->setKeywords('office 2007 openxml php')
		    ->setCategory('Test result file');
		    	$spreadsheet->setActiveSheetIndex(0)
			    ->setCellValue('A1', 'id')
			    ->setCellValue('B1', 'name')
			    ->setCellValue('C1', 'password');
		// Add some data
		    foreach ($arr as $key => $value) {
		    	$i=$key+2;
		    	$spreadsheet->setActiveSheetIndex(0)
			    ->setCellValue('A'.$i, $value['id'])
			    ->setCellValue('B'.$i, $value['user_name'])
			    ->setCellValue('C'.$i, $value['password']);
			  
		    }
		
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="01simple.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}
	 public function love(){
	 	$excel = request()->file('excel');
	 	$info = $excel->move('static/excel');
	 	if ($info) {
	 		  $exclePath=str_replace("\\","/",$info->getSaveName()) ;
	 		  $file_name = 'static/excel/'.$exclePath;
			$helper = new Sample();
			$helper->log('Loading file ' . pathinfo($file_name, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
			$spreadsheet = IOFactory::load($file_name);
			$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			
			array_shift($sheetData);
			foreach ($sheetData as $key => $value) {
				$name=$value['B'];
				$password=$value['C'];
				$data=['name'=>$name,'password'=>$password];
				Db::table('status')->insert($data);

			}
	 	}
		
	 }


	 
}