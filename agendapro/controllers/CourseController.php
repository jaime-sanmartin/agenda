<?php
// controllers/CourseController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Course.php';
require_once 'models/Otec.php';
require_once 'models/Booking.php';
require_once 'models/ActivityLog.php';
require_once 'helpers/Upload.php';

class CourseController extends Controller {
    
    public function __construct() {
        Auth::checkLogin();
    }
    
    public function index() {
        Auth::checkLogin();
        
        $courseModel = new Course();
        
        if (Auth::isAdministrador()) {
            // Administrador: ver TODOS los cursos
            $courses = $courseModel->getAllCoursesAdmin();
        } elseif (Auth::isFacilitador()) {
            // Facilitador: ver SOLO los cursos que creó
            $courses = $courseModel->getByFacilitador($_SESSION['user_id']);
        } else {
            // Ejecutivo: ver solo cursos públicos (catálogo)
            $this->redirect(BASE_URL . '/courses/public');
            return;
        }
        
        $this->view('courses/index', [
            'courses' => $courses, 
            'title' => 'Gestión de Cursos'
        ]);
    }
    
    public function create() {
        Auth::checkRole('facilitador');
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'nombre' => $this->getPost('nombre'),
                'descripcion' => $this->getPost('descripcion'),
                'modalidad' => $this->getPost('modalidad'),
                'duracion_horas' => $this->getPost('duracion_horas'),
                'publico' => $this->getPost('publico', 1),
                'activo' => $this->getPost('activo', 1),
                'created_by' => $_SESSION['user_id']
            ];
            
            $courseModel = new Course();
            
            if (!$courseModel->validateNombre($data['nombre'])) {
                $this->view('courses/create', ['error' => 'Ya existe un curso con este nombre.', 'data' => $data]);
                return;
            }
            
            $id = $courseModel->create($data);
            
            if ($id) {
                // Procesar imagen
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $upload = new Upload();
                    $result = $upload->uploadImage($_FILES['imagen'], $id);
                    if ($result['success']) {
                        $courseModel->update($id, ['imagen' => $result['filename']]);
                    }
                }
                
                // Procesar PDF
                if (isset($_FILES['descriptor_pdf']) && $_FILES['descriptor_pdf']['error'] === UPLOAD_ERR_OK) {
                    $upload = new Upload();
                    $result = $upload->uploadPdf($_FILES['descriptor_pdf'], $id);
                    if ($result['success']) {
                        $courseModel->update($id, ['descriptor_pdf' => $result['filename']]);
                    }
                }
                
