# 🎮 CManga - Nền tảng đọc truyện và Game RPG Tu Tiên

![CManga Logo](assets/images/logo.png)

## 📖 **Mô tả dự án**

CManga là một nền tảng web hiện đại kết hợp giữa **đọc truyện tranh online** và **game RPG tu tiên**. Dự án được thiết kế với giao diện đẹp mắt, responsive và trải nghiệm người dùng tối ưu.

## ✨ **Tính năng chính**

### 🌐 **Website đọc truyện**
- **Kho truyện khổng lồ** với hơn 10 triệu thành viên
- Hỗ trợ nhiều thể loại: Manga, Manhua, Manhwa, Truyện chữ
- **Tìm kiếm thông minh** với gợi ý
- **Hệ thống đánh giá** và bình luận
- **Cập nhật liên tục** mỗi ngày

### 🎮 **Game RPG Tu Tiên**
- **Hệ thống nhân vật** phong phú với level up
- **Kỹ năng đa dạng**: Tấn công, phòng thủ, hồi phục, khống chế
- **Trang bị hệ thống**: Vũ khí, giáp, mũ, thắt lưng, dây chuyền...
- **Nguyên tố tu tiên**: Kim, Mộc, Thủy, Hỏa, Thổ, Phong, Lôi, Băng, Quang, Ám
- **Hoạt động game**: Hầm ngục, luyện tập, tông môn, chiến đấu

### 👥 **Hệ thống người dùng**
- **Đăng ký/Đăng nhập** an toàn
- **Hồ sơ cá nhân** với avatar và thông tin
- **Hệ thống VIP** và premium
- **Tích điểm** và danh hiệu

## 🚀 **Cách chạy dự án trên cục bộ**

### **⚡ Setup nhanh (3 bước)**

```bash
# Bước 1: Kiểm tra Python
python3 --version

# Bước 2: Chạy script setup tự động
./start.sh

# Bước 3: Hoặc khởi động trực tiếp
python3 server.py
```

🎉 **Xong!** Truy cập: `http://localhost:8080`

### **📖 Hướng dẫn chi tiết**

Xem file [SETUP-LOCAL.md](./SETUP-LOCAL.md) để có hướng dẫn đầy đủ về:
- Yêu cầu hệ thống
- Các cách khởi động khác nhau
- Troubleshooting
- Development tips

### **🛠️ Development Commands**

```bash
# Khởi động server (khuyến nghị)
python3 server.py

# Hoặc sử dụng npm scripts
npm start                    # Khởi động server
npm run check               # Kiểm tra dự án
npm run setup               # Setup hoàn chỉnh

# Hoặc sử dụng Makefile
make start                  # Khởi động server
make setup                  # Setup + kiểm tra
make help                   # Xem tất cả commands
```

### **🌐 URLs sau khi khởi động**
- **Trang chủ**: http://localhost:8080
- **Game RPG**: http://localhost:8080/game
- **API Mock**: http://localhost:8080/api/*

## 📁 **Cấu trúc dự án**

```
cmanga/
├── index.html              # Trang chủ chính
├── game.html               # Trang game RPG
├── assets/                 # Tài nguyên
│   ├── css/               # Stylesheets
│   │   ├── main.css       # CSS chính
│   │   ├── game.css       # CSS cho game
│   │   └── components.css # CSS components
│   ├── js/                # JavaScript
│   │   ├── main.js        # Logic chính
│   │   ├── game.js        # Logic game
│   │   └── components.js  # Components chung
│   └── images/            # Hình ảnh
├── README.md              # Tài liệu dự án
└── package.json           # Dependencies (nếu có)
```

## 🎨 **Thiết kế và UI/UX**

### **Design System**
- **Color Palette**: Sử dụng CSS variables với hệ thống màu nhất quán
- **Typography**: Font Inter với hierarchy rõ ràng
- **Spacing**: Hệ thống spacing 8px grid
- **Shadows**: 3 levels shadow (sm, md, lg)
- **Border Radius**: 8px và 12px cho các elements

### **Responsive Design**
- **Mobile First** approach
- **Breakpoints**: 480px, 768px, 1024px
- **Flexbox & Grid** layout
- **Touch-friendly** interactions

### **Components**
- **Buttons**: Primary, Secondary, Success với states
- **Cards**: Dashboard cards, manga items
- **Modals**: Login/Register forms
- **Navigation**: Header, sidebar, tabs
- **Forms**: Input fields, validation

## 🎮 **Game Features**

