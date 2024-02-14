<?php

namespace App\Controllers\Documents;

use App\Controllers\MoySkladController;
use App\Utils\ToolClass;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentController
{
    private ToolClass $toolClass;
    private MoySkladController $moySkladController;

    public function __construct(ToolClass $toolClass, MoySkladController $moySkladController)
    {
        $this->toolClass = $toolClass;
        $this->moySkladController = $moySkladController;
    }

    public function createAct($dealNum) {
        $dealData = $this->moySkladController->getTotalOrderData($dealNum);
        $dateCreate = date('d.m.Y');
        $stringDate = $this->toolClass->getStringDate($dateCreate);
        $totalSumStr = $this->toolClass->num2str($dealData['common_data']['total_sum']);

        $customerName = $dealData['customer_data']['customer_name'];
        $passportNum = '2916 746124';
        $passportInfo = '26.04.2016, отделом УФМС России по Калужской обл. в гор. Калуге';
        $customerAddr = 'г. Калуга ул.М.Жукова д.47, кв.34';


        $templateAddr = __DIR__.'/../../../public/assets/docs/act-priema.docx';

        Settings::setOutputEscapingEnabled(true);
        $templateProcessor = new TemplateProcessor($templateAddr);
        $templateProcessor->setValue('date_create', $dateCreate);
        $templateProcessor->setValue('string_date', $stringDate);
        $templateProcessor->setValue('customerName', $customerName);
        $templateProcessor->setValue('passportNum', $passportNum);
        $templateProcessor->setValue('passportDataIssue', $passportInfo);
        $templateProcessor->setValue('customerAddr', $customerAddr);
        $templateProcessor->setValue('numToStr', $totalSumStr);

        $products = $dealData['products'];
        $productValuesArr = [];

        foreach ($products as $product) {
            $productValuesArr[] = [
                'productNum' => $product['position'],
                'productName' => $product['name'],
                'productQunt' => $product['quantity'],
                'productPrice' => $product['price'].' руб.',
                'productTotal' => $product['total'].' руб.'
            ];
        }

        $templateProcessor->cloneRowAndSetValues('productNum', $productValuesArr);
        $fileName = 'act_rabot_'.$dealNum.'.docx';
        $outPutPath = __DIR__.'/../../../public/temp-docs/';
        $outputFile = $outPutPath.$fileName;
        $templateProcessor->saveAs($outputFile);

        return [
            'filename' => $fileName,
            'filepath' => $outputFile,
            'output' => '/temp-docs/'.$fileName
        ];
    }

    public function createDealDoc($dealNum) {
        $dealData = $this->moySkladController->getTotalOrderData($dealNum);
        $dateCreate = date('d.m.Y');
        $stringDate = $this->toolClass->getStringDate($dateCreate);


        $totalSum = $dealData['common_data']['total_sum'];
        $totalSumStr = $this->toolClass->num2str($totalSum);
        $payedSum = $dealData['common_data']['payed_sum'];


        $passportNum = '2916 746124';
        $passportInfo = 'Отделом УФМС России по Калужской обл, в гор. Калуге';
        $customerAddr = 'г. Калуга ул. М.Жукова д.47 кв.34';
        $customerName = $dealData['customer_data']['customer_name'];
        $customerPhone = $dealData['customer_data']['phone'];




        $templateAddr = __DIR__.'/../../../public/assets/docs/dogovor-memorial.docx';
        $fileName = 'dogovor_'.$dealNum.'.docx';
        $outPutPath = __DIR__.'/../../../public/temp-docs/';
        $outputFile = $outPutPath.$fileName;

        $templateProcessor = new TemplateProcessor($templateAddr);
        $templateProcessor->setValue('dealNum', $dealNum);
        $templateProcessor->setValue('stringDate', $stringDate);
        $templateProcessor->setValue('customerName', $customerName);
        $templateProcessor->setValue('customerName', $customerName);
        $templateProcessor->setValue('stringTotalSum', $totalSumStr);
        $templateProcessor->setValue('passportNum', $passportNum);
        $templateProcessor->setValue('passportInfo', $passportInfo);
        $templateProcessor->setValue('customerAddr', $customerAddr);
        $templateProcessor->setValue('customerPhone', $customerPhone);
        $templateProcessor->setValue('TotalSum', $totalSum);
        $templateProcessor->saveAs($outputFile);

        return [
            'filename' => $fileName,
            'filepath' => $outputFile,
            'output' => '/temp-docs/'.$fileName
        ];
    }
}