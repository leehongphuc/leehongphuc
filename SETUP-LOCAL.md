# 🚀 Hướng dẫn Setup CManga trên máy cục bộ

## 📋 Yêu cầu hệ thống

### Bắt buộc
- **Python 3.6+** (khuyến nghị Python 3.8+)
- **Trình duyệt web** hiện đại (Chrome, Firefox, Safari, Edge)

### Tùy chọn (để có trải nghiệm tốt hơn)
- **Node.js 16+** và **npm 8+** (cho live-reload và build tools)
- **Git** (để clone và quản lý source code)

## ⚡ Setup nhanh (3 bước)

### Bước 1: Kiểm tra Python
```bash
python3 --version
# Hoặc trên Windows:
python --version
```

### Bước 2: Chạy script setup tự động
```bash
# Cách 1: Sử dụng script setup
./start.sh

# Cách 2: Sử dụng Makefile (nếu có make)
make setup

# Cách 3: Thủ công
python3 dev-tools.py all
```

### Bước 3: Khởi động server
```bash
# Cách đơn giản nhất
python3 server.py

# Hoặc sử dụng npm script
npm start

# Hoặc sử dụng Makefile
make start
```

🎉 **Xong!** Mở trình duyệt và truy cập: `http://localhost:8080`

## 🔧 Các cách khởi động khác nhau

### 1. Python Custom Server (Khuyến nghị)
```bash
python3 server.py
# Hoặc với port tùy chỉnh
python3 server.py --port 8081
```

**Ưu điểm:**
- ✅ Có API mock tích hợp
- ✅ CORS được cấu hình sẵn
- ✅ Auto-open browser
- ✅ Logging đầy đủ

### 2. Python Simple HTTP Server
```bash
python3 -m http.server 8080
```

**Ưu điểm:**
- ✅ Không cần cài đặt gì thêm
- ✅ Rất đơn giản và nhanh

**Nhược điểm:**
- ❌ Không có API mock
- ❌ Có thể gặp vấn đề CORS

### 3. Node.js Live Server (Auto-reload)
```bash
# Cài đặt live-server
npm install -g live-server

# Khởi động
live-server --port=8080
```

**Ưu điểm:**
- ✅ Auto-reload khi file thay đổi
- ✅ Inject CSS không cần reload page

### 4. Node.js HTTP Server
```bash
# Cài đặt http-server
npm install -g http-server

# Khởi động
http-server -p 8080 -o
```

## 🛠️ Development Tools

### Kiểm tra dự án
```bash
python3 dev-tools.py check
# Hoặc
npm run check
```

### Validate HTML
```bash
python3 dev-tools.py validate
# Hoặc
npm run validate
```

### Tối ưu hóa assets
```bash
python3 dev-tools.py optimize
# Hoặc
npm run optimize
```

### Setup hoàn chỉnh
```bash
python3 dev-tools.py all
# Hoặc
npm run setup
```

## 📁 Cấu trúc dự án sau khi setup

```
cmanga/
├── index.html              # Trang chủ
├── game.html               # Game RPG
├── server.py               # Custom development server
├── start.sh                # Script khởi động nhanh
├── dev-tools.py            # Công cụ development
├── Makefile                # Make commands
├── .env.local              # Cấu hình môi trường
├── local-config.json       # Cấu hình JSON
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── game.css
│   │   └── components.css  # Tự động tạo
│   ├── js/
│   │   ├── main.js
│   │   ├── game.js
│   │   └── components.js
│   └── images/
└── package.json            # NPM configuration
```

## 🌐 URLs sau khi khởi động

- **Trang chủ**: http://localhost:8080
- **Game RPG**: http://localhost:8080/game
- **API Mock**: http://localhost:8080/api/*

## 🔍 Troubleshooting

### Lỗi: "Port 8080 already in use"
```bash
# Sử dụng port khác
python3 server.py --port 8081

# Hoặc tìm và kill process đang dùng port 8080
lsof -ti:8080 | xargs kill -9
```

### Lỗi: "Python command not found"
```bash
# Thử các lệnh sau:
python --version
python3 --version
py --version

# Trên Windows, có thể cần cài Python từ Microsoft Store
```

### Lỗi: CORS khi gọi API
- ✅ Sử dụng `python3 server.py` thay vì `python3 -m http.server`
- ✅ Custom server đã cấu hình CORS sẵn

### Lỗi: Files CSS/JS không load
- ✅ Kiểm tra đường dẫn trong HTML
- ✅ Đảm bảo server chạy từ thư mục gốc dự án
- ✅ Xóa cache trình duyệt (Ctrl+F5)

### Game không hoạt động
- ✅ Mở Developer Tools (F12) để xem lỗi JavaScript
- ✅ Đảm bảo tất cả files JS được load
- ✅ Kiểm tra API mock đang hoạt động

## 🚀 Tips cho Development

### 1. Sử dụng Browser Developer Tools
- **F12** hoặc **Ctrl+Shift+I** để mở DevTools
- **Console tab** để xem lỗi JavaScript
- **Network tab** để xem API calls
- **Application tab** để xem Local Storage

### 2. Auto-reload khi có thay đổi
```bash
# Sử dụng live-server cho auto-reload
npm install -g live-server
live-server --port=8080
```

### 3. Debug API calls
- Mở Network tab trong DevTools
- API mock responses có thể xem trong `server.py`
- Thêm `console.log()` trong JavaScript để debug

### 4. CSS debugging
- Sử dụng Elements tab để inspect CSS
- Thay đổi CSS trực tiếp trong DevTools để test
- Sử dụng `!important` để override styles

### 5. Performance monitoring
```bash
# Kiểm tra performance
python3 dev-tools.py optimize

# Xem kích thước files
ls -lh assets/css/
ls -lh assets/js/
```

## 📞 Hỗ trợ

Nếu gặp vấn đề:

1. **Kiểm tra lại yêu cầu hệ thống**
2. **Chạy `python3 dev-tools.py check`** để tự động kiểm tra
3. **Xem log lỗi** trong terminal và browser console
4. **Thử khởi động bằng cách khác** (Python simple server, Node.js, etc.)

---

**🎮 Chúc bạn có trải nghiệm phát triển vui vẻ với CManga!**