<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Dokumen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            user-select: none;
            -webkit-user-select: none;
        }
        #toolbar {
            width: 100%;
            background: #1a1a1a;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        #toolbar button {
            background: #444;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            cursor: pointer;
        }
        #toolbar button:hover { background: #666; }
        #toolbar span { font-size: 14px; }
        #canvas-container {
            padding: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        canvas {
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            max-width: 100%;
            background: white;
        }
        #loading {
            color: #aaa;
            margin-top: 40px;
            font-family: sans-serif;
        }
    </style>
</head>
<body>

    <div id="toolbar">
        <button onclick="changePage(-1)">&#8592; Prev</button>
        <span id="page-info">Halaman 1</span>
        <button onclick="changePage(1)">Next &#8594;</button>
        <span style="margin-left:auto; font-size:13px; color:#aaa;">🔒 View Only</span>
    </div>

    <div id="canvas-container">
        <div id="loading">Memuat dokumen...</div>
    </div>

    {{-- PDF.js dari CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.2.67/pdf.min.mjs" type="module"></script>

  <script type="module">
    import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.2.67/pdf.min.mjs';

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.2.67/pdf.worker.min.mjs';

    const pdfUrl = "{{ route('share.stream', ['token' => $token, 'fileId' => $fileId]) }}";
    const container = document.getElementById('canvas-container');
    const pageInfo  = document.getElementById('page-info');
    const loading   = document.getElementById('loading');

    let pdfDoc      = null;
    let currentPage = 1;
    let totalPages  = 0;
    let rendering   = false;

    // Fetch manual dengan credentials
    fetch(pdfUrl, {
        method: 'GET',
        credentials: 'same-origin',
        headers: { 'Accept': 'application/pdf' }
    })
    .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        return res.arrayBuffer();
    })
    .then(buffer => {
        return pdfjsLib.getDocument({ data: buffer }).promise;
    })
    .then(pdf => {
        pdfDoc     = pdf;
        totalPages = pdf.numPages;
        loading.remove();
        renderPage(1);
    })
    .catch(err => {
        loading.textContent = `Gagal: ${err.message}`;
        console.error('PDF load error:', err);
    });

    function renderPage(num) {
        if (rendering) return;
        rendering = true;

        pdfDoc.getPage(num).then(page => {
            const scale    = window.innerWidth < 768 ? 1.2 : 1.5;
            const viewport = page.getViewport({ scale });

            container.querySelectorAll('canvas').forEach(c => c.remove());

            const canvas  = document.createElement('canvas');
            canvas.height = viewport.height;
            canvas.width  = viewport.width;
            container.appendChild(canvas);

            page.render({
                canvasContext: canvas.getContext('2d'),
                viewport
            }).promise.then(() => {
                rendering = false;
                pageInfo.textContent = `Halaman ${num} / ${totalPages}`;
            });
        });
    }

    window.changePage = function(delta) {
        const next = currentPage + delta;
        if (next < 1 || next > totalPages) return;
        currentPage = next;
        renderPage(currentPage);
        window.scrollTo(0, 0);
    };

    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && ['s','p','u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>