                ActivityLog::log($_SESSION['user_id'], 'create_course', 'courses', $id, "Curso creado: {$data['nombre']}");
                $this->redirect(BASE_URL . '/courses?success=created');
            } else {
                $this->view('courses/create', ['error' => 'Error al crear el curso.', 'data' => $data]);
            }
        } else {
            $this->view('courses/create', ['title' => 'Nuevo Curso']);
        }
    }
    
    public function edit($id) {
        Auth::checkRole('facilitador');
        
        $courseModel = new Course();
        $course = $courseModel->find($id);
        
        if (!$course) {
            $this->redirect(BASE_URL . '/courses?error=notfound');
            return;
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'nombre' => $this->getPost('nombre'),
                'descripcion' => $this->getPost('descripcion'),
                'modalidad' => $this->getPost('modalidad'),
                'duracion_horas' => $this->getPost('duracion_horas'),
                'publico' => $this->getPost('publico', 1),
                'activo' => $this->getPost('activo', 1)
            ];
            
            if ($data['nombre'] !== $course['nombre'] && !$courseModel->validateNombre($data['nombre'], $id)) {
                $this->view('courses/edit', ['error' => 'Ya existe un curso con este nombre.', 'course' => $course, 'data' => $data]);
                return;
            }
            
            $upload = new Upload();
            
            // Procesar nueva imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                // Eliminar imagen anterior
                if (!empty($course['imagen'])) {
                    $upload->deleteFile($course['imagen']);
                }
                $result = $upload->uploadImage($_FILES['imagen'], $id);
                if ($result['success']) {
                    $data['imagen'] = $result['filename'];
                }
            }
            
            // Procesar nuevo PDF
            if (isset($_FILES['descriptor_pdf']) && $_FILES['descriptor_pdf']['error'] === UPLOAD_ERR_OK) {
                if (!empty($course['descriptor_pdf'])) {
                    $upload->deleteFile($course['descriptor_pdf']);
                }
                $result = $upload->uploadPdf($_FILES['descriptor_pdf'], $id);
                if ($result['success']) {
                    $data['descriptor_pdf'] = $result['filename'];
                }
            }
            
            // Eliminar imagen si se solicitó
            if ($this->getPost('eliminar_imagen') == '1') {
                if (!empty($course['imagen'])) {
                    $upload->deleteFile($course['imagen']);
                    $data['imagen'] = null;
                }
            }
            
            // Eliminar PDF si se solicitó
            if ($this->getPost('eliminar_pdf') == '1') {
                if (!empty($course['descriptor_pdf'])) {
                    $upload->deleteFile($course['descriptor_pdf']);
                    $data['descriptor_pdf'] = null;
                }
            }
            
            if ($courseModel->update($id, $data)) {
                ActivityLog::log($_SESSION['user_id'], 'update_course', 'courses', $id, "Curso actualizado: {$data['nombre']}");
                $this->redirect(BASE_URL . '/courses?success=updated');
            } else {
                $this->view('courses/edit', ['error' => 'Error al actualizar el curso.', 'course' => $course]);
            }
        } else {
            $this->view('courses/edit', ['course' => $course, 'title' => 'Editar Curso']);
        }
    }
    
    public function details($id) {
        Auth::checkRole('facilitador');
        
        $courseModel = new Course();
        $bookingModel = new Booking();
        
        $course = $courseModel->find($id);
        
        if (!$course) {
            $this->redirect(BASE_URL . '/courses?error=notfound');
            return;
        }
        
        $capacitaciones = $bookingModel->getAllWithDetails(['curso_id' => $id]);
        
        $this->view('courses/view', [
            'course' => $course,
            'capacitaciones' => $capacitaciones,
            'title' => 'Detalles de Curso'
        ]);
    }
    
    public function delete($id) {
        Auth::checkRole('facilitador');
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $courseModel = new Course();
            $bookingModel = new Booking();
            
            $capacitaciones = $bookingModel->getAllWithDetails(['curso_id' => $id]);
            if (!empty($capacitaciones)) {
                $this->redirect(BASE_URL . '/courses?error=has_bookings');
                return;
            }
            
            // Eliminar archivos asociados
            $courseModel->deleteAssociatedFiles($id);
            
            if ($courseModel->delete($id)) {
                ActivityLog::log($_SESSION['user_id'], 'delete_course', 'courses', $id, "Curso eliminado");
                $this->redirect(BASE_URL . '/courses?success=deleted');
            } else {
                $this->redirect(BASE_URL . '/courses?error=delete_failed');
            }
        }
    }
    
    public function toggleStatus($id) {
        Auth::checkRole('facilitador');
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $courseModel = new Course();
            $course = $courseModel->find($id);
            
            if ($course) {
                $newStatus = $course['activo'] == 1 ? 0 : 1;
                $courseModel->update($id, ['activo' => $newStatus]);
                
                ActivityLog::log($_SESSION['user_id'], 'toggle_course_status', 'courses', $id, "Estado cambiado a: " . ($newStatus ? 'activo' : 'inactivo'));
                $this->redirect(BASE_URL . '/courses?success=status_changed');
            }
        }
    }
    
    /**
     * Catálogo público de cursos para ejecutivos
     */
    public function publicCatalog() {
        if (!Auth::isEjecutivo()) {
            $this->redirect(BASE_URL . '/dashboard/ejecutivo?error=unauthorized');
            return;
        }
        
        $user = Auth::user();
        $otecId = $user['otec_id'] ?? $_SESSION['user_otec_id'] ?? null;
        
        if (!$otecId) {
            $this->redirect(BASE_URL . '/dashboard/ejecutivo?error=no_otec');
            return;
        }
        
        $courseModel = new Course();
        
        // Obtener facilitadores para el filtro
        $facilitadores = $courseModel->getFacilitadoresByOtecForCatalog($otecId);
        
        // Procesar filtros
        $filtros = [];
        if ($this->getQuery('facilitador_id')) {
            $filtros['facilitador_id'] = $this->getQuery('facilitador_id');
        }
        if ($this->getQuery('modalidad')) {
            $filtros['modalidad'] = $this->getQuery('modalidad');
        }
        if ($this->getQuery('buscar')) {
            $filtros['buscar'] = $this->getQuery('buscar');
        }
        
        $cursos = $courseModel->getCoursesForEjecutivo($otecId, $filtros);
        
        $this->view('courses/public_catalog', [
            'cursos' => $cursos,
            'facilitadores' => $facilitadores,
            'filtros' => $filtros,
            'title' => 'Catálogo de Cursos'
        ]);
    }
}
?>