// battle.js - Enhanced Battle System with Detailed Enemy Stats

console.log('battle.js được tải');

// Battle System Variables
let player, enemy;
let battleActive = false;
let battleLog = [];
let currentTurn = null;
let damageDisplay = [];
let playerShake = false;
let enemyShake = false;

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

// Start battle function
function startBattle(index) {
    console.log('Bắt đầu chiến đấu với quái:', index);
    
    if (index < 0 || index >= battleEnemies.length) {
        console.error('Lỗi: Chỉ số quái không hợp lệ:', index);
        alert('Lỗi: Không thể chọn quái vật!');
        return;
    }

    // Create enemy copy and reset HP
    enemy = { ...battleEnemies[index] };
    enemy.hp = enemy.maxHp;
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

    // Start battle loop with delay
    setTimeout(() => {
        if (battleActive) {
            battleLoop();
        }
    }, 1000);
}

// Battle loop
function battleLoop() {
    if (!battleActive) return;
    
    if (currentTurn === 'player') {
        playerAttack();
    } else {
        enemyAttack();
    }
}

// Player attack
function playerAttack() {
    if (!battleActive || currentTurn !== 'player') return;

    // Enemy shake effect
    enemyShake = true;
    setTimeout(() => {
        enemyShake = false;
    }, 500);

    // Calculate damage
    const useMagic = Math.random() < 0.3 && player.magicDamage > 0;
    const damageResult = calculateDamage(player, enemy, useMagic);
    const { damage, isCrit, damageType } = damageResult;
    
    enemy.hp = Math.max(0, enemy.hp - damage);
    
    const attackTypeText = damageType === 'magic' ? ' (Phép thuật)' : ' (Vật lý)';
    battleLog.push({ 
        text: `Bạn gây ${damage} sát thương${isCrit ? ' chí mạng' : ''}${attackTypeText} cho ${enemy.name}!`, 
        type: isCrit ? 'critical' : 'normal' 
    });
    
    // Damage display position: enemy is on top, so damage shows at top
    damageDisplay.push({ 
        damage, 
        isCrit, 
        x: 250, 
        y: 180, 
        time: 2.0,
        type: 'enemy'
    });

    // Check if enemy is dead
    if (enemy.hp <= 0) {
        battleLog.push({ text: `Bạn đã đánh bại ${enemy.name}!`, type: 'victory' });
        endBattle(true);
        return;
    }

    // Switch turns
    currentTurn = 'enemy';
    setTimeout(() => {
        if (battleActive) {
            battleLoop();
        }
    }, 1500);
}

// Enemy attack
function enemyAttack() {
    if (!battleActive || currentTurn !== 'enemy') return;

    // Player shake effect
    playerShake = true;
    setTimeout(() => {
        playerShake = false;
    }, 500);

    // Calculate damage - enemies can use both physical and magic damage
    const useMagic = enemy.magicDamage > 0 && Math.random() < 0.4;
    const damageResult = calculateDamage(enemy, player, useMagic);
    const { damage, isCrit, damageType } = damageResult;
    
    player.hp = Math.max(0, player.hp - damage);
    
    const attackTypeText = damageType === 'magic' ? ' (Phép thuật)' : ' (Vật lý)';
    battleLog.push({ 
        text: `${enemy.name} gây ${damage} sát thương${isCrit ? ' chí mạng' : ''}${attackTypeText} cho bạn!`, 
        type: isCrit ? 'critical' : 'normal' 
    });
    
    // Damage display position: player is at bottom, so damage shows at bottom
    damageDisplay.push({ 
        damage, 
        isCrit, 
        x: 250, 
        y: 480, 
        time: 2.0,
        type: 'player'
    });

    // Check if player is dead
    if (player.hp <= 0) {
        battleLog.push({ text: 'Bạn đã bị đánh bại!', type: 'defeat' });
        endBattle(false);
        return;
    }

    // Switch turns
    currentTurn = 'player';
    setTimeout(() => {
        if (battleActive) {
            battleLoop();
        }
    }, 1500);
}

