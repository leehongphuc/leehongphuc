#!/bin/bash

echo "🎯 CManga - Chạy dự án offline (không cần database)"
echo "================================================="
echo ""

# Check if Python is available
if ! command -v python3 &> /dev/null; then
    echo "❌ Python3 không được tìm thấy. Vui lòng cài đặt Python3."
    exit 1
fi

echo "✅ Python3 đã sẵn sàng"
echo "🚀 Đang khởi động server..."
echo ""

# Run the server
python3 /workspace/server.py