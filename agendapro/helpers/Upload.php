<?php
// helpers/Upload.php

class Upload {
    
    private $uploadDir;
    private $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $allowedPdfTypes = ['application/pdf'];
    private $maxImageSize = 2097152; // 2MB
    private $maxPdfSize = 10485760; // 10MB
    
    public function __construct() {
        $this->uploadDir = __DIR__ . '/../uploads/cursos/';
        $this->createDirectories();
    }
    
    private function createDirectories() {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        if (!file_exists($this->uploadDir . 'imagenes/')) {
            mkdir($this->uploadDir . 'imagenes/', 0777, true);
        }
        if (!file_exists($this->uploadDir . 'descriptores/')) {
            mkdir($this->uploadDir . 'descriptores/', 0777, true);
        }
    }
    
    /**
     * Subir imagen del curso
     */
    public function uploadImage($file, $cursoId) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Error al subir el archivo'];
        }
        
        if (!in_array($file['type'], $this->allowedImageTypes)) {
            return ['success' => false, 'error' => 'Tipo de archivo no permitido. Use JPG, PNG, GIF o WEBP'];
        }
        
        if ($file['size'] > $this->maxImageSize) {
            return ['success' => false, 'error' => 'La imagen no debe superar los 2MB'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'curso_' . $cursoId . '_' . time() . '.' . $extension;
        $destination = $this->uploadDir . 'imagenes/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => 'uploads/cursos/imagenes/' . $filename];
        }
        
        return ['success' => false, 'error' => 'Error al guardar el archivo'];
    }
    
    /**
     * Subir PDF del descriptor
     */
    public function uploadPdf($file, $cursoId) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Error al subir el archivo'];
        }
        
        if (!in_array($file['type'], $this->allowedPdfTypes)) {
            return ['success' => false, 'error' => 'Tipo de archivo no permitido. Use PDF'];
        }
        
        if ($file['size'] > $this->maxPdfSize) {
            return ['success' => false, 'error' => 'El PDF no debe superar los 10MB'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'descriptor_' . $cursoId . '_' . time() . '.' . $extension;
        $destination = $this->uploadDir . 'descriptores/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => 'uploads/cursos/descriptores/' . $filename];
        }
        
        return ['success' => false, 'error' => 'Error al guardar el archivo'];
    }
    
    /**
     * Eliminar archivo
     */
    public function deleteFile($filepath) {
        if (empty($filepath)) {
            return true;
        }
        
        $fullPath = __DIR__ . '/../' . $filepath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return true;
    }
    
    /**
     * Obtener URL de imagen por defecto
     */
    public static function getDefaultImage() {
        return BASE_URL . '/assets/img/course-default.png';
    }
}
?>