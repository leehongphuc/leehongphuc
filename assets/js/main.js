// Main JavaScript for CManga
class CMangaApp {
    constructor() {
        this.currentUser = null;
        this.isAuthenticated = false;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadFeaturedManga();
        this.loadLatestManga();
        this.checkAuthentication();
    }

    setupEventListeners() {
        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navMenu = document.querySelector('.nav-menu');
        
        if (mobileMenuBtn && navMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        }

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.search-btn');
        
        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', () => this.handleSearch());
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.handleSearch();
                }
            });
        }

        // Modal functionality
        this.setupModals();

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    setupModals() {
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModal(e.target.id);
            }
        });

        // Close modal when clicking close button
        document.querySelectorAll('.close').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => {
                const modal = closeBtn.closest('.modal');
                if (modal) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Form submissions
        this.setupFormSubmissions();
    }

    setupFormSubmissions() {
        // Login form
        const loginForm = document.querySelector('#loginModal .auth-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleLogin();
            });
        }

        // Register form
        const registerForm = document.querySelector('#registerModal .auth-form');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleRegister();
            });
        }
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    async handleLogin() {
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        if (!email || !password) {
            this.showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
            return;
        }

        try {
            // Simulate API call
            const response = await this.loginUser(email, password);
            if (response.success) {
                this.currentUser = response.user;
                this.isAuthenticated = true;
                this.updateUIAfterLogin();
                this.closeModal('loginModal');
                this.showNotification('Đăng nhập thành công!', 'success');
            } else {
                this.showNotification(response.message || 'Đăng nhập thất bại', 'error');
            }
        } catch (error) {
            this.showNotification('Có lỗi xảy ra, vui lòng thử lại', 'error');
        }
    }

    async handleRegister() {
        const username = document.getElementById('registerUsername').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;

        if (!username || !email || !password) {
            this.showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
            return;
        }

        if (password.length < 6) {
            this.showNotification('Mật khẩu phải có ít nhất 6 ký tự', 'error');
            return;
        }

        try {
            // Simulate API call
            const response = await this.registerUser(username, email, password);
            if (response.success) {
                this.closeModal('registerModal');
                this.showNotification('Đăng ký thành công! Vui lòng đăng nhập', 'success');
            } else {
                this.showNotification(response.message || 'Đăng ký thất bại', 'error');
            }
        } catch (error) {
            this.showNotification('Có lỗi xảy ra, vui lòng thử lại', 'error');
        }
    }

    async loginUser(email, password) {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                if (email === 'test@example.com' && password === '123456') {
                    resolve({
                        success: true,
                        user: {
                            id: 1,
                            username: 'testuser',
                            email: email,
                            avatar: 'assets/images/avatar-default.png'
                        }
                    });
                } else {
                    resolve({
                        success: false,
                        message: 'Email hoặc mật khẩu không đúng'
                    });
                }
            }, 1000);
        });
    }

    async registerUser(username, email, password) {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    success: true,
                    message: 'Đăng ký thành công'
                });
            }, 1000);
        });
    }

    updateUIAfterLogin() {
        const userMenu = document.querySelector('.user-menu');
        if (userMenu) {
            userMenu.innerHTML = `
                <div class="user-profile">
                    <img src="${this.currentUser.avatar}" alt="Avatar" class="user-avatar">
                    <span class="user-name">${this.currentUser.username}</span>
                    <button class="btn btn-secondary" onclick="app.logout()">Đăng Xuất</button>
                </div>
            `;
        }
    }

    logout() {
        this.currentUser = null;
        this.isAuthenticated = false;
        this.updateUIAfterLogout();
        this.showNotification('Đã đăng xuất thành công', 'success');
    }

    updateUIAfterLogout() {
        const userMenu = document.querySelector('.user-menu');
        if (userMenu) {
            userMenu.innerHTML = `
                <button class="btn btn-primary" onclick="app.openModal('login')">Đăng Nhập</button>
                <button class="btn btn-secondary" onclick="app.openModal('register')">Đăng Ký</button>
            `;
        }
    }

    checkAuthentication() {
        // Check if user is already logged in (e.g., from localStorage)
        const savedUser = localStorage.getItem('cmanga_user');
        if (savedUser) {
            try {
                this.currentUser = JSON.parse(savedUser);
                this.isAuthenticated = true;
                this.updateUIAfterLogin();
            } catch (error) {
                localStorage.removeItem('cmanga_user');
            }
        }
    }

    async loadFeaturedManga() {
        const featuredContainer = document.getElementById('featuredManga');
        if (!featuredContainer) return;

        try {
            // Simulate API call
            const featuredManga = await this.getFeaturedManga();
            this.renderMangaGrid(featuredContainer, featuredManga);
        } catch (error) {
            console.error('Error loading featured manga:', error);
        }
    }

    async loadLatestManga() {
        const latestContainer = document.getElementById('latestManga');
        if (!latestContainer) return;

        try {
            // Simulate API call
            const latestManga = await this.getLatestManga();
            this.renderMangaList(latestContainer, latestManga);
        } catch (error) {
            console.error('Error loading latest manga:', error);
        }
    }

    async getFeaturedManga() {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        id: 1,
                        title: 'Tu Tiên Giới',
                        cover: 'assets/images/manga-1.jpg',
                        rating: 4.8,
                        views: '1.2M'
                    },
                    {
                        id: 2,
                        title: 'Đấu La Đại Lục',
                        cover: 'assets/images/manga-2.jpg',
                        rating: 4.7,
                        views: '980K'
                    },
                    {
                        id: 3,
                        title: 'Võ Động Cang Thiên',
                        cover: 'assets/images/manga-3.jpg',
                        rating: 4.6,
                        views: '850K'
                    },
                    {
                        id: 4,
                        title: 'Thần Mộ',
                        cover: 'assets/images/manga-4.jpg',
                        rating: 4.5,
                        views: '720K'
                    }
                ]);
            }, 500);
        });
    }

    async getLatestManga() {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        id: 5,
                        title: 'Thiên Tôn',
                        cover: 'assets/images/manga-5.jpg',
                        chapter: 'Chương 156',
                        updateTime: '2 giờ trước'
                    },
                    {
                        id: 6,
                        title: 'Vạn Cổ Thần Đế',
                        cover: 'assets/images/manga-6.jpg',
                        chapter: 'Chương 89',
                        updateTime: '4 giờ trước'
                    },
                    {
                        id: 7,
                        title: 'Cửu Tinh Bá Thể Quyết',
                        cover: 'assets/images/manga-7.jpg',
                        chapter: 'Chương 234',
                        updateTime: '6 giờ trước'
                    }
                ]);
            }, 500);
        });
    }

    renderMangaGrid(container, mangaList) {
        container.innerHTML = mangaList.map(manga => `
            <div class="manga-item" onclick="app.openManga(${manga.id})">
                <img src="${manga.cover}" alt="${manga.title}" class="manga-cover">
                <div class="manga-info">
                    <h3 class="manga-title">${manga.title}</h3>
                    <div class="manga-meta">
                        <span class="rating">⭐ ${manga.rating}</span>
                        <span class="views">👁️ ${manga.views}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderMangaList(container, mangaList) {
        container.innerHTML = mangaList.map(manga => `
            <div class="manga-item" onclick="app.openManga(${manga.id})">
                <img src="${manga.cover}" alt="${manga.title}" class="manga-cover">
                <div class="manga-info">
                    <h3 class="manga-title">${manga.title}</h3>
                    <div class="manga-meta">
                        <span class="chapter">${manga.chapter}</span>
                        <span class="update-time">${manga.updateTime}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    openManga(mangaId) {
        // Navigate to manga detail page
        window.location.href = `/manga/${mangaId}`;
    }

    handleSearch() {
        const searchInput = document.querySelector('.search-input');
        const query = searchInput.value.trim();
        
        if (query) {
            // Navigate to search results page
            window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        // Close button functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.remove();
        });
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new CMangaApp();
});

// Global functions for onclick handlers
function openModal(modalId) {
    if (window.app) {
        window.app.openModal(modalId);
    }
}

// Add CSS animation for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    
    .notification-close:hover {
        opacity: 0.8;
    }
`;
document.head.appendChild(style);