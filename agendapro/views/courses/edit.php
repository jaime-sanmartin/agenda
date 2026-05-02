<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Editar Curso
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/courses/edit/<?php echo $course['id']; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($course['nombre']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripci&oacute;n</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($course['descripcion'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="modalidad" class="form-label">Modalidad <span class="text-danger">*</span></label>
                                        <select class="form-select" id="modalidad" name="modalidad" required>
                                            <option value="online" <?php echo ($course['modalidad'] == 'online') ? 'selected' : ''; ?>>Online</option>
                                            <option value="presencial" <?php echo ($course['modalidad'] == 'presencial') ? 'selected' : ''; ?>>Presencial</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="duracion_horas" class="form-label">Duraci&oacute;n (horas) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="duracion_horas" name="duracion_horas" 
                                               value="<?php echo $course['duracion_horas']; ?>" min="1" required>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" id="publico" name="publico" value="1" <?php echo ($course['publico'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="publico">Curso P&uacute;blico</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" <?php echo ($course['activo'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="activo">Activo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-image"></i> Imagen del Curso
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="imagenPreview" class="mb-3">
                                            <?php if (!empty($course['imagen'])): ?>
                                                <img src="<?php echo BASE_URL . '/' . $course['imagen']; ?>" 
                                                     class="img-fluid rounded" style="max-height: 150px; width: auto;">
                                            <?php else: ?>
                                                <img src="<?php echo BASE_URL; ?>/assets/img/course-default.png" 
                                                     class="img-fluid rounded" style="max-height: 150px; width: auto;">
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (Max 2MB)</small>
                                        <?php if (!empty($course['imagen'])): ?>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="eliminar_imagen" name="eliminar_imagen" value="1">
                                                <label class="form-check-label text-danger" for="eliminar_imagen">
                                                    <i class="fas fa-trash"></i> Eliminar imagen actual
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mt-3">
                                    <div class="card-header">
                                        <i class="fas fa-file-pdf"></i> Descriptor / Temario (PDF)
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($course['descriptor_pdf'])): ?>
                                            <div class="mb-2">
                                                <a href="<?php echo BASE_URL . '/' . $course['descriptor_pdf']; ?>" target="_blank" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-file-pdf"></i> Ver PDF actual
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="descriptor_pdf" name="descriptor_pdf" accept="application/pdf">
                                        <small class="text-muted">PDF del descriptor o temario (Max 10MB)</small>
                                        <?php if (!empty($course['descriptor_pdf'])): ?>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="eliminar_pdf" name="eliminar_pdf" value="1">
                                                <label class="form-check-label text-danger" for="eliminar_pdf">
                                                    <i class="fas fa-trash"></i> Eliminar PDF actual
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Previsualizaci¨®n de imagen
document.getElementById('imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagenPreview').innerHTML = 
                '<img src="' + event.target.result + '" class="img-fluid rounded" style="max-height: 150px; width: auto;">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>