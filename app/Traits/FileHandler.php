<?php

namespace App\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;

trait FileHandler
{
    private $files;
    /**
     * this function takes a base64 encoded image and store it in the filesystem and return the name of it
     * (ex. 12546735.png) that will be stored in DB
     * @param $file
     * @param $dir
     * @param false $is_base_64
     * @return string
     */
    public function storeFile($file, $dir){
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/'.$dir));
        return Storage::disk('public')->putFile($dir, $file);
    }

    /**
     * this function takes $newImage(base64 encoded) and $oldImage(DB name) ,
     * it deletes the $oldImage from the filesystem and store the $newImage and return it's name that will be stored in DB
     * @param $new_file
     * @param $old_file
     * @param $dir
     * @return string
     */
    public  function updateFile($new_file, $old_file, $dir){
        $this->deleteFile($old_file);
        $name=$this->storeFile($new_file,$dir);
        return $name;
    }

    /**
     * this function takes image(DB name) and deletes it from the filesystem ,
     * returns true if deleted and false if not found
     * @param $file
     * @return bool
     */
    public  function deleteFile($file){

        if($file && file_exists(storage_path('app/public/').$file)){
            Storage::disk('public')->delete($file);
            return true;
        }
        return false;
    }

    /**
     * make directory for files
     * @param $path
     * @return mixed
     */
    private function makeDirectory($path)
    {
        $this->files->makeDirectory($path, 0777, true,true);
        return $path;
    }

    public function deleteDirectory($directory)
    {
        $directoryPath = storage_path('app/public/') . $directory;

        if (is_dir($directoryPath)) {
            Storage::disk('public')->deleteDirectory($directory);
            return true;
        }

        return false;
    }

    public function storeImage($fileContent, $dir,$extension)
    {
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/' . $dir));

        // Directly store the file content without base64 encoding or decoding
        $filePath = $dir . '/' . uniqid() . '.' . $extension;
        Storage::disk('public')->put($filePath, $fileContent);

        return $filePath;
    }
    public function storeAttachment($fileContent, $dir)
    {
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/' . $dir));

        // Directly store the file content without base64 encoding or decoding
        $filePath = $dir . '/' . uniqid() . '.docx';
        Storage::disk('public')->put($filePath, $fileContent);

        return $filePath;
    }

    public function signPdf($pdfPath, $signaturePath, $dir, $position = 'bottom-right', $width = null, $height = null)
    {
        // Default signature dimensions
        $defaultWidth = 50;
        $defaultHeight = 20;

        // Use default values if parameters are not provided
        $width = $width ?? $defaultWidth;
        $height = $height ?? $defaultHeight;

        // Create a new FPDI instance
        $pdf = new Fpdi();

        // Set the source file
        $pageCount = $pdf->setSourceFile($pdfPath);

        // Loop through each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $templateId = $pdf->importPage($pageNo);
            $pdf->useTemplate($templateId, 0, 0);

            // Get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            // Determine X and Y based on the position
            if ($position === 'bottom-right') {
                $x = $size['width'] - $width - 10; // 10 units padding from the right
            } elseif ($position === 'bottom-left') {
                $x = 10; // 10 units padding from the left
            } else {
                // Default to bottom-right if invalid position is given
                $x = $size['width'] - $width - 10;
            }
            $y = $size['height'] - $height - 30; // 30 units padding from the bottom

            // Add the signature image at the determined position
            $pdf->Image($signaturePath, $x, $y, $width, $height);
        }

        // Generate the output path
        $outputPath = $dir . '/' . uniqid() . '.pdf';
        $fullOutputPath = storage_path('app/public/' . $outputPath);

        // Output the new PDF
        $pdf->Output($fullOutputPath, 'F');

        return $outputPath;
    }

    public function storeSignature($base64Image, $dir)
    {
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/' . $dir));
        // Decode the base64 image
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = base64_decode($base64Image);
        // Generate a unique file name
        $filePath = $dir . '/' . uniqid() . '.png';
        Storage::disk('public')->put($filePath, $base64Image);
        return $filePath;
    }


    public function generatePdfFromHtml($htmlContent, $dir)
    {
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/' . $dir));
        // Generate the PDF
        $pdf = Pdf::loadHTML($htmlContent);
        // Generate a unique file name
        $filePath = $dir . '/' . uniqid() . '.pdf';
        $fullOutputPath = storage_path('app/public/' . $filePath);
        // Save the PDF to the specified directory
        Storage::disk('public')->put($filePath, $pdf->output());
        return $filePath;
    }

}

