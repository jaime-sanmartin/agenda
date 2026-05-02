<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book-open"></i> Cat&aacute;logo de Cursos Disponibles
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="<?php echo BASE_URL; ?>/courses/public" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Buscar por nombre</label>
                                    <input type="text" class="form-control" name="buscar" 
                                           value="<?php echo htmlspecialchars($filtros['buscar'] ?? ''); ?>"
                                           placeholder="Nombre del curso...">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Modalidad</label>
                                    <select class="form-select" name="modalidad">
                                        <option value="">Todas</option>
                                        <option value="online" <?php echo ($filtros['modalidad'] ?? '') == 'online' ? 'selected' : ''; ?>>Online</option>
                                        <option value="presencial" <?php echo ($filtros['modalidad'] ?? '') == 'presencial' ? 'selected' : ''; ?>>Presencial</option>
                                        <option value="hibrido" <?php echo ($filtros['modalidad'] ?? '') == 'hibrido' ? 'selected' : ''; ?>>Hibrido</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Facilitador</label>
                                    <select class="form-select" name="facilitador_id">
                                        <option value="">Todos</option>
                                        <?php foreach ($facilitadores as $facilitador): ?>
                                        <option value="<?php echo $facilitador['id']; ?>" 
                                            <?php echo ($filtros['facilitador_id'] ?? '') == $facilitador['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($facilitador['nombre']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabla de resultados -->
                    <div class="table-responsive">
                        <?php if (empty($cursos)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-4x text-gray-300 mb-3"></i>
                                <p class="text-muted">No se encontraron cursos con los filtros seleccionados.</p>
                                <a href="<?php echo BASE_URL; ?>/courses/public?clear=1" class="btn btn-secondary">
                                    <i class="fas fa-eraser"></i> Limpiar filtros
                                </a>
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">Imagen</th>
                                        <th width="250">Curso</th>
                                        <th width="170">Facilitador</th>
                                        <th width="80">Duraci&oacute;n</th>
                                        <th width="80">Modalidad</th>
                                        <!-- 
                                        <th width="180">Facilitador</th>
                                        -->
                                        <th width="150">Descriptor</th>
                                        <th width="100">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cursos as $curso): ?>
                                    <tr>
                                        <!-- Columna Imagen -->
                                        <td class="text-center">
                                            <?php 
                                            $imagen_url = '';
                                            if (!empty($curso['imagen'])) {
                                                $imagen_url = BASE_URL . '/' . ltrim($curso['imagen'], '/');
                                            }
                                            ?>
                                            <?php if ($imagen_url): ?>
                                                <img src="<?php echo $imagen_url; ?>" 
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                     onerror="this.src='<?php echo BASE_URL; ?>/assets/img/course-default.png'">
                                            <?php else: ?>
                                                <img src="<?php echo BASE_URL; ?>/assets/img/course-default.png" 
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Columna Curso -->
                                        <td>
                                            <strong><?php echo htmlspecialchars($curso['nombre']); ?></strong>
                                            <?php if (!empty($curso['descripcion'])): ?>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 80)) . (strlen($curso['descripcion']) > 80 ? '...' : ''); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Columna Facilitador -->
                                        <td>
                                            <i class="fas fa-chalkboard-user text-primary"></i>
                                            <?php echo htmlspecialchars($curso['facilitador_nombre'] ?? 'No asignado'); ?>
                                        </div>

                                        <!-- Columna Duración -->
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-clock"></i> <?php echo $curso['duracion_horas']; ?> hrs
                                            </span>
                                        </div>
                                        
                                        <!-- Columna Modalidad -->
                                        <td class="text-center">
                                            <?php if ($curso['modalidad'] == 'online'): ?>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-laptop"></i> Online
                                                </span>
                                            <?php elseif ($curso['modalidad'] == 'presencial'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-building"></i> Presencial
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="fa-solid fa-compass"></i> Hibrido
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Columna Descriptor (PDF) -->
                                        
                                        <td class="text-center">
                                            <?php 
                                            $pdf_url = '';
                                            if (!empty($curso['descriptor_pdf'])) {
                                                $pdf_url = BASE_URL . '/' . ltrim($curso['descriptor_pdf'], '/');
                                            }
                                            ?>
                                            <?php if ($pdf_url): ?>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo $pdf_url; ?>" 
                                                       target="_blank" class="btn btn-sm btn-danger" title="Ver PDF">
                                                        <i class="fas fa-file-pdf"></i> Ver
                                                    </a>
                                                    <a href="<?php echo $pdf_url; ?>" 
                                                       download class="btn btn-sm btn-secondary" title="Descargar PDF">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-times-circle"></i> No disponible
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        
                                        <!-- Columna Acciones -->
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/bookings/create?curso_id=<?php echo $curso['id']; ?>" 
                                               class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-calendar-plus"></i> Solicitar
                                            </a>
                                        </div>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <!-- Contador de resultados -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p class="text-muted text-center">
                                        <i class="fas fa-list"></i> Mostrando <?php echo count($cursos); ?> curso(s)
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}
.img-thumbnail {
    border-radius: 8px;
}
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
.btn-group .btn:first-child {
    border-radius: 4px 0 0 4px;
}
.btn-group .btn:last-child {
    border-radius: 0 4px 4px 0;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>