### **Character System**
- **Level progression** với kinh nghiệm
- **Stats**: HP, MP, Attack, Defense, Speed
- **Talent system** với các nguyên tố
- **Cultivation levels** tu tiên

### **Skill System**
- **6 loại kỹ năng** chính
- **Target types**: Enemy, Team, Self
- **Area effects**: Single, Triple, AoE
- **MP consumption** và cooldown

### **Equipment System**
- **10 slot trang bị** chính
- **Quality levels**: Thường, Tốt, Hiếm, Hoàn mỹ
- **Upgrade system** với materials
- **Set bonuses** và special effects

### **Activities**
- **Training**: Luyện tập để nhận exp và gold
- **Dungeons**: Khám phá hầm ngục
- **Daily rewards**: Quà hàng ngày
- **Guild system**: Tông môn và teamwork

## 🔧 **Công nghệ sử dụng**

### **Frontend**
- **HTML5** semantic markup
- **CSS3** với custom properties và Grid/Flexbox
- **Vanilla JavaScript** ES6+ (không dùng framework)
- **Font Awesome** icons
- **Google Fonts** (Inter)

### **Backend (Simulated)**
- **Mock API** calls với Promise
- **Local Storage** cho user data
- **Session management** simulated

### **Performance**
- **Lazy loading** cho images
- **Debounced search** functionality
- **Optimized animations** với CSS transforms
- **Minimal DOM manipulation**

## 📱 **Tính năng responsive**

### **Mobile (≤480px)**
- Single column layout
- Stacked navigation
- Full-width buttons
- Optimized touch targets

### **Tablet (≤768px)**
- Two column layout
- Collapsible sidebar
- Medium-sized components

### **Desktop (≥1024px)**
- Full layout với sidebar
- Hover effects
- Advanced interactions

## 🚀 **Deployment**

### **Static Hosting**
- **Netlify**: Kéo thả thư mục `dist/`
- **Vercel**: Auto-deploy từ Git
- **GitHub Pages**: Push to `gh-pages` branch

### **Production Build**
```bash
# Tối ưu hóa CSS và JS
npm run build

# Hoặc sử dụng tools như:
# - PurgeCSS để remove unused CSS
# - Terser để minify JavaScript
# - Image optimization
```

## 🧪 **Testing**

### **Browser Testing**
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### **Device Testing**
- iOS Safari
- Android Chrome
- Desktop browsers
- Tablet devices

## 📈 **Performance Metrics**

### **Lighthouse Scores**
- **Performance**: 95+
- **Accessibility**: 95+
- **Best Practices**: 95+
- **SEO**: 90+

### **Core Web Vitals**
- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

## 🔒 **Security Features**

### **Frontend Security**
- **XSS Prevention** với input sanitization
- **CSRF Protection** với tokens
- **Content Security Policy** headers
- **Secure cookies** với httpOnly flag

### **Data Protection**
- **Input validation** client-side
- **Output encoding** để tránh injection
- **Secure storage** practices

## 🌐 **Internationalization**

### **Multi-language Support**
- **Tiếng Việt** (chính)
- **English** (planned)
- **Chinese** (planned)
- **Japanese** (planned)

### **Localization Features**
- **Date formatting** theo locale
- **Number formatting** với separators
- **Currency display** theo region

## 📊 **Analytics & Monitoring**

### **User Analytics**
- **Page views** tracking
- **User engagement** metrics
- **Performance monitoring**
- **Error tracking**

### **Game Analytics**
- **Player progression** tracking
- **Skill usage** statistics
- **Equipment preferences**
- **Activity completion rates**

## 🤝 **Contributing**

### **Development Setup**
1. Fork repository
2. Create feature branch
3. Make changes
4. Test thoroughly
5. Submit pull request

### **Code Standards**
- **ESLint** configuration
- **Prettier** formatting
- **Conventional commits**
- **Code review** required

## 📄 **License**

Dự án này được phát hành dưới **MIT License**. Xem file `LICENSE` để biết thêm chi tiết.

## 📞 **Liên hệ**

- **Website**: [cmanga.com](https://cmanga.com)
- **Email**: contact@cmanga.com
- **Discord**: [CManga Community](https://discord.gg/cmanga)
- **Facebook**: [CManga Official](https://facebook.com/cmanga)

## 🙏 **Acknowledgments**

- **Font Awesome** cho icons
- **Google Fonts** cho typography
- **CSS Grid** và **Flexbox** cho layout
- **Modern JavaScript** features

---

**Made with ❤️ by CManga Team**

*Cập nhật lần cuối: Tháng 8, 2025*