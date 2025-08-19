#!/usr/bin/env python3
"""
CManga Development Tools
Công cụ phát triển cho dự án CManga
"""

import os
import sys
import json
import time
import subprocess
import webbrowser
from pathlib import Path
import argparse

class CMangaDevTools:
    def __init__(self):
        self.project_root = Path("/workspace")
        self.assets_dir = self.project_root / "assets"
        
    def check_dependencies(self):
        """Kiểm tra các dependencies cần thiết"""
        print("🔍 Kiểm tra dependencies...")
        
        # Kiểm tra Python
        python_version = sys.version_info
        if python_version >= (3, 6):
            print(f"✅ Python {python_version.major}.{python_version.minor}.{python_version.micro}")
        else:
            print("❌ Python 3.6+ required")
            return False
            
        # Kiểm tra Node.js (optional)
        try:
            result = subprocess.run(['node', '--version'], capture_output=True, text=True)
            if result.returncode == 0:
                print(f"✅ Node.js {result.stdout.strip()}")
                self.has_node = True
            else:
                print("⚠️  Node.js không có sẵn (tùy chọn)")
                self.has_node = False
        except FileNotFoundError:
            print("⚠️  Node.js không có sẵn (tùy chọn)")
            self.has_node = False
            
        # Kiểm tra cấu trúc thư mục
        required_dirs = ['assets', 'assets/css', 'assets/js']
        for dir_path in required_dirs:
            full_path = self.project_root / dir_path
            if full_path.exists():
                print(f"✅ Thư mục {dir_path}")
            else:
                print(f"❌ Thiếu thư mục {dir_path}")
                full_path.mkdir(parents=True, exist_ok=True)
                print(f"🔧 Đã tạo thư mục {dir_path}")
                
        # Kiểm tra files chính
        required_files = ['index.html', 'assets/css/main.css', 'assets/js/main.js']
        for file_path in required_files:
            full_path = self.project_root / file_path
            if full_path.exists():
                print(f"✅ File {file_path}")
            else:
                print(f"⚠️  Thiếu file {file_path}")
                
        return True
        
    def create_missing_assets(self):
        """Tạo các assets còn thiếu"""
        print("\n🔧 Tạo assets còn thiếu...")
        
        # Tạo favicon placeholder nếu chưa có
        favicon_path = self.assets_dir / "images" / "favicon.ico"
        if not favicon_path.exists():
            favicon_path.parent.mkdir(parents=True, exist_ok=True)
            # Tạo favicon đơn giản (base64 encoded)
            favicon_data = b'\\x00\\x00\\x01\\x00\\x01\\x00\\x10\\x10\\x00\\x00\\x01\\x00\\x08\\x00h\\x05\\x00\\x00\\x16\\x00\\x00\\x00(\\x00\\x00\\x00\\x10\\x00\\x00\\x00 \\x00\\x00\\x00\\x01\\x00\\x08\\x00\\x00\\x00\\x00\\x00@\\x05\\x00\\x00\\x00\\x00\\x00\\x00\\x00\\x00\\x00\\x00\\x00\\x01\\x00\\x00\\x00\\x01\\x00\\x00'
            with open(favicon_path, 'wb') as f:
                f.write(favicon_data)
            print(f"✅ Đã tạo {favicon_path}")
            
        # Tạo logo placeholder nếu chưa có
        logo_path = self.assets_dir / "images" / "logo.png"
        if not logo_path.exists():
            print(f"⚠️  Thiếu logo: {logo_path}")
            print("   Bạn có thể thêm logo vào thư mục assets/images/")
            
    def optimize_assets(self):
        """Tối ưu hóa assets"""
        print("\n⚡ Tối ưu hóa assets...")
        
        # Kiểm tra và tối ưu CSS
        css_files = list((self.assets_dir / "css").glob("*.css"))
        for css_file in css_files:
            size_before = css_file.stat().st_size
            print(f"📄 {css_file.name}: {size_before:,} bytes")
            
        # Kiểm tra và tối ưu JS
        js_files = list((self.assets_dir / "js").glob("*.js"))
        for js_file in js_files:
            size_before = js_file.stat().st_size
            print(f"📄 {js_file.name}: {size_before:,} bytes")
            
    def validate_html(self):
        """Validate HTML files"""
        print("\n🔍 Kiểm tra HTML files...")
        
        html_files = list(self.project_root.glob("*.html"))
        for html_file in html_files:
            print(f"📄 Kiểm tra {html_file.name}...")
            
            with open(html_file, 'r', encoding='utf-8') as f:
                content = f.read()
                
            # Kiểm tra các thẻ cơ bản
            checks = [
                ('DOCTYPE', '<!DOCTYPE html>' in content),
                ('HTML lang', 'lang="vi"' in content or 'lang=' in content),
                ('Meta charset', 'charset="UTF-8"' in content),
                ('Meta viewport', 'viewport' in content),
                ('Title tag', '<title>' in content),
            ]
            
            for check_name, passed in checks:
                status = "✅" if passed else "⚠️ "
                print(f"  {status} {check_name}")
                
    def generate_sitemap(self):
        """Tạo sitemap cho SEO"""
        print("\n🗺️  Tạo sitemap...")
        
        urls = [
            {'loc': '/', 'priority': '1.0', 'changefreq': 'daily'},
            {'loc': '/game', 'priority': '0.9', 'changefreq': 'weekly'},
        ]
        
        sitemap_content = '''<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
'''
        
        for url in urls:
            sitemap_content += f'''  <url>
    <loc>http://localhost:8080{url['loc']}</loc>
    <priority>{url['priority']}</priority>
    <changefreq>{url['changefreq']}</changefreq>
  </url>
'''
        
        sitemap_content += '</urlset>'
        
        sitemap_path = self.project_root / "sitemap.xml"
        with open(sitemap_path, 'w', encoding='utf-8') as f:
            f.write(sitemap_content)
            
        print(f"✅ Đã tạo sitemap: {sitemap_path}")
        
    def create_robots_txt(self):
        """Tạo robots.txt"""
        robots_content = """User-agent: *
Allow: /

Sitemap: http://localhost:8080/sitemap.xml
"""
        
        robots_path = self.project_root / "robots.txt"
        with open(robots_path, 'w', encoding='utf-8') as f:
            f.write(robots_content)
            
        print(f"✅ Đã tạo robots.txt: {robots_path}")
        
    def performance_check(self):
        """Kiểm tra performance"""
        print("\n⚡ Kiểm tra performance...")
        
        # Kiểm tra kích thước files
        total_size = 0
        file_sizes = {}
        
        for file_path in self.project_root.rglob("*"):
            if file_path.is_file() and not file_path.name.startswith('.'):
                size = file_path.stat().st_size
                total_size += size
                
                ext = file_path.suffix.lower()
                if ext in file_sizes:
                    file_sizes[ext] += size
                else:
                    file_sizes[ext] = size
                    
        print(f"📊 Tổng kích thước dự án: {total_size:,} bytes ({total_size/1024/1024:.2f} MB)")
        
        for ext, size in sorted(file_sizes.items(), key=lambda x: x[1], reverse=True):
            if ext:
                print(f"   {ext}: {size:,} bytes ({size/1024:.1f} KB)")
                
        # Gợi ý tối ưu hóa
        print("\n💡 Gợi ý tối ưu hóa:")
        if file_sizes.get('.css', 0) > 100000:  # > 100KB
            print("   • Nén CSS files")
        if file_sizes.get('.js', 0) > 200000:   # > 200KB
            print("   • Minify JavaScript files")
        if file_sizes.get('.png', 0) + file_sizes.get('.jpg', 0) > 1000000:  # > 1MB
            print("   • Tối ưu hóa hình ảnh")
            
    def start_dev_server(self, port=8080):
        """Khởi động development server"""
        print(f"\n🚀 Khởi động development server trên port {port}...")
        
        try:
            # Sử dụng server.py nếu có
            server_py = self.project_root / "server.py"
            if server_py.exists():
                subprocess.run([sys.executable, str(server_py), '--port', str(port)])
            else:
                # Fallback to simple HTTP server
                os.chdir(self.project_root)
                subprocess.run([sys.executable, '-m', 'http.server', str(port)])
                
        except KeyboardInterrupt:
            print("\n🛑 Server đã dừng.")
            
    def run_all_checks(self):
        """Chạy tất cả các kiểm tra"""
        print("🔍 Chạy tất cả các kiểm tra...\n")
        
        if not self.check_dependencies():
            return False
            
        self.create_missing_assets()
        self.validate_html()
        self.generate_sitemap()
        self.create_robots_txt()
        self.performance_check()
        
        print("\n✅ Tất cả kiểm tra hoàn tất!")
        return True

def main():
    parser = argparse.ArgumentParser(description='CManga Development Tools')
    parser.add_argument('command', nargs='?', default='check',
                       choices=['check', 'serve', 'optimize', 'validate', 'all'],
                       help='Lệnh cần thực hiện')
    parser.add_argument('--port', '-p', type=int, default=8080,
                       help='Port cho dev server')
    
    args = parser.parse_args()
    
    tools = CMangaDevTools()
    
    if args.command == 'check':
        tools.check_dependencies()
    elif args.command == 'serve':
        tools.start_dev_server(args.port)
    elif args.command == 'optimize':
        tools.optimize_assets()
    elif args.command == 'validate':
        tools.validate_html()
    elif args.command == 'all':
        tools.run_all_checks()

if __name__ == "__main__":
    main()