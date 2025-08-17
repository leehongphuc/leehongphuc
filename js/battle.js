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
    
    return {
        damage: Math.floor(damage),
        isCrit,
        damageType: useMagic ? 'magic' : 'physical'
    };
}

// Update HTML battle display
function updateBattleDisplay() {
    if (!player || !enemy) return;
    
    // Update enemy image and HP
    const enemyImg = document.getElementById('enemy-img');
    const enemyHpFill = document.getElementById('enemy-hp-fill');
    const enemyHpText = document.getElementById('enemy-hp-text');
    
    if (enemyImg) {
        enemyImg.src = enemy.image;
        enemyImg.style.display = 'block';
    }
    
    if (enemyHpFill && enemyHpText) {
        const enemyHpPercent = (enemy.hp / enemy.maxHp) * 100;
        enemyHpFill.style.width = enemyHpPercent + '%';
        enemyHpText.textContent = `${enemy.name}: ${Math.floor(enemy.hp)}/${enemy.maxHp}`;
    }
    
    // Update player HP
    const playerHpFill = document.getElementById('player-hp-fill');
    const playerHpText = document.getElementById('player-hp-text');
    
    if (playerHpFill && playerHpText) {
        const playerHpPercent = (player.hp / player.maxHp) * 100;
        playerHpFill.style.width = playerHpPercent + '%';
        playerHpText.textContent = `Người chơi: ${Math.floor(player.hp)}/${player.maxHp}`;
    }
}

// Battle loop function
function battleLoop() {
    if (!battleActive || !player || !enemy) return;
    
    if (currentTurn === 'player') {
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
        
        // Enemy shake effect
        enemyShake = true;
        setTimeout(() => { enemyShake = false; }, 500);
        
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
        setTimeout(() => {
            if (battleActive) {
                enemyTurn();
            }
        }, 1000);
        
    } else {
        // Enemy's turn
        enemyTurn();
    }
    
    // Update display
    updateBattleDisplay();
}

// Enemy turn function
function enemyTurn() {
    if (!battleActive || !player || !enemy) return;
    
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
    
    // Player shake effect
    playerShake = true;
    setTimeout(() => { playerShake = false; }, 500);
    
    battleLog.push({ 
        text: `${enemy.name} gây ${damage.damage} sát thương${damage.isCrit ? ' CHÍ MẠNG!' : ''} cho bạn!`, 
        type: 'enemy' 
    });
    
    currentTurn = 'player';
    
    // Check if player is defeated
    if (player.hp <= 0) {
        endBattle(false);
        return;
    }
    
    // Update display
    updateBattleDisplay();
}

// End battle function
function endBattle(playerWon) {
    battleActive = false;
    
    if (playerWon) {
        battleLog.push({ 
            text: `🎉 Bạn đã đánh bại ${enemy.name}!`, 
            type: 'victory' 
        });
        
        // Add rewards
        const gameState = window.gameState || window.initializeGameState();
        if (gameState) {
            gameState.stats.exp.current += enemy.expReward || 50;
            gameState.stats.gold.current += enemy.goldReward || 100;
            gameState.stats.spiritStones.current += enemy.spiritStonesReward || 50;
            
            // Update display
            if (typeof window.updateDisplay === 'function') {
                window.updateDisplay();
            }
        }
        
        alert(`🎉 Chiến thắng! Bạn nhận được:\nKinh nghiệm: +${enemy.expReward || 50}\nKim tệ: +${enemy.goldReward || 100}\nLinh thạch: +${enemy.spiritStonesReward || 50}`);
    } else {
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
    setTimeout(() => {
        if (battleActive) {
            battleLoop();
        }
    }, 1000);
}

// Get turn order function
function getTurnOrder(playerAgility, enemyAgility) {
    return playerAgility >= enemyAgility ? 'player' : 'enemy';
}

// Placeholder functions for compatibility
function showEnemyInfoInBattle() {
    console.log('Enemy info in battle - placeholder');
}

function showPlayerInfoInBattle() {
    console.log('Player info in battle - placeholder');
}

function closeEnemyInfoInBattle() {
    console.log('Close enemy info in battle - placeholder');
}

function closePlayerInfoInBattle() {
    console.log('Close player info in battle - placeholder');
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