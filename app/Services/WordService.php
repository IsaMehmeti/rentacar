<?php
namespace App\Services;

use Intervention\Image\Facades\Image;
use PhpOffice\PhpWord\TemplateProcessor;

class WordService{

function fillWordTemplate($templatePath, $data) {
    // Create a new TemplateProcessor instance
    $templateProcessor = new TemplateProcessor($templatePath);

    // Loop through the data array and replace placeholders with actual data
    foreach ($data as $placeholder => $value) {
        $templateProcessor->setValue($placeholder, $value);
    }

    return response()->streamDownload(function () use ($templateProcessor) {
        $templateProcessor->saveAs('php://output');
    }, str_replace(' ', '-', $data["full_name"]."-".$data["model"].".docx"));
}


}
