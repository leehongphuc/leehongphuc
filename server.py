#!/usr/bin/env python3
"""
Simple HTTP Server để chạy CManga project offline
Không cần database - sử dụng mock data
"""

import http.server
import socketserver
import os
import json
from urllib.parse import urlparse, parse_qs
import mimetypes

class CMangaHandler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory="/workspace", **kwargs)
    
    def do_GET(self):
        # Parse URL
        parsed_path = urlparse(self.path)
        path = parsed_path.path
        
        print(f"Request: {path}")
        
        # Handle API requests với mock data
        if path.startswith('/cmangax3.com/api/'):
            self.handle_api_request(path, parsed_path.query)
            return
        
        # Handle root path - redirect to dashboard
        if path == '/' or path == '':
            self.send_response(302)
            self.send_header('Location', '/cmangax3.com/user/game/dashboard.html')
            self.end_headers()
            return
            
        # Handle static files
        try:
            # Remove leading slash and serve from workspace
            if path.startswith('/'):
                path = path[1:]
            
            file_path = os.path.join("/workspace", path)
            
            if os.path.exists(file_path) and os.path.isfile(file_path):
                # Determine content type
                content_type, _ = mimetypes.guess_type(file_path)
                if content_type is None:
                    if path.endswith('.js'):
                        content_type = 'application/javascript'
                    elif path.endswith('.css'):
                        content_type = 'text/css'
                    elif path.endswith('.html'):
                        content_type = 'text/html'
                    else:
                        content_type = 'application/octet-stream'
                
                self.send_response(200)
                self.send_header('Content-Type', content_type)
                self.send_header('Cache-Control', 'no-cache')
                self.end_headers()
                
                with open(file_path, 'rb') as f:
                    self.wfile.write(f.read())
            else:
                self.send_error(404, f"File not found: {path}")
                
        except Exception as e:
            print(f"Error serving {path}: {e}")
            self.send_error(500, f"Server error: {str(e)}")
    
    def handle_api_request(self, path, query_string):
        """Handle API requests với mock data"""
        
        # Mock API responses
        mock_responses = {
            '/cmangax3.com/api/get_stories': {
                'status': 'success',
                'data': [
                    {
                        'id': 1,
                        'title': 'One Piece',
                        'description': 'Câu chuyện về Luffy và băng hải tặc Mũ Rơm',
                        'author': 'Oda Eiichiro',
                        'status': 'ongoing',
                        'cover': '/assets/img/stories/onepiece.jpg',
                        'chapters_count': 1000
                    },
                    {
                        'id': 2,
                        'title': 'Naruto',
                        'description': 'Câu chuyện về ninja Naruto Uzumaki',
                        'author': 'Kishimoto Masashi',
                        'status': 'completed',
                        'cover': '/assets/img/stories/naruto.jpg',
                        'chapters_count': 700
                    }
                ]
            },
            '/cmangax3.com/api/get_user_info': {
                'status': 'success',
                'data': {
                    'id': 31443,
                    'username': 'demo_user',
                    'email': 'demo@cmanga.com',
                    'level': 25,
                    'exp': 1500,
                    'coins': 10000,
                    'character': {
                        'id': 6027,
                        'name': 'Character Demo',
                        'level': 25,
                        'exp': 1500
                    }
                }
            }
        }
        
        # Check if we have a mock response for this path
        response_data = mock_responses.get(path, {
            'status': 'success',
            'message': 'Mock API response',
            'data': {}
        })
        
        # Send JSON response
        self.send_response(200)
        self.send_header('Content-Type', 'application/json')
        self.send_header('Access-Control-Allow-Origin', '*')
        self.end_headers()
        
        response_json = json.dumps(response_data, ensure_ascii=False, indent=2)
        self.wfile.write(response_json.encode('utf-8'))
    
    def log_message(self, format, *args):
        """Override để giảm spam log"""
        pass

def run_server(port=8000):
    """Chạy server"""
    handler = CMangaHandler
    
    try:
        with socketserver.TCPServer(("", port), handler) as httpd:
            print(f"🚀 CManga Server đang chạy tại: http://localhost:{port}")
            print(f"📱 Truy cập ứng dụng: http://localhost:{port}/cmangax3.com/user/game/dashboard.html")
            print(f"🛑 Nhấn Ctrl+C để dừng server")
            print("="*60)
            httpd.serve_forever()
    except KeyboardInterrupt:
        print("\n🛑 Server đã dừng!")
    except OSError as e:
        if "Address already in use" in str(e):
            print(f"❌ Port {port} đã được sử dụng. Thử port khác...")
            run_server(port + 1)
        else:
            print(f"❌ Lỗi: {e}")

if __name__ == "__main__":
    run_server()