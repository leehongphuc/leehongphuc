#!/bin/bash

# CManga Local Development Starter
# Script khởi động nhanh cho môi trường phát triển cục bộ

clear

echo "╔══════════════════════════════════════════════╗"
echo "║            🎮 CManga Starter                 ║"
echo "║          Script khởi động nhanh              ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

# Kiểm tra Python
if command -v python3 &> /dev/null; then
    PYTHON_CMD="python3"
elif command -v python &> /dev/null; then
    PYTHON_CMD="python"
else
    echo "❌ Lỗi: Không tìm thấy Python. Vui lòng cài đặt Python 3.6+"
    exit 1
fi

echo "🐍 Phiên bản Python: $($PYTHON_CMD --version)"

# Kiểm tra Node.js (tùy chọn)
if command -v node &> /dev/null; then
    echo "📦 Phiên bản Node.js: $(node --version)"
    HAS_NODE=true
else
    echo "📦 Node.js: Không có (tùy chọn)"
    HAS_NODE=false
fi

echo ""
echo "🚀 Chọn phương thức khởi động:"
echo "   1. Python HTTP Server (Khuyến nghị)"
echo "   2. Python Custom Server (Có API mock)"

if [ "$HAS_NODE" = true ]; then
    echo "   3. Node.js Live Server (Auto-reload)"
    echo "   4. Node.js HTTP Server"
fi

echo ""
read -p "Nhập lựa chọn (1-4): " choice

case $choice in
    1)
        echo "🎯 Khởi động Python HTTP Server..."
        echo "📍 URL: http://localhost:8080"
        echo "⚡ Nhấn Ctrl+C để dừng"
        echo ""
        $PYTHON_CMD -m http.server 8080
        ;;
    2)
        echo "🎯 Khởi động Python Custom Server..."
        $PYTHON_CMD server.py
        ;;
    3)
        if [ "$HAS_NODE" = true ]; then
            echo "🎯 Khởi động Live Server..."
            if command -v live-server &> /dev/null; then
                live-server --port=8080 --open=/ --no-css-inject
            else
                echo "📦 Cài đặt live-server..."
                npm install -g live-server
                live-server --port=8080 --open=/ --no-css-inject
            fi
        else
            echo "❌ Node.js không có sẵn"
            exit 1
        fi
        ;;
    4)
        if [ "$HAS_NODE" = true ]; then
            echo "🎯 Khởi động Node.js HTTP Server..."
            if command -v http-server &> /dev/null; then
                http-server -p 8080 -o
            else
                echo "📦 Cài đặt http-server..."
                npm install -g http-server
                http-server -p 8080 -o
            fi
        else
            echo "❌ Node.js không có sẵn"
            exit 1
        fi
        ;;
    *)
        echo "❌ Lựa chọn không hợp lệ"
        exit 1
        ;;
esac