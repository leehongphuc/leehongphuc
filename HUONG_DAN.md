# 🎯 CManga - Chạy Offline (Không Cần Database)

## 📋 Tổng Quan
Dự án CManga đã được cấu hình để chạy offline mà không cần Firebase Database. Tất cả dữ liệu sẽ sử dụng mock data.

## 🚀 Cách Chạy Dự Án

### Phương Pháp 1: Sử dụng Script
```bash
./run.sh
```

### Phương Pháp 2: Chạy Python Trực Tiếp
```bash
python3 server.py
```

## 🌐 Truy Cập Ứng Dụng

Sau khi server khởi động, truy cập:
- **URL chính**: http://localhost:8000/cmangax3.com/user/game/dashboard.html
- **Hoặc**: http://localhost:8000 (sẽ tự động redirect)

## 🔧 Các Thay Đổi Đã Thực Hiện

### 1. Firebase Mock (`/assets/js/firebase-mock.js`)
- Thay thế Firebase Realtime Database
- Cung cấp mock data cho truyện, user, chat
- Giả lập tất cả Firebase functions

### 2. Dashboard Modified
- Thay thế Firebase CDN links bằng mock script
- Giữ nguyên toàn bộ UI và functionality

### 3. HTTP Server (`server.py`)
- Serve static files
- Handle API requests với mock responses
- CORS support

## 📊 Mock Data Có Sẵn

### Truyện:
- One Piece (ID: 1)
- Naruto (ID: 2)

### User Demo:
- ID: 31443
- Username: demo_user
- Character ID: 6027
- Level: 25, Coins: 10000

### Chat Messages:
- Một số tin nhắn demo

## 🛠️ Tùy Chỉnh Mock Data

Để thêm/sửa mock data, chỉnh sửa file:
```
/assets/js/firebase-mock.js
```

Trong object `mockData`, bạn có thể:
- Thêm truyện mới
- Thêm chapters
- Thêm users
- Thêm chat messages

## 🔍 Troubleshooting

### Port đã được sử dụng:
Server sẽ tự động thử port tiếp theo (8001, 8002...)

### File không tìm thấy:
Đảm bảo chạy server từ thư mục workspace root

### CSS/JS không load:
Kiểm tra đường dẫn trong console browser (F12)

## 📝 Ghi Chú

- Tất cả dữ liệu là mock data, không lưu vào database thật
- Mọi thay đổi sẽ bị mất khi refresh page
- Phù hợp cho development và demo
- Không cần internet connection (trừ Google Analytics và Cloudflare)

## 🎮 Tính Năng Hoạt Động

✅ Giao diện dashboard
✅ Mock user data
✅ Mock truyện data  
✅ Mock chat system
✅ Static assets (CSS, JS, images)
✅ API endpoints với mock responses

## 📞 Hỗ Trợ

Nếu gặp vấn đề, kiểm tra:
1. Python3 đã cài đặt
2. Port 8000 có available
3. Quyền đọc/ghi file
4. Browser console để xem lỗi JS