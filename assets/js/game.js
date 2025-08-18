// Game JavaScript for CManga RPG
class CMangaGame {
    constructor() {
        this.currentSection = 'dashboard';
        this.character = null;
        this.skills = [];
        this.inventory = [];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadGameData();
        this.initializeCharacter();
        this.loadSkills();
        this.loadInventory();
    }

    setupEventListeners() {
        // Game navigation
        document.querySelectorAll('.game-nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = link.getAttribute('href').substring(1);
                this.switchSection(target);
            });
        });

        // Inventory tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.switchInventoryTab(btn.dataset.tab);
            });
        });

        // Game actions
        this.setupGameActions();
    }

    setupGameActions() {
        // Training action
        if (typeof startTraining === 'undefined') {
            window.startTraining = () => this.startTraining();
        }

        // Dungeon action
        if (typeof enterDungeon === 'undefined') {
            window.enterDungeon = () => this.enterDungeon();
        }

        // Daily reward action
        if (typeof dailyReward === 'undefined') {
            window.dailyReward = () => this.claimDailyReward();
        }
    }

    switchSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.game-section').forEach(section => {
            section.classList.remove('active');
        });

        // Remove active class from all nav items
        document.querySelectorAll('.game-nav-item').forEach(item => {
            item.classList.remove('active');
        });

        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
        }

        // Update active nav item
        const activeNavItem = document.querySelector(`[href="#${sectionId}"]`).closest('.game-nav-item');
        if (activeNavItem) {
            activeNavItem.classList.add('active');
        }

        this.currentSection = sectionId;
    }

    switchInventoryTab(tabId) {
        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Activate target tab
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Show target content
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.add('active');
        }
    }

    async loadGameData() {
        try {
            // Simulate loading game data
            const gameData = await this.fetchGameData();
            this.updateGameStats(gameData);
        } catch (error) {
            console.error('Error loading game data:', error);
        }
    }

    async fetchGameData() {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    crystal: 1500,
                    gold: 25000,
                    energy: 85,
                    level: 5,
                    exp: 75,
                    maxExp: 100,
                    hp: 1200,
                    maxHp: 1200,
                    mp: 600,
                    maxMp: 600,
                    attack: 180,
                    defense: 95
                });
            }, 500);
        });
    }

    updateGameStats(data) {
        // Update header stats
        document.getElementById('crystalAmount').textContent = data.crystal.toLocaleString();
        document.getElementById('goldAmount').textContent = data.gold.toLocaleString();
        document.getElementById('energyAmount').textContent = data.energy;

        // Update user info
        document.getElementById('userLevel').textContent = data.level;
        document.getElementById('expText').textContent = `${data.exp} / ${data.maxExp}`;
        
        const expFill = document.getElementById('expFill');
        if (expFill) {
            expFill.style.width = `${(data.exp / data.maxExp) * 100}%`;
        }

        // Update character status
        document.getElementById('hpValue').textContent = `${data.hp} / ${data.maxHp}`;
        document.getElementById('mpValue').textContent = `${data.mp} / ${data.maxMp}`;
        document.getElementById('attackValue').textContent = data.attack;
        document.getElementById('defenseValue').textContent = data.defense;

        // Update character level badge
        const levelBadge = document.getElementById('characterLevelBadge');
        if (levelBadge) {
            levelBadge.textContent = data.level;
        }
    }

    initializeCharacter() {
        // Simulate character data
        this.character = {
            name: 'Tu Tiên Giả',
            level: 5,
            talent: 'Kim',
            cultivation: 'Luyện Khí 5',
            reputation: 150
        };

        // Update character display
        document.getElementById('characterName').textContent = this.character.name;
        document.getElementById('talentValue').textContent = this.character.talent;
        document.getElementById('cultivationValue').textContent = this.character.cultivation;
        document.getElementById('reputationValue').textContent = this.character.reputation;
    }

    async loadSkills() {
        try {
            const skills = await this.fetchSkills();
            this.renderSkills(skills);
        } catch (error) {
            console.error('Error loading skills:', error);
        }
    }

    async fetchSkills() {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        id: 1,
                        name: 'Kim Cương Quyền',
                        type: 'Tấn công đơn lẻ',
                        icon: '⚔️',
                        mp: 60,
                        damage: 100,
                        target: 'Kẻ địch',
                        area: 1
                    },
                    {
                        id: 2,
                        name: 'Thổ Địa Hộ Thể',
                        type: 'Phòng thủ',
                        icon: '🛡️',
                        mp: 80,
                        defense: 150,
                        target: 'Bản thân',
                        area: 1
                    },
                    {
                        id: 3,
                        name: 'Thủy Nguyệt Trị Liệu',
                        type: 'Hồi phục',
                        icon: '💧',
                        mp: 90,
                        heal: 200,
                        target: 'Đồng đội',
                        area: 1
                    },
                    {
                        id: 4,
                        name: 'Hỏa Long Phun Lửa',
                        type: 'Tấn công diện rộng',
                        icon: '🔥',
                        mp: 120,
                        damage: 80,
                        target: 'Kẻ địch',
                        area: 6
                    },
                    {
                        id: 5,
                        name: 'Mộc Linh Trói Buộc',
                        type: 'Khống chế',
                        icon: '🌿',
                        mp: 70,
                        effect: 'Trói buộc',
                        target: 'Kẻ địch',
                        area: 3
                    },
                    {
                        id: 6,
                        name: 'Phong Thần Tốc',
                        type: 'Tăng tốc',
                        icon: '💨',
                        mp: 50,
                        effect: 'Tăng tốc độ',
                        target: 'Bản thân',
                        area: 1
                    }
                ]);
            }, 500);
        });
    }

    renderSkills(skills) {
        const skillsGrid = document.getElementById('skillsGrid');
        if (!skillsGrid) return;

        skillsGrid.innerHTML = skills.map(skill => `
            <div class="skill-item" onclick="game.showSkillDetails(${skill.id})">
                <div class="skill-header">
                    <div class="skill-icon">${skill.icon}</div>
                    <div class="skill-info">
                        <h4>${skill.name}</h4>
                        <span class="skill-type">${skill.type}</span>
                    </div>
                </div>
                <div class="skill-stats">
                    <div class="skill-stat">
                        <span>MP:</span>
                        <span>${skill.mp}</span>
                    </div>
                    <div class="skill-stat">
                        <span>Mục tiêu:</span>
                        <span>${skill.target}</span>
                    </div>
                    <div class="skill-stat">
                        <span>Phạm vi:</span>
                        <span>${skill.area}</span>
                    </div>
                    <div class="skill-stat">
                        <span>${skill.damage ? 'Sát thương:' : skill.heal ? 'Hồi phục:' : skill.defense ? 'Phòng thủ:' : 'Hiệu ứng:'}</span>
                        <span>${skill.damage || skill.heal || skill.defense || skill.effect}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    showSkillDetails(skillId) {
        // Find skill data
        const skill = this.skills.find(s => s.id === skillId);
        if (!skill) return;

        // Show skill modal
        const modal = document.getElementById('skillModal');
        const details = document.getElementById('skillDetails');
        
        if (modal && details) {
            details.innerHTML = `
                <div class="skill-detail-header">
                    <div class="skill-detail-icon">${skill.icon}</div>
                    <div>
                        <h3>${skill.name}</h3>
                        <p class="skill-detail-type">${skill.type}</p>
                    </div>
                </div>
                <div class="skill-detail-stats">
                    <div class="skill-detail-stat">
                        <strong>MP tiêu hao:</strong> ${skill.mp}
                    </div>
                    <div class="skill-detail-stat">
                        <strong>Mục tiêu:</strong> ${skill.target}
                    </div>
                    <div class="skill-detail-stat">
                        <strong>Phạm vi:</strong> ${skill.area} người
                    </div>
                    ${skill.damage ? `<div class="skill-detail-stat"><strong>Sát thương:</strong> ${skill.damage}</div>` : ''}
                    ${skill.heal ? `<div class="skill-detail-stat"><strong>Hồi phục:</strong> ${skill.heal}</div>` : ''}
                    ${skill.defense ? `<div class="skill-detail-stat"><strong>Phòng thủ:</strong> ${skill.defense}</div>` : ''}
                    ${skill.effect ? `<div class="skill-detail-stat"><strong>Hiệu ứng:</strong> ${skill.effect}</div>` : ''}
                </div>
                <div class="skill-detail-actions">
                    <button class="btn btn-primary" onclick="game.learnSkill(${skill.id})">Học Kỹ Năng</button>
                    <button class="btn btn-secondary" onclick="game.upgradeSkill(${skill.id})">Nâng Cấp</button>
                </div>
            `;
            
            modal.style.display = 'block';
        }
    }

    async loadInventory() {
        try {
            const inventory = await this.fetchInventory();
            this.renderInventory(inventory);
        } catch (error) {
            console.error('Error loading inventory:', error);
        }
    }

    async fetchInventory() {
        // Simulate API call
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    equipment: [
                        { id: 1, name: 'Kiếm Sắt', type: 'weapon', quality: 2, level: 5 },
                        { id: 2, name: 'Áo Giáp Da', type: 'armor', quality: 1, level: 3 },
                        { id: 3, name: 'Mũ Sắt', type: 'helmet', quality: 2, level: 4 },
                        { id: 4, name: 'Giày Da', type: 'boots', quality: 1, level: 2 }
                    ],
                    consumables: [
                        { id: 5, name: 'Thuốc Hồi Máu', type: 'potion', quantity: 10 },
                        { id: 6, name: 'Thuốc Hồi MP', type: 'potion', quantity: 8 }
                    ],
                    materials: [
                        { id: 7, name: 'Sắt Thô', type: 'material', quantity: 25 },
                        { id: 8, name: 'Gỗ Thông', type: 'material', quantity: 30 }
                    ]
                });
            }, 500);
        });
    }

    renderInventory(inventory) {
        // Render equipment
        const equipmentGrid = document.getElementById('equipmentGrid');
        if (equipmentGrid) {
            equipmentGrid.innerHTML = inventory.equipment.map(item => `
                <div class="equipment-item" onclick="game.showItemDetails(${item.id})">
                    <div class="equipment-quality quality-${item.quality}"></div>
                    <span>${item.name}</span>
                </div>
            `).join('');
        }
    }

    // Game Actions
    async startTraining() {
        if (this.character.energy < 10) {
            this.showGameNotification('Không đủ năng lượng để luyện tập!', 'error');
            return;
        }

        try {
            this.showGameNotification('Đang luyện tập...', 'info');
            
            // Simulate training
            const result = await this.performTraining();
            
            if (result.success) {
                this.showGameNotification(`Luyện tập thành công! Nhận được ${result.exp} kinh nghiệm`, 'success');
                this.updateCharacterStats(result);
            }
        } catch (error) {
            this.showGameNotification('Có lỗi xảy ra khi luyện tập', 'error');
        }
    }

    async performTraining() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    success: true,
                    exp: Math.floor(Math.random() * 20) + 10,
                    energy: -10,
                    gold: Math.floor(Math.random() * 100) + 50
                });
            }, 2000);
        });
    }

    async enterDungeon() {
        if (this.character.energy < 20) {
            this.showGameNotification('Không đủ năng lượng để vào hầm ngục!', 'error');
            return;
        }

        this.showGameNotification('Đang vào hầm ngục...', 'info');
        
        // Simulate dungeon exploration
        setTimeout(() => {
            const rewards = this.generateDungeonRewards();
            this.showGameNotification(`Hoàn thành hầm ngục! Nhận được ${rewards.exp} kinh nghiệm và ${rewards.gold} vàng`, 'success');
            this.updateCharacterStats(rewards);
        }, 3000);
    }

    generateDungeonRewards() {
        return {
            exp: Math.floor(Math.random() * 50) + 30,
            gold: Math.floor(Math.random() * 200) + 100,
            energy: -20
        };
    }

    async claimDailyReward() {
        try {
            const reward = await this.fetchDailyReward();
            if (reward.available) {
                this.showGameNotification(`Nhận quà hàng ngày: ${reward.gold} vàng, ${reward.crystal} ngọc!`, 'success');
                this.updateCharacterStats(reward);
            } else {
                this.showGameNotification('Bạn đã nhận quà hôm nay rồi!', 'info');
            }
        } catch (error) {
            this.showGameNotification('Có lỗi xảy ra khi nhận quà', 'error');
        }
    }

    async fetchDailyReward() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    available: true,
                    gold: 1000,
                    crystal: 50,
                    energy: 20
                });
            }, 500);
        });
    }

    updateCharacterStats(stats) {
        // Update character stats based on rewards
        // This would typically update the game state and UI
        console.log('Character stats updated:', stats);
    }

    showGameNotification(message, type = 'info') {
        // Create game notification
        const notification = document.createElement('div');
        notification.className = `game-notification game-notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            max-width: 300px;
            animation: gameSlideIn 0.3s ease;
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 4 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 4000);
    }

    // Skill management
    learnSkill(skillId) {
        this.showGameNotification('Đang học kỹ năng...', 'info');
        // Simulate learning skill
        setTimeout(() => {
            this.showGameNotification('Học kỹ năng thành công!', 'success');
            this.closeSkillModal();
        }, 1500);
    }

    upgradeSkill(skillId) {
        this.showGameNotification('Đang nâng cấp kỹ năng...', 'info');
        // Simulate upgrading skill
        setTimeout(() => {
            this.showGameNotification('Nâng cấp kỹ năng thành công!', 'success');
            this.closeSkillModal();
        }, 1500);
    }

    closeSkillModal() {
        const modal = document.getElementById('skillModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    showItemDetails(itemId) {
        this.showGameNotification('Xem chi tiết vật phẩm...', 'info');
        // Implement item details modal
    }
}

// Initialize game when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.game = new CMangaGame();
});

// Add CSS animation for game notifications
const gameStyle = document.createElement('style');
gameStyle.textContent = `
    @keyframes gameSlideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .game-notification {
        font-weight: 500;
        font-size: 14px;
    }
`;
document.head.appendChild(gameStyle);