// End battle
function endBattle(playerWon) {
    battleActive = false;
    
    // Get current game state
    let gameState;
    if (typeof window.gameState !== 'undefined' && window.gameState) {
        gameState = window.gameState;
    } else if (typeof window.initializeGameState === 'function') {
        gameState = window.initializeGameState();
    } else {
        console.error('Không thể truy cập gameState trong endBattle!');
        return;
    }
    
    if (playerWon) {
        // Add rewards
        gameState.gold += enemy.goldReward;
        gameState.spiritStones += enemy.spiritStonesReward;
        
        // Check material drop
        if (Math.random() < enemy.materialDrop.chance) {
            if (!gameState.materials) {
                gameState.materials = {};
            }
            gameState.materials[enemy.materialDrop.name] = (gameState.materials[enemy.materialDrop.name] || 0) + enemy.materialDrop.amount;
            battleLog.push({ text: `Nhận được ${enemy.materialDrop.amount} ${enemy.materialDrop.name}!`, type: 'reward' });
        }
        
        // Gain experience
        if (typeof window.gainExp === 'function') {
            window.gainExp(enemy.expReward);
        } else {
            // Fallback exp gain
            gameState.exp = (gameState.exp || 0) + enemy.expReward;
            while (gameState.exp >= gameState.maxExp) {
                gameState.level = (gameState.level || 1) + 1;
                gameState.exp -= gameState.maxExp;
                gameState.potentialPoints = (gameState.potentialPoints || 0) + 5;
                // Update level tier and maxExp based on new level
                if (gameState.level <= 13) {
                    gameState.levelTier = "Luyện Khí";
                    gameState.levelSubTier = `Tầng ${gameState.level}`;
                    gameState.maxExp = gameState.level * 100;
                }
            }
        }
        
        battleLog.push({ 
            text: `Bạn nhận được ${enemy.goldReward} Kim tệ, ${enemy.spiritStonesReward} Linh thạch, và ${enemy.expReward} kinh nghiệm!`, 
            type: 'reward' 
        });
        
        setTimeout(() => {
            alert(`Chiến thắng! Nhận được ${enemy.goldReward} Kim tệ, ${enemy.spiritStonesReward} Linh thạch, ${enemy.expReward} kinh nghiệm!`);
        }, 1000);
    } else {
        setTimeout(() => {
            alert('Thất bại! Bạn đã bị đánh bại.');
        }, 1000);
    }

    // Save game state
    if (typeof window.saveGameState === 'function') {
        window.saveGameState();
    }
    if (typeof window.updateDisplay === 'function') {
        window.updateDisplay();
    }

    // Show return button and auto return after 3 seconds
    showReturnButton();
    setTimeout(() => {
        returnToSelection();
    }, 3000);
}

// Show return button
function showReturnButton() {
    let returnBtn = document.getElementById('return-btn');
    if (returnBtn && returnBtn.style) {
        returnBtn.style.display = 'block';
    }
}

// Return to selection
function returnToSelection() {
    const selectSection = document.getElementById('select-enemy-section');
    const battleSection = document.getElementById('battle-section');
    const enemyInfoSection = document.getElementById('enemy-info-section');
    const returnBtn = document.getElementById('return-btn');
    
    if (selectSection && battleSection && enemyInfoSection) {
        battleSection.classList.remove('active');
        enemyInfoSection.classList.remove('active');
        selectSection.classList.add('active');
    }
    
    if (returnBtn && returnBtn.style) {
        returnBtn.style.display = 'none';
    }
    
    // Reset battle state
    battleActive = false;
    battleLog = [];
    damageDisplay = [];
    player = null;
    enemy = null;
    currentTurn = null;
    playerShake = false;
    enemyShake = false;
    
    console.log('Quay lại màn chọn quái');
}

// Update battle log display
function updateBattleLogDisplay() {
    const logDiv = document.getElementById('battle-log');
    if (logDiv && battleLog.length > 0) {
        logDiv.innerHTML = battleLog.slice(-8).map(entry => {
            let className = 'normal-attack';
            if (entry.type === 'critical') className = 'critical-attack';
            else if (entry.type === 'victory') className = 'victory-text';
            else if (entry.type === 'defeat') className = 'defeat-text';
            else if (entry.type === 'reward') className = 'reward-text';
            
            return `<p class="${className}">${entry.text}</p>`;
        }).join('');
        logDiv.scrollTop = logDiv.scrollHeight;
    }
}

// P5.js functions - Enhanced visual system
let backgroundLoaded = false;
let playerImg, enemy1Img, enemy2Img;

