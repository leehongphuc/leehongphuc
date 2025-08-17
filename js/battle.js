// battle.js - Enhanced Battle System with Detailed Enemy Stats

console.log('🎮 battle.js được tải - bắt đầu khởi tạo battle system');
console.log('📍 Current script loading time:', new Date().toISOString());
console.log('📁 Script file: battle.js loaded successfully');

// Battle System Variables
let player, enemy;
let battleActive = false;
let battleLog = [];
let currentTurn = null;
let damageDisplay = [];
let playerShake = false;
let enemyShake = false;

// Image loading variables
let bgHTMLImg, playerHTMLImg, enemy1HTMLImg, enemy2HTMLImg;
let backgroundLoaded = false;

// Enhanced Enemy data with detailed stats
const battleEnemies = [
    { 
        name: 'Quái Vật Cấp 1', 
        image: 'images/dungeon_1.png',
        hp: 800, 
        maxHp: 800, 
        physicalDamage: 80, 
        magicDamage: 40, 
        criticalChance: 5, 
        criticalDamage: 150, 
        physicalDefense: 15, 
        magicDefense: 12, 
        agility: 0.8,
        expReward: 50,
        goldReward: 100,
        spiritStonesReward: 50,
        materialDrop: { name: 'Huyền Thiết', chance: 0.5, amount: 10 }
    },
    { 
        name: 'Quái Vật Cấp 2', 
        image: 'images/dungeon_2.png',
        hp: 1200, 
        maxHp: 1200, 
        physicalDamage: 120, 
        magicDamage: 65, 
        criticalChance: 8, 
        criticalDamage: 180, 
        physicalDefense: 20, 
        magicDefense: 18, 
        agility: 1.2,
        expReward: 100,
        goldReward: 150,
        spiritStonesReward: 75,
        materialDrop: { name: 'Huyền Thiết', chance: 0.7, amount: 15 }
    }
];

// Export enemies to window
window.battleEnemies = battleEnemies;

// Enhanced damage calculation function
function calculateDamage(attacker, defender, useMagic = false) {
    const baseDamage = useMagic ? attacker.magicDamage : attacker.physicalDamage;
    const defense = useMagic ? defender.magicDefense : defender.physicalDefense;
    
    let damage = baseDamage - defense;
    damage = Math.max(damage, baseDamage * 0.1); // Minimum 10% damage
    
    const isCrit = Math.random() * 100 < attacker.criticalChance;
    if (isCrit) {
        damage *= (attacker.criticalDamage || 200) / 100;
    }
    
    const result = {
        damage: Math.floor(damage),
        isCrit,
        damageType: useMagic ? 'magic' : 'physical'
    };
    
    console.log('⚔️ Damage calculation:', {
        attacker: attacker.name || 'Player',
        baseDamage,
        defense,
        finalDamage: result.damage,
        isCrit: result.isCrit
    });
    
    return result;
}

// Update HTML battle display
function updateBattleDisplay() {
    console.log('🖼️ Updating battle display...');
    if (!player || !enemy) {
        console.log('❌ Cannot update display: player or enemy missing');
        return;
    }
    
    // Update enemy image and HP
    const enemyImg = document.getElementById('enemy-img');
    const enemyHpFill = document.getElementById('enemy-hp-fill');
    const enemyHpText = document.getElementById('enemy-hp-text');
    
    console.log('🔍 HTML elements found:', {
        enemyImg: !!enemyImg,
        enemyHpFill: !!enemyHpFill,
        enemyHpText: !!enemyHpText
    });
    
    if (enemyImg) {
        enemyImg.src = enemy.image;
        enemyImg.style.display = 'block';
        console.log('✅ Enemy image updated:', enemy.image);
    } else {
        console.log('❌ Enemy image element not found');
    }
    
    if (enemyHpFill && enemyHpText) {
        const enemyHpPercent = (enemy.hp / enemy.maxHp) * 100;
        enemyHpFill.style.width = enemyHpPercent + '%';
        enemyHpText.textContent = `${enemy.name}: ${Math.floor(enemy.hp)}`;
        console.log('✅ Enemy HP updated:', enemyHpPercent + '%');
    } else {
        console.log('❌ Enemy HP elements not found');
    }
    
    // Update player HP
    const playerHpFill = document.getElementById('player-hp-fill');
    const playerHpText = document.getElementById('player-hp-text');
    
    if (playerHpFill && playerHpText) {
        const playerHpPercent = (player.hp / player.maxHp) * 100;
        playerHpFill.style.width = playerHpPercent + '%';
        playerHpText.textContent = `Người chơi: ${Math.floor(player.hp)}`;
        console.log('✅ Player HP updated:', playerHpPercent + '%');
    } else {
        console.log('❌ Player HP elements not found');
    }
    
    console.log('✅ Battle display updated successfully');
    console.log('📊 Current status - Player HP:', player.hp, '/', player.maxHp, 'Enemy HP:', enemy.hp, '/', enemy.maxHp);
}

