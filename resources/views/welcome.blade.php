@extends('layouts.index')

@section('content')

<div class="comunicados-container">
   

    <div class="comunicados-grid">
        @foreach($paginas as $pagina)
        <div class="comunicado-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <div class="card-badge">
                <i class="fas fa-star"></i> Nuevo
            </div>
            <div class="card-image" onclick="openImageModal('{{ asset('imagen/'.$pagina->imagen) }}', '{{ $pagina->nombre }}')">
                <img src="{{ asset('imagen/'.$pagina->imagen) }}" alt="{{ $pagina->nombre }}">
                <div class="image-overlay">
                    <div class="zoom-icon">
                        <i class="fas fa-search-plus"></i>
                        <span>Ampliar imagen</span>
                    </div>
                </div>
                <div class="image-actions">
                    <button class="action-btn" onclick="event.stopPropagation(); openImageModal('{{ asset('imagen/'.$pagina->imagen) }}', '{{ $pagina->nombre }}')">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-content">
                <div class="card-category">
                    <span class="category-icon"><i class="fas fa-bullhorn"></i></span>
                    <span class="category-text">Comunicado Oficial</span>
                </div>
                <h3 class="card-title">{{ $pagina->nombre }}</h3>
                <p class="card-excerpt">{{ Str::limit($pagina->descripcion, 80) }}</p>
                <div class="card-meta">
                    <div class="meta-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $pagina->created_at ? $pagina->created_at->format('d/m/Y') : 'Reciente' }}</span>
                    </div>
                    <div class="meta-read" onclick="openModal({{ $pagina->id }})">
                        <span>Leer más</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($paginas->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3>No hay comunicados disponibles</h3>
        <p>Pronto publicaremos novedades importantes para ti.</p>
    </div>
    @endif
</div>

<!-- Modal para Ver Imagen Ampliada -->
<div id="imageViewerModal" class="image-viewer-modal">
    <div class="image-viewer-overlay" onclick="closeImageViewer()"></div>
    <div class="image-viewer-container">
        <button class="image-viewer-close" onclick="closeImageViewer()">
            <i class="fas fa-times"></i>
        </button>
        <button class="image-viewer-nav prev" onclick="changeImage(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="image-viewer-content">
            <img id="viewerImage" src="" alt="Imagen ampliada">
            <div class="image-viewer-info">
                <h3 id="imageTitle"></h3>
                <div class="image-viewer-actions">
                    <button onclick="downloadImage()" class="action-download">
                        <i class="fas fa-download"></i> Descargar
                    </button>
                    <button onclick="shareImage()" class="action-share">
                        <i class="fas fa-share-alt"></i> Compartir
                    </button>
                </div>
            </div>
        </div>
        <button class="image-viewer-nav next" onclick="changeImage(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<!-- Modales para cada comunicado -->
@foreach($paginas as $pagina)
<div id="modal-{{ $pagina->id }}" class="custom-modal">
    <div class="modal-overlay" onclick="closeModal({{ $pagina->id }})"></div>
    <div class="modal-container">
        <div class="modal-close" onclick="closeModal({{ $pagina->id }})">
            <i class="fas fa-times"></i>
        </div>
        <div class="modal-inner">
            <div class="modal-image" onclick="openImageModal('{{ asset('imagen/'.$pagina->imagen) }}', '{{ $pagina->nombre }}')">
                <img src="{{ asset('imagen/'.$pagina->imagen) }}" alt="{{ $pagina->nombre }}">
                <div class="modal-image-overlay">
                    <i class="fas fa-search-plus"></i>
                    <span>Haz clic para ampliar</span>
                </div>
                <div class="modal-category">
                    <i class="fas fa-bullhorn"></i> Comunicado Oficial
                </div>
            </div>
            <div class="modal-body-custom">
                <div class="modal-header-custom">
                    <h2>{{ $pagina->nombre }}</h2>
                    <div class="modal-date">
                        <i class="far fa-calendar-alt"></i>
                        {{ $pagina->created_at ? $pagina->created_at->format('d F Y') : 'Fecha no disponible' }}
                    </div>
                </div>
                <div class="modal-description-custom">
                    <p>{{ $pagina->descripcion }}</p>
                </div>
                <div class="modal-footer-custom">
                    <button class="btn-share" onclick="shareComunicado('{{ $pagina->nombre }}')">
                        <i class="fas fa-share-alt"></i> Compartir
                    </button>
                    <button class="btn-close-modal" onclick="closeModal({{ $pagina->id }})">
                        <i class="fas fa-check"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Contenedor principal */
    .comunicados-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Encabezado de sección */
    .section-header {
        margin-bottom: 3rem;
    }

    .section-badge {
        display: inline-block;
        background: rgba(192, 57, 43, 0.15);
        color: #E74C3C;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .section-subtitle {
        color: rgba(255,255,255,0.7);
        font-size: 1.1rem;
    }

    /* Grid de tarjetas */
    .comunicados-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
    }

    /* Tarjeta individual */
    .comunicado-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .comunicado-card:hover {
        transform: translateY(-12px);
        background: rgba(255,255,255,0.08);
        border-color: rgba(26, 82, 118, 0.4);
        box-shadow: 0 30px 50px rgba(0,0,0,0.3);
    }

    /* Badge de la tarjeta */
    .card-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #C0392B 0%, #921E13 100%);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        box-shadow: 0 5px 15px rgba(192,57,43,0.35);
    }

    /* Imagen de la tarjeta */
    .card-image {
        position: relative;
        height: 240px;
        overflow: hidden;
        cursor: pointer;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .comunicado-card:hover .card-image img {
        transform: scale(1.1);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .card-image:hover .image-overlay {
        opacity: 1;
    }

    .zoom-icon {
        text-align: center;
        color: white;
    }

    .zoom-icon i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .zoom-icon span {
        font-size: 0.8rem;
    }

    .image-actions {
        position: absolute;
        bottom: 15px;
        right: 15px;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 5;
    }

    .card-image:hover .image-actions {
        opacity: 1;
    }

    .action-btn {
        background: rgba(0,0,0,0.7);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background: #1A5276;
        transform: scale(1.1);
    }

    /* Contenido de la tarjeta */
    .card-content {
        padding: 1.5rem;
    }

    .card-category {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
    }

    .category-icon {
        background: rgba(26,82,118,0.2);
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #5DADE2;
        font-size: 0.8rem;
    }

    .category-text {
        color: rgba(255,255,255,0.6);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.8rem;
        line-height: 1.4;
    }

    .card-excerpt {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.2rem;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        cursor: pointer;
    }

    .meta-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255,255,255,0.5);
        font-size: 0.8rem;
    }

    .meta-read {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #5DADE2;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .meta-read:hover {
        gap: 0.8rem;
    }

    /* Modal de imagen ampliada */
    .image-viewer-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        animation: fadeIn 0.3s ease;
    }

    .image-viewer-modal.active {
        display: block;
    }

    .image-viewer-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.95);
        backdrop-filter: blur(20px);
    }

    .image-viewer-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 1200px;
        max-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10001;
    }

    .image-viewer-content {
        flex: 1;
        text-align: center;
        padding: 0 20px;
    }

    .image-viewer-content img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        animation: zoomIn 0.3s ease;
    }

    .image-viewer-info {
        margin-top: 1.5rem;
        color: white;
    }

    .image-viewer-info h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .image-viewer-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .action-download, .action-share {
        background: rgba(255,255,255,0.1);
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .action-download:hover, .action-share:hover {
        background: #1A5276;
        transform: scale(1.05);
    }

    .image-viewer-nav {
        background: rgba(255,255,255,0.2);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .image-viewer-nav:hover {
        background: #1A5276;
        transform: scale(1.1);
    }

    .image-viewer-close {
        position: absolute;
        top: 20px;
        right: 30px;
        background: rgba(255,255,255,0.2);
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10002;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .image-viewer-close:hover {
        background: #e74c3c;
        transform: rotate(90deg);
    }

    /* Modal de comunicado */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .custom-modal.active {
        display: block;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(15px);
    }

    .modal-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 1000px;
        max-height: 90vh;
        overflow-y: auto;
        background: linear-gradient(145deg, #0D1B26 0%, #112233 100%);
        border-radius: 32px;
        animation: slideUp 0.4s ease;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 25px;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .modal-close:hover {
        background: #e74c3c;
        transform: rotate(90deg);
    }

    .modal-image {
        position: relative;
        height: 350px;
        overflow: hidden;
        cursor: pointer;
    }

    .modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .modal-image:hover img {
        transform: scale(1.05);
    }

    .modal-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        opacity: 0;
        transition: all 0.3s ease;
        color: white;
    }

    .modal-image:hover .modal-image-overlay {
        opacity: 1;
    }

    .modal-category {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        color: #5DADE2;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body-custom {
        padding: 2rem;
    }

    .modal-header-custom h2 {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }

    .modal-date {
        color: rgba(255,255,255,0.5);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .modal-description-custom {
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .modal-description-custom p {
        color: rgba(255,255,255,0.85);
        line-height: 1.8;
        font-size: 1rem;
    }

    .modal-footer-custom {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-share {
        background: rgba(255,255,255,0.1);
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-share:hover {
        background: #1A5276;
    }

    .btn-close-modal {
        background: linear-gradient(135deg, #C0392B 0%, #921E13 100%);
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem;
        background: rgba(255,255,255,0.05);
        border-radius: 32px;
        backdrop-filter: blur(10px);
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .comunicados-grid {
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .modal-image {
            height: 250px;
        }

        .modal-header-custom h2 {
            font-size: 1.3rem;
        }

        .image-viewer-nav {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }

        .image-viewer-actions {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<script>
    let currentImages = [];
    let currentImageIndex = 0;

    @foreach($paginas as $pagina)
        currentImages.push({
            url: "{{ asset('imagen/'.$pagina->imagen) }}",
            title: "{{ $pagina->nombre }}"
        });
    @endforeach

    function openImageModal(imageUrl, imageTitle) {
        const modal = document.getElementById('imageViewerModal');
        const viewerImage = document.getElementById('viewerImage');
        const imageTitleElem = document.getElementById('imageTitle');
        
        // Encontrar el índice de la imagen actual
        currentImageIndex = currentImages.findIndex(img => img.url === imageUrl);
        if (currentImageIndex === -1) currentImageIndex = 0;
        
        viewerImage.src = imageUrl;
        imageTitleElem.textContent = imageTitle;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeImageViewer() {
        const modal = document.getElementById('imageViewerModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function changeImage(direction) {
        currentImageIndex += direction;
        if (currentImageIndex < 0) currentImageIndex = currentImages.length - 1;
        if (currentImageIndex >= currentImages.length) currentImageIndex = 0;
        
        const viewerImage = document.getElementById('viewerImage');
        const imageTitleElem = document.getElementById('imageTitle');
        
        viewerImage.style.opacity = '0';
        setTimeout(() => {
            viewerImage.src = currentImages[currentImageIndex].url;
            imageTitleElem.textContent = currentImages[currentImageIndex].title;
            viewerImage.style.opacity = '1';
        }, 200);
    }

    function downloadImage() {
        const link = document.createElement('a');
        link.href = currentImages[currentImageIndex].url;
        link.download = currentImages[currentImageIndex].title || 'imagen';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function shareImage() {
        if (navigator.share) {
            navigator.share({
                title: currentImages[currentImageIndex].title,
                text: 'Mira esta imagen de DeportBeca UMSA',
                url: currentImages[currentImageIndex].url
            }).catch(() => {});
        } else {
            alert('Comparte esta imagen: ' + currentImages[currentImageIndex].title);
        }
    }

    function openModal(id) {
        const modal = document.getElementById('modal-' + id);
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById('modal-' + id);
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function shareComunicado(title) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Mira este comunicado de DeportBeca UMSA',
                url: window.location.href
            }).catch(() => {});
        } else {
            alert('Comparte este comunicado: ' + title);
        }
    }

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const imageModal = document.getElementById('imageViewerModal');
            if (imageModal.classList.contains('active')) {
                closeImageViewer();
            }
            document.querySelectorAll('.custom-modal.active').forEach(modal => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Navegación con flechas en el visor de imágenes
        if (e.key === 'ArrowLeft') {
            const imageModal = document.getElementById('imageViewerModal');
            if (imageModal.classList.contains('active')) {
                changeImage(-1);
            }
        }
        if (e.key === 'ArrowRight') {
            const imageModal = document.getElementById('imageViewerModal');
            if (imageModal.classList.contains('active')) {
                changeImage(1);
            }
        }
    });
</script>

@endsection