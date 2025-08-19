# CManga Development Makefile
# Các lệnh tiện ích cho phát triển

.PHONY: help start dev check clean install build test

# Mặc định hiển thị help
help:
	@echo "╔══════════════════════════════════════════════╗"
	@echo "║            🎮 CManga Makefile                ║"
	@echo "║          Các lệnh phát triển                 ║"
	@echo "╚══════════════════════════════════════════════╝"
	@echo ""
	@echo "📖 Các lệnh có sẵn:"
	@echo "  make start     - Khởi động server (Python)"
	@echo "  make dev       - Chế độ development"
	@echo "  make check     - Kiểm tra dự án"
	@echo "  make install   - Cài đặt dependencies"
	@echo "  make build     - Build production"
	@echo "  make test      - Chạy tests"
	@echo "  make clean     - Dọn dẹp files tạm"
	@echo "  make setup     - Setup hoàn chỉnh"
	@echo ""

# Khởi động server
start:
	@echo "🚀 Khởi động CManga server..."
	python3 server.py

# Chế độ development
dev:
	@echo "🔧 Khởi động development mode..."
	python3 server.py --port 8080

# Kiểm tra dự án
check:
	@echo "🔍 Kiểm tra dự án..."
	python3 dev-tools.py check

# Cài đặt dependencies
install:
	@echo "📦 Cài đặt dependencies..."
	@if command -v npm >/dev/null 2>&1; then \
		npm install; \
	else \
		echo "⚠️  Node.js không có sẵn, bỏ qua npm install"; \
	fi

# Build production
build:
	@echo "🏗️  Build production..."
	@if command -v npm >/dev/null 2>&1; then \
		npm run build; \
	else \
		echo "⚠️  Node.js không có sẵn, bỏ qua build step"; \
	fi

# Chạy tests
test:
	@echo "🧪 Chạy tests..."
	python3 dev-tools.py validate

# Dọn dẹp
clean:
	@echo "🧹 Dọn dẹp files tạm..."
	find . -name "*.pyc" -delete
	find . -name "__pycache__" -type d -exec rm -rf {} + 2>/dev/null || true
	find . -name ".DS_Store" -delete 2>/dev/null || true
	rm -rf dist/ 2>/dev/null || true
	rm -rf node_modules/.cache/ 2>/dev/null || true
	@echo "✅ Đã dọn dẹp xong"

# Setup hoàn chỉnh
setup: install check
	@echo "🔧 Setup hoàn chỉnh..."
	python3 dev-tools.py all
	@echo ""
	@echo "✅ Setup hoàn tất! Sử dụng 'make start' để khởi động"

# Quick start (setup + start)
quick:
	@make setup
	@make start

# Hiển thị thông tin hệ thống
info:
	@echo "📊 Thông tin hệ thống:"
	@echo "  Python: $(shell python3 --version 2>/dev/null || echo 'Không có')"
	@echo "  Node.js: $(shell node --version 2>/dev/null || echo 'Không có')"
	@echo "  NPM: $(shell npm --version 2>/dev/null || echo 'Không có')"
	@echo "  OS: $(shell uname -s)"
	@echo "  PWD: $(shell pwd)"

# Mở browser
open:
	@echo "🌐 Mở trình duyệt..."
	@if command -v xdg-open >/dev/null 2>&1; then \
		xdg-open http://localhost:8080; \
	elif command -v open >/dev/null 2>&1; then \
		open http://localhost:8080; \
	else \
		echo "Vui lòng mở http://localhost:8080 trong trình duyệt"; \
	fi

# Restart server
restart:
	@echo "🔄 Restart server..."
	@pkill -f "python3 server.py" 2>/dev/null || true
	@sleep 1
	@make start