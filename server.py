#!/usr/bin/env python3
"""
CManga Local Development Server
Máy chủ phát triển cục bộ cho dự án CManga
"""

import http.server
import socketserver
import os
import sys
import webbrowser
from urllib.parse import urlparse, parse_qs
import json
import threading
import time

class CMangaHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    """Custom HTTP request handler cho CManga"""
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory="/workspace", **kwargs)
    
    def end_headers(self):
        """Thêm CORS headers để tránh lỗi cross-origin"""
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        self.send_header('Cache-Control', 'no-cache, no-store, must-revalidate')
        self.send_header('Pragma', 'no-cache')
        self.send_header('Expires', '0')
        super().end_headers()
    
    def do_GET(self):
        """Xử lý GET requests"""
        parsed_path = urlparse(self.path)
        
        # Route handling cho SPA
        if parsed_path.path == '/':
            self.path = '/index.html'
        elif parsed_path.path == '/game':
            self.path = '/game.html'
        elif parsed_path.path.startswith('/api/'):
            self.handle_api_request(parsed_path)
            return
        elif not '.' in parsed_path.path.split('/')[-1]:
            # Nếu không có extension, redirect về index.html
            self.path = '/index.html'
        
        super().do_GET()
    
    def do_OPTIONS(self):
        """Xử lý OPTIONS requests cho CORS"""
        self.send_response(200)
        self.end_headers()
    
    def handle_api_request(self, parsed_path):
        """Xử lý API requests (mock data)"""
        self.send_response(200)
        self.send_header('Content-type', 'application/json')
        self.end_headers()
        
        # Mock API responses
        api_responses = {
            '/api/user_data': {
                'status': 'success',
                'data': {
                    'username': 'Player1',
                    'level': 25,
                    'experience': 15000,
                    'gold': 50000,
                    'vip_level': 2
                }
            },
            '/api/game_data': {
                'status': 'success',
                'data': {
                    'character': {
                        'hp': 1000,
                        'mp': 500,
                        'attack': 150,
                        'defense': 100,
                        'speed': 80
                    },
                    'equipment': [],
                    'skills': []
                }
            },
            '/api/manga_data': {
                'status': 'success',
                'data': {
                    'featured': [],
                    'latest': [],
                    'popular': []
                }
            }
        }
        
        response = api_responses.get(parsed_path.path, {'status': 'error', 'message': 'API not found'})
        self.wfile.write(json.dumps(response, ensure_ascii=False).encode('utf-8'))
    
    def log_message(self, format, *args):
        """Custom log message với timestamp"""
        timestamp = time.strftime('%Y-%m-%d %H:%M:%S')
        sys.stderr.write(f"[{timestamp}] {format % args}\n")

def start_server(port=8080, auto_open=True):
    """Khởi động server phát triển"""
    
    print(f"""
╔══════════════════════════════════════════════╗
║            🎮 CManga Dev Server              ║
║          Máy chủ phát triển cục bộ           ║
╚══════════════════════════════════════════════╝

🚀 Server đang chạy tại: http://localhost:{port}
📁 Thư mục gốc: /workspace
🔄 Auto-reload: Enabled (restart server khi có thay đổi)
🌐 CORS: Enabled (cho phép cross-origin requests)

📖 Truy cập:
   • Trang chủ: http://localhost:{port}
   • Game RPG: http://localhost:{port}/game
   • API Mock: http://localhost:{port}/api/*

⚡ Để dừng server: Ctrl+C
""")
    
    try:
        with socketserver.TCPServer(("", port), CMangaHTTPRequestHandler) as httpd:
            if auto_open:
                # Mở trình duyệt sau 1 giây
                def open_browser():
                    time.sleep(1)
                    webbrowser.open(f'http://localhost:{port}')
                
                threading.Thread(target=open_browser, daemon=True).start()
            
            print(f"✅ Server sẵn sàng! Nhấn Ctrl+C để dừng.\n")
            httpd.serve_forever()
            
    except KeyboardInterrupt:
        print("\n🛑 Server đã dừng. Cảm ơn bạn đã sử dụng CManga!")
    except OSError as e:
        if e.errno == 48:  # Address already in use
            print(f"❌ Lỗi: Port {port} đã được sử dụng. Thử port khác:")
            print(f"   python3 server.py --port 8081")
        else:
            print(f"❌ Lỗi: {e}")

if __name__ == "__main__":
    import argparse
    
    parser = argparse.ArgumentParser(description='CManga Development Server')
    parser.add_argument('--port', '-p', type=int, default=8080, 
                       help='Port để chạy server (mặc định: 8080)')
    parser.add_argument('--no-browser', action='store_true',
                       help='Không tự động mở trình duyệt')
    
    args = parser.parse_args()
    
    start_server(port=args.port, auto_open=not args.no_browser)