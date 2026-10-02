const https = require('https');
const http = require('http');
const fs = require('fs');
const path = require('path');

const HOST = process.env.HTTPS_HOST || '0.0.0.0';
const PORT = parseInt(process.env.HTTPS_PORT || '8443', 10);
const TARGET = process.env.TARGET_URL || 'http://127.0.0.1:8000';
const CERTS_DIR = path.join(__dirname, 'storage', 'certs');

const tls = {
    key: fs.readFileSync(path.join(CERTS_DIR, 'server.key')),
    cert: fs.readFileSync(path.join(CERTS_DIR, 'server.crt')),
};

const server = https.createServer(tls, (req, res) => {
    const proxy = http.request(
        TARGET + req.url,
        { method: req.method, headers: req.headers },
        (pRes) => {
            pRes.headers['access-control-allow-origin'] = '*';
            res.writeHead(pRes.statusCode, pRes.headers);
            pRes.pipe(res);
        }
    );
    proxy.on('error', (err) => {
        if (!res.headersSent) {
            res.writeHead(502, { 'Content-Type': 'text/plain' });
        }
        res.end('Proxy error: pastikan `php artisan serve` berjalan. (' + err.message + ')');
    });
    req.pipe(proxy);
});

server.on('clientError', (err, socket) => {
    socket.end('HTTP/1.1 400 Bad Request\r\n\r\n');
});

server.listen(PORT, HOST, () => {
    console.log('HTTPS proxy aktif: https://' + HOST + ':' + PORT + '  ->  ' + TARGET);
});