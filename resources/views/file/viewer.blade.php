<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Pratinjau {{ $file->original_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            background: #525659;
        }
        #pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
        }
        /* Sembunyikan elemen download/print yang mungkin muncul */
        .pdfViewer .download, 
        .pdfViewer .print,
        .toolbar .download,
        .toolbar .print {
            display: none !important;
        }
        /* Nonaktifkan klik kanan pada canvas (opsional) */
        canvas {
            pointer-events: none; /* user tidak bisa klik kanan untuk save gambar */
        }
    </style>
    <!-- PDF.js core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <!-- PDF.js viewer (dengan UI sederhana) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf_viewer.css" />
    <style>
        /* Override toolbar agar tombol download/print hilang */
        .toolbar {
            display: none !important;
        }
        #viewerContainer {
            top: 0 !important;
        }
    </style>
</head>
<body>
    <div id="pdf-viewer">
        <div id="viewerContainer" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto;">
            <div id="viewer" class="pdfViewer"></div>
        </div>
    </div>

    <script>
        // URL PDF (dari stream atau langsung)
        const pdfUrl = "{{ $viewerUrl }}";
        
        // Konfigurasi PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        
        // Nonaktifkan download/print dengan menghapus event listener
        const container = document.getElementById('viewerContainer');
        const viewer = document.getElementById('viewer');
        
        // Load PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc) {
            const numPages = pdfDoc.numPages;
            const viewerDiv = viewer;
            
            // Fungsi render halaman
            function renderPage(pageNum) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });
                    
                    // Buat canvas untuk halaman
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    
                    page.render(renderContext).promise.then(function() {
                        // Masukkan canvas ke dalam container
                        const pageDiv = document.createElement('div');
                        pageDiv.className = 'page';
                        pageDiv.style.margin = '10px auto';
                        pageDiv.style.boxShadow = '0 0 5px rgba(0,0,0,0.3)';
                        pageDiv.appendChild(canvas);
                        viewerDiv.appendChild(pageDiv);
                    });
                });
            }
            
            for (let i = 1; i <= numPages; i++) {
                renderPage(i);
            }
        }).catch(function(error) {
            console.error('Gagal memuat PDF:', error);
            document.body.innerHTML = '<div style="text-align:center; padding:50px; color:red;">Gagal memuat file PDF.</div>';
        });
        
        // Nonaktifkan klik kanan (opsional)
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Nonaktifkan drag and drop file
        window.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
    </script>
</body>
</html>