// Show damage number on screen
function showDamageNumber(damage, x, y, isCrit, type) {
    const damageDiv = document.createElement('div');
    damageDiv.className = 'damage-number';
    damageDiv.style.cssText = `
        position: absolute;
        left: ${x}px;
        top: ${y}px;
        color: ${isCrit ? '#ff0000' : (type === 'enemy' ? '#ffff00' : '#ff6666')};
        font-size: ${isCrit ? '28px' : '20px'};
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        z-index: 1000;
        pointer-events: none;
        animation: damageFloat 2s ease-out forwards;
    `;
    
    damageDiv.textContent = `-${damage}${isCrit ? ' CRIT!' : ''}`;
    
    // Add to battle section
    const battleSection = document.getElementById('battle-section');
    if (battleSection) {
        battleSection.appendChild(damageDiv);
        
        // Remove after animation
        setTimeout(() => {
            if (damageDiv.parentNode) {
                damageDiv.remove();
            }
        }, 2000);
    }
}

// Show material drop popup
function showMaterialDropPopup(materialName, amount) {
    const popupDiv = document.createElement('div');
    popupDiv.className = 'material-drop-popup';
    
    let materialImage = '';
    if (materialName === 'Huyền Thiết') {
        materialImage = 'images/huyen_thiet.png';
    } else if (materialName === 'Vàng') {
        materialImage = 'images/vang.png';
    } else if (materialName === 'Kinh Nghiệm') {
        materialImage = 'images/exp.png';
    }
    
    popupDiv.innerHTML = `
        <div class="material-drop-content">
            <div class="material-image">
                <img src="${materialImage}" alt="${materialName}" onerror="this.style.display='none'; this.parentNode.innerHTML='${materialName}';">
                <div class="material-amount">+${amount}</div>
            </div>
            <div class="material-name">${materialName}</div>
        </div>
    `;
    
    // Remove existing popup
    const existingPopup = document.querySelector('.material-drop-popup');
    if (existingPopup) {
        existingPopup.remove();
    }
    
    // Add to battle section
    const battleSection = document.getElementById('battle-section');
    if (battleSection) {
        battleSection.appendChild(popupDiv);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            if (popupDiv.parentNode) {
                popupDiv.remove();
            }
        }, 3000);
    }
}

// Show reward summary
function showRewardSummary(enemy) {
    const summaryDiv = document.createElement('div');
    summaryDiv.className = 'reward-summary';
    
    let materialText = '';
    if (enemy.materialDrop && enemy.materialDrop.name) {
        materialText = `<div class="reward-item">
            <span class="reward-label">Vật phẩm:</span>
            <span class="reward-value">${enemy.materialDrop.name} x${enemy.materialDrop.amount}</span>
        </div>`;
    }
    
    summaryDiv.innerHTML = `
        <div class="reward-summary-content">
            <h3>🎉 Chiến Thắng!</h3>
            <div class="reward-list">
                <div class="reward-item">
                    <span class="reward-label">Kinh nghiệm:</span>
                    <span class="reward-value">+${enemy.expReward || 50}</span>
                </div>
                <div class="reward-item">
                    <span class="reward-label">Kim tệ:</span>
                    <span class="reward-value">+${enemy.goldReward || 100}</span>
                </div>
                <div class="reward-item">
                    <span class="reward-label">Linh thạch:</span>
                    <span class="reward-value">+${enemy.spiritStonesReward || 50}</span>
                </div>
                ${materialText}
            </div>
            <button class="reward-close-btn" onclick="this.parentElement.parentElement.remove()">Đóng</button>
        </div>
    `;
    
    // Remove existing summary
    const existingSummary = document.querySelector('.reward-summary');
    if (existingSummary) {
        existingSummary.remove();
    }
    
    // Add to battle section
    const battleSection = document.getElementById('battle-section');
    if (battleSection) {
        battleSection.appendChild(summaryDiv);
    }
}

