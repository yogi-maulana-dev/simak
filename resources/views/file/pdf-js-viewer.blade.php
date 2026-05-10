<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} - Baca PDF</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body, html { height:100%; background:#525659; overflow:hidden; }
        #pdf-viewer { width:100%; height:100%; position:relative; }
        #viewerContainer { position:absolute; top:0; bottom:0; left:0; right:0; overflow:auto; }
        .page { margin:10px auto; box-shadow:0 0 5px rgba(0,0,0,0.3); }
        canvas { display:block; width:100%; height:auto; }
        /* Sembunyikan semua aksi download */
        .download, .print, .toolbar {
            display:none !important;
        }
        /* Nonaktifkan klik kanan dan seleksi */
        body {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        const pdfUrl = "{{ $viewerUrl }}";

        window.onload = function() {
            const container = document.getElementById('viewerContainer');
            const viewerDiv = document.getElementById('viewer');

            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc) {
                const numPages = pdfDoc.numPages;
                for (let i = 1; i <= numPages; i++) {
                    pdfDoc.getPage(i).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        const pageDiv = document.createElement('div');
                        pageDiv.className = 'page';
                        pageDiv.appendChild(canvas);
                        viewerDiv.appendChild(pageDiv);
                        page.render({ canvasContext: context, viewport: viewport });
                    });
                }
            }).catch(function(error) {
                console.error(error);
                document.body.innerHTML = '<div style="color:red; text-align:center; padding:50px;">Gagal memuat PDF</div>';
            });
        };

        // Blok klik kanan
        document.addEventListener('contextmenu', (e) => e.preventDefault());
        // Blok Ctrl+S, Ctrl+P, Ctrl+Shift+I
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey && (e.key === 's' || e.key === 'p')) || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</head>
<body>
    <div id="pdf-viewer">
        <div id="viewerContainer">
            <div id="viewer"></div>
        </div>
    </div>
</body>
</html>