function loadBattleAssets() {
    console.log('Loading battle assets...');
    
    // Load background image using regular Image objects
    const bgImg = new Image();
    bgImg.onload = function() {
        console.log('Background image loaded successfully');
        backgroundLoaded = true;
        // Convert to p5.js image when p5 is ready
        if (typeof loadImage === 'function') {
            loadImage('images/phong_canh_1.png', (img) => {
                window.battleBackground = img;
            });
        }
    };
    bgImg.onerror = function() {
        console.error('Failed to load background image');
        backgroundLoaded = false;
    };
    bgImg.src = 'images/phong_canh_1.png';
    
    // Load player image
    const playerImage = new Image();
    playerImage.onload = function() {
        console.log('Player image loaded');
        if (typeof loadImage === 'function') {
            loadImage('images/player_1.png', (img) => {
                playerImg = img;
            });
        }
    };
    playerImage.onerror = function() {
        console.error('Failed to load player image');
    };
    playerImage.src = 'images/player_1.png';
    
    // Load enemy images
    const enemy1Image = new Image();
    enemy1Image.onload = function() {
        console.log('Enemy 1 image loaded');
        if (typeof loadImage === 'function') {
            loadImage('images/dungeon_1.png', (img) => {
                enemy1Img = img;
            });
        }
    };
    enemy1Image.onerror = function() {
        console.error('Failed to load enemy 1 image');
    };
    enemy1Image.src = 'images/dungeon_1.png';
    
    const enemy2Image = new Image();
    enemy2Image.onload = function() {
        console.log('Enemy 2 image loaded');
        if (typeof loadImage === 'function') {
            loadImage('images/dungeon_2.png', (img) => {
                enemy2Img = img;
            });
        }
    };
    enemy2Image.onerror = function() {
        console.error('Failed to load enemy 2 image');
    };
    enemy2Image.src = 'images/dungeon_2.png';
}

function preload() {
    // This will be called by p5.js if available
    if (typeof loadImage === 'function') {
        console.log('Using p5.js preload...');
        loadImage('images/phong_canh_1.png', 
            (img) => {
                window.battleBackground = img;
                backgroundLoaded = true;
                console.log('Background loaded via p5.js');
            },
            (err) => {
                console.error('Failed to load background via p5.js:', err);
            }
        );
        
        loadImage('images/player_1.png', (img) => { playerImg = img; });
        loadImage('images/dungeon_1.png', (img) => { enemy1Img = img; });
        loadImage('images/dungeon_2.png', (img) => { enemy2Img = img; });
    }
}

function setup() {
    console.log('Khởi tạo canvas chiến đấu...');
    try {
        const canvas = createCanvas(500, 600);
        canvas.parent('battle-canvas');
        console.log('Canvas created successfully');
        
        // Load assets after canvas is ready
        setTimeout(() => {
            loadBattleAssets();
        }, 100);
    } catch (error) {
        console.error('Error creating canvas:', error);
    }
}

function draw() {
    // Only draw when battle is active
    if (!battleActive) {
        // Blank screen when not battling
        background(20, 20, 30);
        fill(255, 255, 255, 100);
        textAlign(CENTER, CENTER);
        textSize(18);
        text('Sẵn sàng chiến đấu', width/2, height/2);
        return;
    }

    // Draw background
    if (backgroundLoaded && window.battleBackground && window.battleBackground.width) {
        try {
            let bg = window.battleBackground;
            let scale = height / bg.height;
            let scaledWidth = bg.width * scale;
            
            if (scaledWidth >= width) {
                let offsetX = (scaledWidth - width) / 2;
                image(bg, -offsetX, 0, scaledWidth, height);
            } else {
                let offsetX = (width - scaledWidth) / 2;
                image(bg, offsetX, 0, scaledWidth, height);
            }
        } catch (error) {
            console.error('Error drawing background:', error);
            // Draw fallback background
            background(35, 33, 54);
        }
    } else {
        // Fallback gradient background
        background(35, 33, 54);
        for (let y = 0; y < height; y += 2) {
            let inter = map(y, 0, height, 0, 1);
            let c = lerpColor(color(50, 50, 80), color(20, 30, 60), inter);
            stroke(c);
            line(0, y, width, y);
        }
    }
    
    // Add battle overlay
    fill(0, 0, 0, 50);
    rect(0, 0, width, height);

    // Draw enemy image at top with shake effect
    if (enemy) {
        let enemyImg = enemy.name.includes('Cấp 1') ? enemy1Img : enemy2Img;
        if (enemyImg && enemyImg.width) {
            try {
                let enemySize = 120;
                let enemyX = (width - enemySize) / 2;
                let enemyY = 80;
                
                // Apply shake effect
                if (enemyShake) {
                    enemyX += random(-8, 8);
                    enemyY += random(-8, 8);
                }
                
                image(enemyImg, enemyX, enemyY, enemySize, enemySize);
            } catch (error) {
                console.error('Error drawing enemy image:', error);
                // Draw fallback
                fill(255, 100, 100, 100);
                rect((width - 120) / 2, 80, 120, 120, 10);
                textAlign(CENTER, CENTER);
                textSize(14);
                fill(255);
                text(enemy.name, width/2, 140);
            }
        } else {
            // Fallback if image not loaded
            fill(255, 100, 100, 100);
            rect((width - 120) / 2, 80, 120, 120, 10);
            textAlign(CENTER, CENTER);
            textSize(14);
            fill(255);
            text(enemy.name, width/2, 140);
        }
    }
    
    // Draw player image at bottom with shake effect
    if (playerImg && playerImg.width) {
        try {
            let playerSize = 100;
            let playerX = (width - playerSize) / 2;
            let playerY = height - 180;
            
            // Apply shake effect
            if (playerShake) {
                playerX += random(-8, 8);
                playerY += random(-8, 8);
            }
            
            image(playerImg, playerX, playerY, playerSize, playerSize);
        } catch (error) {
            console.error('Error drawing player image:', error);
            // Draw fallback
            fill(100, 200, 255, 100);
            rect((width - 100) / 2, height - 180, 100, 100, 10);
            textAlign(CENTER, CENTER);
            textSize(12);
            fill(100, 200, 255);
            text('Người chơi', width/2, height - 130);
        }
    } else {
        // Fallback if image not loaded
        fill(100, 200, 255, 100);
        rect((width - 100) / 2, height - 180, 100, 100, 10);
        textAlign(CENTER, CENTER);
        textSize(12);
        fill(100, 200, 255);
        text('Người chơi', width/2, height - 130);
    }

    // Draw health bars positioned below character images
    drawHealthBars();
    
    // Draw damage numbers
    drawDamageNumbers();
    
    // Update battle log
    updateBattleLogDisplay();
}