// End battle function
function endBattle(playerWon) {
    console.log('🏁 Ending battle, playerWon:', playerWon);
    battleActive = false;
    
    if (playerWon) {
        console.log('🎉 Player won the battle!');
        battleLog.push({ 
            text: `🎉 Bạn đã đánh bại ${enemy.name}!`, 
            type: 'victory' 
        });
        
        // Add rewards
        const gameState = window.gameState || window.initializeGameState();
        if (gameState) {
            // Add experience
            gameState.exp += enemy.expReward || 50;
            
            // Add gold
            gameState.gold += enemy.goldReward || 100;
            
            // Add spirit stones
            gameState.spiritStones += enemy.spiritStonesReward || 50;
            
            // Add material drops
            if (enemy.materialDrop && enemy.materialDrop.name) {
                if (!gameState.materials) {
                    gameState.materials = {};
                }
                const dropAmount = enemy.materialDrop.amount || 10;
                gameState.materials[enemy.materialDrop.name] = (gameState.materials[enemy.materialDrop.name] || 0) + dropAmount;
                
                // Show material drop popup
                showMaterialDropPopup(enemy.materialDrop.name, dropAmount);
            }
            
            // Update display
            if (typeof window.updateDisplay === 'function') {
                window.updateDisplay();
            }
            
            console.log('✅ Rewards added successfully:', {
                exp: enemy.expReward || 50,
                gold: enemy.goldReward || 100,
                spiritStones: enemy.spiritStonesReward || 50,
                material: enemy.materialDrop ? enemy.materialDrop.name : 'none'
            });
        }
        
        // Show reward summary
        showRewardSummary(enemy);
    } else {
        console.log('💀 Player lost the battle!');
        battleLog.push({ 
            text: `💀 Bạn đã bị ${enemy.name} đánh bại!`, 
            type: 'defeat' 
        });
        alert('💀 Bạn đã thua! Hãy thử lại!');
    }
    
    // Return to enemy selection after a delay
    setTimeout(() => {
        if (typeof window.backToEnemySelect === 'function') {
            window.backToEnemySelect();
        }
    }, 2000);
}

// Load battle assets function
function loadBattleAssets() {
    console.log('Loading battle assets...');
    
    // Load background image using regular Image objects
    bgHTMLImg = new Image();
    bgHTMLImg.onload = function() {
        console.log('Background image loaded successfully');
        backgroundLoaded = true;
    };
    bgHTMLImg.onerror = function() {
        console.error('Failed to load background image');
        backgroundLoaded = false;
    };
    bgHTMLImg.src = 'images/phong_canh_1.png';
    
    // Load player image
    playerHTMLImg = new Image();
    playerHTMLImg.onload = function() {
        console.log('Player image loaded');
    };
    playerHTMLImg.onerror = function() {
        console.error('Failed to load player image');
    };
    playerHTMLImg.src = 'images/player_1.png';
    
    // Load enemy images
    enemy1HTMLImg = new Image();
    enemy1HTMLImg.onload = function() {
        console.log('Enemy 1 image loaded');
    };
    enemy1HTMLImg.onerror = function() {
        console.error('Failed to load enemy 1 image');
    };
    enemy1HTMLImg.src = 'images/dungeon_1.png';
    
    enemy2HTMLImg = new Image();
    enemy2HTMLImg.onload = function() {
        console.log('Enemy 2 image loaded');
    };
    enemy2HTMLImg.onerror = function() {
        console.error('Failed to load enemy 2 image');
    };
    enemy2HTMLImg.src = 'images/dungeon_2.png';
}

