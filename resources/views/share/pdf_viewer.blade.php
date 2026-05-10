<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Lihat PDF - {{ $fileName }}</title>
    <style>
        * {
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #525659;
            font-family: sans-serif;
        }
        .pdf-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            gap: 20px;
        }
        .page-canvas {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            background: white;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .warning {
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(0,0,0,0.6);
            color: #ffc107;
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 20px;
            pointer-events: none;
            z-index: 9999;
            backdrop-filter: blur(4px);
        }
        @media (max-width: 640px) {
            .pdf-container {
                padding: 10px;
                gap: 12px;
            }
            .warning {
                font-size: 10px;
                bottom: 5px;
                left: 5px;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const token = '{{ $token }}';
        const fileId = {{ $fileId }};
        const pdfUrl = `/share/${token}/pdf/${fileId}/data`;

        let pdfDoc = null;
        let container = null;

        window.onload = function() {
            container = document.getElementById('pdf-container');
            loadPdf();
        };

        async function loadPdf() {
            try {
                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                pdfDoc = await loadingTask.promise;
                renderAllPages();
            } catch (error) {
                console.error('Gagal memuat PDF:', error);
                container.innerHTML = '<div style="color:red;text-align:center;padding:50px;">Gagal memuat dokumen. File mungkin rusak atau tidak valid.</div>';
            }
        }

        async function renderAllPages() {
            const numPages = pdfDoc.numPages;
            const containerWidth = Math.min(window.innerWidth - 40, 1000); // maksimal lebar 1000px, tapi responsif
            let scale = 1;

            // Hitung scale agar lebar halaman sesuai container
            // Kita ambil halaman pertama dulu untuk tahu lebar asli
            const firstPage = await pdfDoc.getPage(1);
            const viewport = firstPage.getViewport({ scale: 1 });
            scale = containerWidth / viewport.width;
            // Batasi scale agar tidak terlalu besar (maks 2) dan tidak terlalu kecil (min 0.5)
            scale = Math.min(2, Math.max(0.5, scale));

            for (let i = 1; i <= numPages; i++) {
                const page = await pdfDoc.getPage(i);
                const scaledViewport = page.getViewport({ scale: scale });
                const canvas = document.createElement('canvas');
                canvas.className = 'page-canvas';
                canvas.width = scaledViewport.width;
                canvas.height = scaledViewport.height;
                canvas.style.width = '100%'; // agar responsif di CSS
                canvas.style.height = 'auto';

                const context = canvas.getContext('2d');
                const renderContext = {
                    canvasContext: context,
                    viewport: scaledViewport,
                };
                await page.render(renderContext).promise;
                container.appendChild(canvas);
            }
        }

        // Blokir klik kanan
        document.addEventListener('contextmenu', (e) => e.preventDefault());

        // Blokir shortcut Ctrl+S, Ctrl+P, Ctrl+Shift+I, Ctrl+U, F12
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey && (e.key === 's' || e.key === 'p' || e.key === 'u' || e.key === 'S' || e.key === 'P' || e.key === 'U')) ||
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                e.key === 'F12' ||
                e.key === 'PrintScreen') {
                e.preventDefault();
                return false;
            }
            if (e.ctrlKey && e.shiftKey && (e.key === 'S' || e.key === 's')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</head>
<body>
    <div id="pdf-container" class="pdf-container"></div>
    <div class="warning">
        ⚠️ Menyimpan, mencetak, atau mengambil screenshot dari dokumen ini tidak diperbolehkan.
    </div>
</body>
</html>