function drawHealthBars() {
    if (!player || !enemy) return;
    
    // Enemy health bar (below enemy image)
    let enemyBarX = 50;
    let enemyBarY = 220; // Below enemy image
    let barWidth = 200;
    let barHeight = 20;
    
    // Background
    fill(100, 0, 0);
    rect(enemyBarX, enemyBarY, barWidth, barHeight);
    
    // Health fill
    fill(0, 200, 0);
    let enemyHealthWidth = map(enemy.hp, 0, enemy.maxHp, 0, barWidth);
    rect(enemyBarX, enemyBarY, enemyHealthWidth, barHeight);
    
    // Health text
    fill(255);
    textAlign(LEFT, TOP);
    textSize(12);
    text(`${enemy.name}: ${Math.floor(enemy.hp)}/${Math.floor(enemy.maxHp)}`, enemyBarX, enemyBarY - 18);

    // Player health bar (below player image)
    let playerBarX = 50;
    let playerBarY = height - 60; // Below player image
    
    // Background
    fill(100, 0, 0);
    rect(playerBarX, playerBarY, barWidth, barHeight);
    
    // Health fill
    fill(0, 200, 0);
    let playerHealthWidth = map(player.hp, 0, player.maxHp, 0, barWidth);
    rect(playerBarX, playerBarY, playerHealthWidth, barHeight);
    
    // Health text
    textAlign(LEFT, BOTTOM);
    text(`Người chơi: ${Math.floor(player.hp)}/${Math.floor(player.maxHp)}`, playerBarX, playerBarY - 2);
}

function drawDamageNumbers() {
    damageDisplay = damageDisplay.filter(d => d.time > 0);
    damageDisplay.forEach(d => {
        // Color based on damage type and critical
        if (d.isCrit) {
            fill(color(255, 50, 50));
            textSize(28);
        } else if (d.type === 'enemy') {
            fill(color(255, 255, 100));
            textSize(20);
        } else {
            fill(color(255, 150, 150));
            textSize(20);
        }
        
        textAlign(CENTER, CENTER);
        textStyle(BOLD);
        
        // Add outline for better visibility
        stroke(0);
        strokeWeight(2);
        text(`-${d.damage}${d.isCrit ? ' CRIT!' : ''}`, d.x, d.y);
        noStroke();
        
        // Move damage number up and fade
        d.y -= 2;
        d.time -= 1/60;
        
        // Add some random horizontal drift
        d.x += random(-0.5, 0.5);
    });
}

function getTurnOrder(playerAgility, enemyAgility) {
    return playerAgility >= enemyAgility ? 'player' : 'enemy';
}

// Export functions to window
window.startBattle = startBattle;
window.returnToSelection = returnToSelection;
window.loadBattleAssets = loadBattleAssets;
window.preload = preload;
window.setup = setup;
window.draw = draw;
window.battleActive = () => battleActive;

console.log('Enhanced battle.js loaded successfully');