// Battle loop function
function battleLoop() {
    console.log('🔄 Battle loop called, battleActive:', battleActive, 'player:', !!player, 'enemy:', !!enemy);
    
    if (!battleActive || !player || !enemy) {
        console.log('❌ Battle loop stopped: battleActive =', battleActive, 'player =', !!player, 'enemy =', !!enemy);
        return;
    }
    
    console.log('⚔️ Current turn:', currentTurn, 'Player HP:', player.hp, 'Enemy HP:', enemy.hp);
    
    if (currentTurn === 'player') {
        console.log('👤 Player turn - calculating damage...');
        // Player's turn
        const damage = calculateDamage(player, enemy);
        enemy.hp = Math.max(0, enemy.hp - damage.damage);
        
        // Add damage display
        damageDisplay.push({
            damage: damage.damage,
            x: 250, // Center of battle scene
            y: 150, // Above enemy
            time: 2,
            isCrit: damage.isCrit,
            type: 'enemy'
        });
        
        // Show damage number on screen
        showDamageNumber(damage.damage, 250, 150, damage.isCrit, 'enemy');
        
        // Enemy shake effect
        enemyShake = true;
        const enemyElement = document.querySelector('.battle-enemy');
        if (enemyElement) {
            enemyElement.classList.add('shake');
            setTimeout(() => { 
                enemyElement.classList.remove('shake');
                enemyShake = false; 
            }, 500);
        }
        
        battleLog.push({ 
            text: `Bạn gây ${damage.damage} sát thương${damage.isCrit ? ' CHÍ MẠNG!' : ''} cho ${enemy.name}!`, 
            type: 'player' 
        });
        
        currentTurn = 'enemy';
        
        // Check if enemy is defeated
        if (enemy.hp <= 0) {
            endBattle(true);
            return;
        }
        
        // Enemy's turn after a delay
        console.log('⏰ Scheduling enemy turn in 1 second...');
        setTimeout(() => {
            if (battleActive) {
                console.log('👹 Executing scheduled enemy turn...');
                enemyTurn();
            } else {
                console.log('❌ Battle no longer active, enemy turn cancelled');
            }
        }, 1000);
        
    } else {
        // Enemy's turn
        console.log('👹 Executing immediate enemy turn...');
        enemyTurn();
    }
    
    // Update display
    updateBattleDisplay();
    console.log('🔄 Battle loop completed, updating display...');
}

// Enemy turn function
function enemyTurn() {
    console.log('👹 Enemy turn - calculating damage...');
    if (!battleActive || !player || !enemy) {
        console.log('❌ Enemy turn stopped: battleActive =', battleActive, 'player =', !!player, 'enemy =', !!enemy);
        return;
    }
    
    const damage = calculateDamage(enemy, player);
    player.hp = Math.max(0, player.hp - damage.damage);
    
    // Add damage display
    damageDisplay.push({
        damage: damage.damage,
        x: 250, // Center of battle scene
        y: 500, // Above player
        time: 2,
        isCrit: damage.isCrit,
        type: 'player'
    });
    
    // Show damage number on screen
    showDamageNumber(damage.damage, 250, 500, damage.isCrit, 'player');
    
    // Player shake effect
    playerShake = true;
    const playerElement = document.querySelector('.battle-player');
    if (playerElement) {
        playerElement.classList.add('shake');
        setTimeout(() => { 
            playerElement.classList.remove('shake');
            playerShake = false; 
        }, 500);
    }
    
    battleLog.push({ 
        text: `${enemy.name} gây ${damage.damage} sát thương${damage.isCrit ? ' CHÍ MẠNG!' : ''} cho bạn!`, 
        type: 'enemy' 
    });
    
    currentTurn = 'player';
    
    // Check if player is defeated
    if (player.hp <= 0) {
        console.log('💀 Player defeated, ending battle...');
        endBattle(false);
        return;
    }
    
    // Schedule next player turn
    console.log('⏰ Scheduling next player turn in 1 second...');
    setTimeout(() => {
        if (battleActive) {
            console.log('👤 Executing scheduled player turn...');
            battleLoop();
        } else {
            console.log('❌ Battle no longer active, player turn cancelled');
        }
    }, 1000);
    
    // Update display
    updateBattleDisplay();
    console.log('👹 Enemy turn completed, next turn scheduled...');
}

