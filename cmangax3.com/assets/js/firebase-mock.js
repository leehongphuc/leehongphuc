// Firebase Mock - Thay thế Firebase Realtime Database
// Tạo mock data cho ứng dụng CManga

// Mock data cho truyện và chapters
const mockData = {
    Cmanga: {
        stories: {
            1: {
                id: 1,
                title: "One Piece",
                description: "Câu chuyện về Luffy và băng hải tặc Mũ Rơm",
                author: "Oda Eiichiro",
                status: "ongoing",
                cover: "/assets/img/stories/onepiece.jpg",
                chapters: {
                    1: { id: 1, title: "Chương 1: Khởi đầu", content: "Nội dung chương 1..." },
                    2: { id: 2, title: "Chương 2: Phiêu lưu", content: "Nội dung chương 2..." }
                }
            },
            2: {
                id: 2,
                title: "Naruto",
                description: "Câu chuyện về ninja Naruto Uzumaki",
                author: "Kishimoto Masashi",
                status: "completed",
                cover: "/assets/img/stories/naruto.jpg",
                chapters: {
                    1: { id: 1, title: "Chương 1: Ninja số 1", content: "Nội dung chương 1..." },
                    2: { id: 2, title: "Chương 2: Đội 7", content: "Nội dung chương 2..." }
                }
            }
        },
        users: {
            31443: {
                id: 31443,
                username: "user_demo",
                email: "demo@cmanga.com",
                characters: {
                    6027: {
                        id: 6027,
                        name: "Character Demo",
                        level: 25,
                        exp: 1500,
                        coins: 10000
                    }
                }
            }
        },
        chat: {
            messages: {
                1: {
                    id: 1,
                    user_id: 31443,
                    message: "Chào mọi người!",
                    timestamp: Date.now() - 3600000
                },
                2: {
                    id: 2,
                    user_id: 31443,
                    message: "Truyện mới cập nhật rồi!",
                    timestamp: Date.now() - 1800000
                }
            }
        }
    }
};

// Mock Firebase object
const mockFirebase = {
    apps: [],
    initializeApp: function(config) {
        console.log('Mock Firebase initialized with config:', config);
        this.apps.push({ config: config });
        return this;
    },
    
    database: function() {
        return {
            ref: function(path) {
                console.log('Mock Firebase ref:', path);
                return {
                    // Lấy data theo path
                    once: function(eventType, callback) {
                        console.log('Mock Firebase once:', eventType, path);
                        const pathParts = path.split('/').filter(p => p);
                        let data = mockData;
                        
                        for (let part of pathParts) {
                            if (data && data[part]) {
                                data = data[part];
                            } else {
                                data = null;
                                break;
                            }
                        }
                        
                        const snapshot = {
                            val: function() { return data; },
                            exists: function() { return data !== null; },
                            key: pathParts[pathParts.length - 1] || null
                        };
                        
                        if (callback) {
                            setTimeout(() => callback(snapshot), 100);
                        }
                        
                        return Promise.resolve(snapshot);
                    },
                    
                    // Lắng nghe thay đổi
                    on: function(eventType, callback) {
                        console.log('Mock Firebase on:', eventType, path);
                        // Gọi callback với data hiện tại
                        this.once(eventType, callback);
                        
                        // Simulate real-time updates (optional)
                        if (path.includes('chat')) {
                            setInterval(() => {
                                this.once(eventType, callback);
                            }, 5000); // Update every 5 seconds
                        }
                    },
                    
                    // Tắt listener
                    off: function(eventType, callback) {
                        console.log('Mock Firebase off:', eventType, path);
                    },
                    
                    // Thêm data
                    push: function(data) {
                        console.log('Mock Firebase push:', data, 'to', path);
                        const newKey = 'mock_' + Date.now();
                        return {
                            key: newKey,
                            then: function(callback) {
                                if (callback) callback();
                                return Promise.resolve();
                            }
                        };
                    },
                    
                    // Cập nhật data
                    set: function(data) {
                        console.log('Mock Firebase set:', data, 'to', path);
                        return Promise.resolve();
                    },
                    
                    // Xóa data
                    remove: function() {
                        console.log('Mock Firebase remove:', path);
                        return Promise.resolve();
                    },
                    
                    // Child reference
                    child: function(childPath) {
                        const newPath = path + '/' + childPath;
                        return mockFirebase.database().ref(newPath);
                    }
                };
            }
        };
    }
};

// Thay thế Firebase global
if (typeof window !== 'undefined') {
    window.firebase = mockFirebase;
} else if (typeof global !== 'undefined') {
    global.firebase = mockFirebase;
}

console.log('Firebase Mock loaded successfully!');