// Start battle function
function startBattle(index, locationType) {
    console.log('Bắt đầu chiến đấu với quái:', index, 'tại địa điểm:', locationType);
    
    // Check if dependencies are available
    if (typeof window.getEnemies !== 'function') {
        console.error('getEnemies function not available, waiting for main.js...');
        // Retry after a short delay
        setTimeout(() => startBattle(index, locationType), 200);
        return;
    }
    
    // Get enemies from main.js based on location
    let enemies;
    try {
        enemies = window.getEnemies(locationType);
    } catch (error) {
        console.error('Error getting enemies:', error);
        alert('Lỗi: Không thể tải thông tin quái vật!');
        return;
    }
    
    if (index < 0 || index >= enemies.length) {
        console.error('Lỗi: Chỉ số quái không hợp lệ:', index);
        alert('Lỗi: Không thể chọn quái vật!');
        return;
    }

    // Create enemy copy and reset HP
    enemy = { ...enemies[index] };
    enemy.hp = enemy.maxHp;
    window.selectedLocation = locationType;
    battleActive = true;
    battleLog = [];
    damageDisplay = [];
    playerShake = false;
    enemyShake = false;

    // Get game state
    let gameState;
    if (typeof window.gameState !== 'undefined' && window.gameState) {
        gameState = window.gameState;
    } else if (typeof window.initializeGameState === 'function') {
        gameState = window.initializeGameState();
    } else {
        console.error('Không thể truy cập gameState!');
        alert('Lỗi: Không thể truy cập trạng thái game!');
        return;
    }
    
    // Initialize player stats
    player = {
        hp: (gameState.stats.hp.base || 1000) + (gameState.stats.hp.bonus || 0) + (gameState.stats.hp.potentialBonus || 0),
        maxHp: (gameState.stats.hp.base || 1000) + (gameState.stats.hp.bonus || 0) + (gameState.stats.hp.potentialBonus || 0),
        physicalDamage: (gameState.stats.physicalDamage.base || 100) + (gameState.stats.physicalDamage.bonus || 0) + (gameState.stats.physicalDamage.potentialBonus || 0),
        magicDamage: (gameState.stats.magicDamage.base || 100) + (gameState.stats.magicDamage.bonus || 0) + (gameState.stats.magicDamage.potentialBonus || 0),
        criticalChance: (gameState.stats.criticalChance.base || 5) + (gameState.stats.criticalChance.bonus || 0) + (gameState.stats.criticalChance.potentialBonus || 0),
        criticalDamage: (gameState.stats.criticalDamage.base || 200) + (gameState.stats.criticalDamage.bonus || 0) + (gameState.stats.criticalDamage.potentialBonus || 0),
        physicalDefense: (gameState.stats.physicalDefense.base || 20) + (gameState.stats.physicalDefense.bonus || 0) + (gameState.stats.physicalDefense.potentialBonus || 0),
        magicDefense: (gameState.stats.magicDefense.base || 20) + (gameState.stats.magicDefense.bonus || 0) + (gameState.stats.magicDefense.potentialBonus || 0),
        agility: (gameState.stats.agility.base || 1.0) + (gameState.stats.agility.bonus || 0) + (gameState.stats.agility.potentialBonus || 0)
    };

    // Determine first turn
    currentTurn = getTurnOrder(player.agility, enemy.agility);
    battleLog.push({ 
        text: `Bắt đầu chiến đấu với ${enemy.name}! ${currentTurn === 'player' ? 'Bạn tấn công trước.' : 'Quái vật tấn công trước.'}`, 
        type: 'normal' 
    });

    // Update HTML display
    updateBattleDisplay();
    
    // Add click events for character info
    const enemyImg = document.getElementById('enemy-img');
    const playerImg = document.getElementById('player-img');
    
    if (enemyImg) {
        enemyImg.onclick = () => showEnemyInfoInBattle();
    }
    
    if (playerImg) {
        playerImg.onclick = () => showPlayerInfoInBattle();
    }
    
    // Start battle loop with delay
    console.log('🚀 Starting battle loop in 1 second...');
    setTimeout(() => {
        if (battleActive) {
            console.log('⚔️ Battle loop starting, current turn:', currentTurn);
            battleLoop();
        } else {
            console.log('❌ Battle not active, cannot start loop');
        }
    }, 1000);
}

// Get turn order function
function getTurnOrder(playerAgility, enemyAgility) {
    return playerAgility >= enemyAgility ? 'player' : 'enemy';
}

// Show enemy info in battle
function showEnemyInfoInBattle() {
    if (!enemy) return;
    
    console.log('📋 Showing enemy info in battle:', enemy.name);
    
    const infoDiv = document.createElement('div');
    infoDiv.className = 'battle-character-info';
    infoDiv.innerHTML = `
        <div class="character-info-header">
            <h4>${enemy.name}</h4>
            <button class="close-btn" onclick="closeEnemyInfoInBattle()">×</button>
        </div>
        <div class="character-info-content">
            <div class="character-image">
                <img src="${enemy.image}" alt="${enemy.name}" onerror="this.style.display='none'; this.parentNode.innerHTML='${enemy.name}';">
            </div>
            <div class="character-stats">
                <div class="stat-row">
                    <span class="stat-label">Tấn công Vật Lý:</span>
                    <span class="stat-value">${enemy.physicalDamage}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Tấn công Phép Thuật:</span>
                    <span class="stat-value">${enemy.magicDamage}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Chí mạng:</span>
                    <span class="stat-value">${enemy.criticalChance}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Sát thương chí mạng:</span>
                    <span class="stat-value">${enemy.criticalDamage}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Sinh lực:</span>
                    <span class="stat-value">${Math.floor(enemy.hp)}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Phòng thủ Vật Lý:</span>
                    <span class="stat-value">${Math.round((enemy.physicalDefense / (enemy.physicalDefense + 100)) * 100)}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Phòng thủ Phép Thuật:</span>
                    <span class="stat-value">${Math.round((enemy.magicDefense / (enemy.magicDefense + 100)) * 100)}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Nhanh nhẹn:</span>
                    <span class="stat-value">${enemy.agility}</span>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing info
    const existingInfo = document.querySelector('.battle-character-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    // Add to battle section
    const battleSection = document.getElementById('battle-section');
    if (battleSection) {
        battleSection.appendChild(infoDiv);
    }
}

// Show player info in battle
function showPlayerInfoInBattle() {
    if (!player) return;
    
    console.log('📋 Showing player info in battle');
    
    const infoDiv = document.createElement('div');
    infoDiv.className = 'battle-character-info';
    infoDiv.innerHTML = `
        <div class="character-info-header">
            <h4>Thông Tin Người Chơi</h4>
            <button class="close-btn" onclick="closePlayerInfoInBattle()">×</button>
        </div>
        <div class="character-info-content">
            <div class="character-image">
                <img src="images/player_1.png" alt="Người chơi" onerror="this.style.display='none'; this.parentNode.innerHTML='Người chơi';">
            </div>
            <div class="character-stats">
                <div class="stat-row">
                    <span class="stat-label">Tấn công Vật Lý:</span>
                    <span class="stat-value">${player.physicalDamage || 100}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Tấn công Phép Thuật:</span>
                    <span class="stat-value">${player.magicDamage || 50}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Chí mạng:</span>
                    <span class="stat-value">${player.criticalChance || 10}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Sát thương chí mạng:</span>
                    <span class="stat-value">${player.criticalDamage || 200}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Sinh lực:</span>
                    <span class="stat-value">${Math.floor(player.hp)}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Phòng thủ Vật Lý:</span>
                    <span class="stat-value">${Math.round(((player.physicalDefense || 20) / ((player.physicalDefense || 20) + 100)) * 100)}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Phòng thủ Phép Thuật:</span>
                    <span class="stat-value">${Math.round(((player.magicDefense || 15) / ((player.magicDefense || 15) + 100)) * 100)}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Nhanh nhẹn:</span>
                    <span class="stat-value">${player.agility || 1.0}</span>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing info
    const existingInfo = document.querySelector('.battle-character-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    // Add to battle section
    const battleSection = document.getElementById('battle-section');
    if (battleSection) {
        battleSection.appendChild(infoDiv);
    }
}

function closeEnemyInfoInBattle() {
    console.log('❌ Closing enemy info in battle');
    const existingInfo = document.querySelector('.battle-character-info');
    if (existingInfo) {
        existingInfo.remove();
    }
}

function closePlayerInfoInBattle() {
    console.log('❌ Closing player info in battle');
    const existingInfo = document.querySelector('.battle-character-info');
    if (existingInfo) {
        existingInfo.remove();
    }
}

function closeItemRewardPopup() {
    console.log('Close item reward popup - placeholder');
}

// Export functions to window
window.startBattle = startBattle;
window.loadBattleAssets = loadBattleAssets;
window.battleActive = () => battleActive;
window.closeEnemyInfoInBattle = closeEnemyInfoInBattle;
window.closePlayerInfoInBattle = closePlayerInfoInBattle;
window.closeItemRewardPopup = closeItemRewardPopup;

// Add a global flag to verify the script loaded
window.battleJSLoaded = true;

console.log('✅ Enhanced battle.js loaded successfully');
console.log('🔧 startBattle function exported:', typeof window.startBattle === 'function');
console.log('🎯 All battle functions ready for use');

// Ensure startBattle is immediately available
if (typeof window.startBattle === 'function') {
    console.log('🚀 startBattle function is ready and available');
} else {
    console.error('❌ startBattle function failed to export properly');
}

// Additional debugging
console.log('🔍 Debug info:');
console.log('- window.startBattle type:', typeof window.startBattle);
console.log('- window.loadBattleAssets type:', typeof window.loadBattleAssets);
console.log('- window.battleActive type:', typeof window.battleActive);
console.log('- All functions exported successfully');