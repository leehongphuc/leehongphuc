// main.js - Enhanced initialization with detailed enemy stats

console.log('main.js được tải');

// Global variables
let gameInitialized = false;
let selectedEnemyIndex = null;
let initializationTimeout = null;

// Enhanced enemy data with detailed stats
function getEnemies() {
    return window.battleEnemies || [
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
}

// Enhanced show enemy info with detailed stats
function showEnemyInfo(index) {
    const enemies = getEnemies();
    
    if (index < 0 || index >= enemies.length) {
        console.error('Invalid enemy index:', index);
        return;
    }
    
    selectedEnemyIndex = index;
    const enemy = enemies[index];
    
    // Update enemy info display
    const enemyImgElement = document.querySelector('#selected-enemy-img img');
    if (enemyImgElement) {
        enemyImgElement.src = enemy.image;
        enemyImgElement.alt = enemy.name;
    }
    
    const enemyNameElement = document.getElementById('selected-enemy-name');
    if (enemyNameElement) {
        enemyNameElement.textContent = enemy.name;
    }
    
    // Enhanced stats display with all combat stats
    const statsDiv = document.getElementById('selected-enemy-stats');
    if (statsDiv) {
        statsDiv.innerHTML = `
            <p>Sinh lực: <span>${enemy.hp}/${enemy.maxHp}</span></p>
            <p>Tấn công Vật Lý: <span>${enemy.physicalDamage}</span></p>
            <p>Tấn công Phép Thuật: <span>${enemy.magicDamage}</span></p>
            <p>Tỷ lệ Chí mạng: <span>${enemy.criticalChance}%</span></p>
            <p>Sát thương Chí mạng: <span>${enemy.criticalDamage}%</span></p>
            <p>Phòng thủ Vật Lý: <span>${enemy.physicalDefense}</span></p>
            <p>Phòng thủ Phép Thuật: <span>${enemy.magicDefense}</span></p>
            <p>Tốc độ: <span>${enemy.agility}</span></p>
        `;
    }
    
    // Switch sections with animation
    const selectSection = document.getElementById('select-enemy-section');
    const enemyInfoSection = document.getElementById('enemy-info-section');
    
    if (selectSection) {
        selectSection.classList.remove('active');
        selectSection.classList.add('screen-transition');
    }
    
    if (enemyInfoSection) {
        enemyInfoSection.classList.add('active');
        enemyInfoSection.classList.add('screen-transition');
        // Remove animation class after animation completes
        setTimeout(() => {
            if (enemyInfoSection.classList) {
                enemyInfoSection.classList.remove('screen-transition');
            }
        }, 500);
    }
    
    console.log('Showing enhanced enemy info for index:', index, 'Enemy:', enemy);
}

// Enhanced enemy selection display
function updateEnemySelectionDisplay() {
    const enemies = getEnemies();
    
    enemies.forEach((enemy, index) => {
        const enemyElement = document.getElementById(`enemy-${index === 0 ? '0' : '2'}`);
        if (enemyElement) {
            // Update enemy stats in selection screen
            const statsDiv = enemyElement.querySelector('.enemy-stats');
            if (statsDiv) {
                statsDiv.innerHTML = `
                    HP: ${enemy.hp} | Vật lý: ${enemy.physicalDamage} | Phép thuật: ${enemy.magicDamage}
                `;
            }
        }
    });
}

// Back to selection with animation
function backToSelect() {
    const sections = ['enemy-info-section', 'battle-section'];
    const selectSection = document.getElementById('select-enemy-section');
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.classList.remove('active');
            section.classList.add('screen-transition');
        }
    });
    
    if (selectSection) {
        selectSection.classList.add('active');
        selectSection.classList.add('screen-transition');
        // Remove animation class after animation completes
        setTimeout(() => {
            if (selectSection.classList) {
                selectSection.classList.remove('screen-transition');
            }
        }, 500);
    }
    
    selectedEnemyIndex = null;
    console.log('Returned to enemy selection');
}

// Start attack with enhanced validation
function startAttack() {
    if (selectedEnemyIndex === null) {
        console.error('No enemy selected');
        alert('Lỗi: Chưa chọn quái vật!');
        return;
    }
    
    console.log('Starting enhanced battle with enemy index:', selectedEnemyIndex);
    
    // Switch to battle section with animation
    const enemyInfoSection = document.getElementById('enemy-info-section');
    const battleSection = document.getElementById('battle-section');
    
    if (enemyInfoSection) {
        enemyInfoSection.classList.remove('active');
        enemyInfoSection.classList.add('screen-transition');
    }
    
    if (battleSection) {
        battleSection.classList.add('active');
        battleSection.classList.add('battle-start');
        // Remove animation class after animation completes
        setTimeout(() => {
            if (battleSection.classList) {
                battleSection.classList.remove('battle-start');
            }
        }, 800);
    }
    
    // Start enhanced battle with canvas
    if (typeof window.startBattle === 'function') {
        window.startBattle(selectedEnemyIndex);
    } else {
        console.error('startBattle function not available');
        alert('Lỗi: Chức năng chiến đấu chưa sẵn sàng!');
        backToSelect();
    }
}

// Initialize click events with enhanced error handling
function initializeClickEvents() {
    console.log('Initializing enhanced click events...');
    
    // Enemy selection events
    const enemy0 = document.getElementById('enemy-0');
    const enemy1 = document.getElementById('enemy-2');
    
    if (enemy0) {
        enemy0.removeEventListener('click', handleEnemy0Click);
        enemy0.addEventListener('click', handleEnemy0Click);
        console.log('Added enhanced click event for enemy-0');
    } else {
        console.warn('Enemy-0 element not found');
    }
    
    if (enemy1) {
        enemy1.removeEventListener('click', handleEnemy1Click);
        enemy1.addEventListener('click', handleEnemy1Click);
        console.log('Added enhanced click event for enemy-2');
    } else {
        console.warn('Enemy-2 element not found');
    }
    
    // Battle action events
    const attackBtn = document.getElementById('attack-btn');
    const backBtn = document.getElementById('back-to-select-btn');
    const returnBtn = document.getElementById('return-btn');
    
    if (attackBtn) {
        attackBtn.removeEventListener('click', startAttack);
        attackBtn.addEventListener('click', startAttack);
        console.log('Added enhanced click event for attack-btn');
    } else {
        console.warn('Attack button not found');
    }
    
    if (backBtn) {
        backBtn.removeEventListener('click', backToSelect);
        backBtn.addEventListener('click', backToSelect);
        console.log('Added enhanced click event for back-to-select-btn');
    } else {
        console.warn('Back button not found');
    }
    
    if (returnBtn) {
        returnBtn.removeEventListener('click', backToSelect);
        returnBtn.addEventListener('click', backToSelect);
        console.log('Added enhanced click event for return-btn');
    } else {
        console.warn('Return button not found');
    }
    
    console.log('Enhanced click events initialized successfully');
}

// Event handlers to prevent duplicate listeners
function handleEnemy0Click() {
    console.log('Enemy 0 clicked');
    showEnemyInfo(0);
}

function handleEnemy1Click() {
    console.log('Enemy 1 clicked');
    showEnemyInfo(1);
}

// Enhanced function checking with detailed logging
function checkRequiredFunctions() {
    const requiredFunctions = [
        'initializeGameState', 
        'updateDisplay',
        'startBattle',
        'battleEnemies'
    ];
    const missingFunctions = [];
    const availableFunctions = [];
    
    for (let funcName of requiredFunctions) {
        if (typeof window[funcName] !== 'function' && typeof window[funcName] !== 'object') {
            missingFunctions.push(funcName);
        } else {
            availableFunctions.push(funcName);
        }
    }
    
    console.log('Available functions:', availableFunctions);
    
    if (missingFunctions.length > 0) {
        console.error('Missing required functions:', missingFunctions);
        return false;
    }
    
    return true;
}

// Enhanced game initialization with better error handling
function initializeGame() {
    if (gameInitialized) {
        console.log('Game already initialized');
        return true;
    }
    
    console.log('Attempting enhanced game initialization...');
    
    // Check if required functions are available
    if (!checkRequiredFunctions()) {
        console.log('Required functions not yet available, retrying...');
        return false;
    }
    
    try {
        // Initialize game state
        console.log('Initializing game state...');
        const gameState = window.initializeGameState();
        
        if (!gameState) {
            console.error('initializeGameState returned null/undefined');
            return false;
        }
        
        console.log('Game state initialized successfully:', gameState);
        
        // Initialize click events
        console.log('Initializing click events...');
        initializeClickEvents();
        
        // Update displays
        console.log('Updating displays...');
        window.updateDisplay();
        updateEnemySelectionDisplay();
        
        gameInitialized = true;
        console.log('Enhanced game initialization complete successfully');
        return true;
        
    } catch (error) {
        console.error('Error during enhanced game initialization:', error);
        return false;
    }
}

// Enhanced initialization with exponential backoff and better logging
function startInitialization() {
    let attempts = 0;
    const maxAttempts = 25;
    const baseDelay = 100;
    
    function tryInitialize() {
        attempts++;
        console.log(`Enhanced initialization attempt ${attempts}/${maxAttempts}`);
        
        if (initializeGame()) {
            console.log(`Enhanced game initialized successfully after ${attempts} attempts`);
            if (initializationTimeout) {
                clearTimeout(initializationTimeout);
                initializationTimeout = null;
            }
            return;
        }
        
        if (attempts < maxAttempts) {
            const delay = baseDelay * Math.pow(1.3, attempts - 1);
            console.log(`Retrying enhanced initialization in ${delay}ms...`);
            
            initializationTimeout = setTimeout(tryInitialize, delay);
        } else {
            console.error(`Failed to initialize enhanced game after ${maxAttempts} attempts`);
            showInitializationError();
        }
    }
    
    tryInitialize();
}

// Enhanced error display with more detailed information
function showInitializationError() {
    const container = document.querySelector('.container');
    if (!container) {
        console.error('Container not found for enhanced error display');
        return;
    }
    
    const existingError = container.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = `
        background: rgba(255, 0, 0, 0.1);
        border: 2px solid rgba(255, 0, 0, 0.5);
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
        color: #ff6b6b;
        animation: errorPulse 2s ease-in-out infinite;
    `;
    
    // Check what's missing for better error message
    const missingItems = [];
    if (typeof window.initializeGameState !== 'function') missingItems.push('Game State');
    if (typeof window.startBattle !== 'function') missingItems.push('Battle System');
    if (typeof window.battleEnemies === 'undefined') missingItems.push('Enemy Data');
    
    errorDiv.innerHTML = `
        <h3>Lỗi khởi tạo game nâng cao</h3>
        <p>Không thể khởi tạo hệ thống game. Các thành phần thiếu: ${missingItems.join(', ')}</p>
        <p><small>Có thể do lỗi tải file JavaScript hoặc thứ tự tải file không đúng.</small></p>
        <div style="margin-top: 15px;">
            <button onclick="location.reload()" style="
                background: linear-gradient(45deg, #ff6b6b, #ee5a5a);
                border: none;
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                margin: 10px 8px;
                font-size: 1rem;
                font-weight: bold;
                transition: all 0.3s ease;
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                Tải lại trang
            </button>
            <button onclick="window.retryInitialization()" style="
                background: linear-gradient(45deg, #4CAF50, #45a049);
                border: none;
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                margin: 10px 8px;
                font-size: 1rem;
                font-weight: bold;
                transition: all 0.3s ease;
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                Thử lại
            </button>
        </div>
        <div style="margin-top: 10px; font-size: 0.9rem; color: #ccc;">
            <p>Thông tin debug:</p>
            <p>Game State: ${typeof window.initializeGameState === 'function' ? 'OK' : 'Missing'}</p>
            <p>Battle System: ${typeof window.startBattle === 'function' ? 'OK' : 'Missing'}</p>
            <p>Enemy Data: ${typeof window.battleEnemies !== 'undefined' ? 'OK' : 'Missing'}</p>
        </div>
    `;
    
    container.insertBefore(errorDiv, container.firstChild);
    
    // Add error pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes errorPulse {
            0%, 100% { 
                box-shadow: 0 0 10px rgba(255, 107, 107, 0.3);
                border-color: rgba(255, 0, 0, 0.5);
            }
            50% { 
                box-shadow: 0 0 25px rgba(255, 107, 107, 0.6);
                border-color: rgba(255, 0, 0, 0.8);
            }
        }
    `;
    document.head.appendChild(style);
}

// Enhanced retry initialization function
function retryInitialization() {
    console.log('Retrying enhanced initialization...');
    gameInitialized = false;
    
    if (initializationTimeout) {
        clearTimeout(initializationTimeout);
        initializationTimeout = null;
    }
    
    // Remove error message
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
    
    // Show loading indicator
    showLoadingIndicator();
    
    startInitialization();
}

// Show loading indicator during initialization
function showLoadingIndicator() {
    const container = document.querySelector('.container');
    if (!container) return;
    
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'loading-indicator';
    loadingDiv.style.cssText = `
        background: rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(255, 215, 0, 0.3);
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
        color: #ffd700;
    `;
    
    loadingDiv.innerHTML = `
        <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid rgba(255, 215, 0, 0.3); border-top: 4px solid #ffd700; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 15px;"></div>
        <p>Đang khởi tạo hệ thống game nâng cao...</p>
    `;
    
    container.insertBefore(loadingDiv, container.firstChild);
    
    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
    
    // Remove loading indicator after 5 seconds
    setTimeout(() => {
        const indicator = document.querySelector('.loading-indicator');
        if (indicator) indicator.remove();
    }, 5000);
}

// Make functions globally accessible
window.showEnemyInfo = showEnemyInfo;
window.backToSelect = backToSelect;
window.startAttack = startAttack;
window.initializeGame = initializeGame;
window.retryInitialization = retryInitialization;
window.updateEnemySelectionDisplay = updateEnemySelectionDisplay;

// Enhanced initialization strategies with better logging
console.log('Setting up enhanced initialization triggers...');

// Strategy 1: Immediate check if DOM is ready
if (document.readyState === 'loading') {
    console.log('DOM is still loading, waiting for DOMContentLoaded...');
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired, starting enhanced initialization...');
        setTimeout(startInitialization, 100);
    });
} else {
    console.log('DOM already loaded, starting enhanced initialization immediately...');
    setTimeout(startInitialization, 100);
}

// Strategy 2: Backup initialization on window load
window.addEventListener('load', function() {
    if (!gameInitialized) {
        console.log('Window load event fired, backup enhanced initialization triggered');
        setTimeout(startInitialization, 200);
    } else {
        console.log('Enhanced game already initialized, skipping backup initialization');
    }
});

// Strategy 3: Final fallback after reasonable delay
setTimeout(function() {
    if (!gameInitialized) {
        console.log('Final fallback enhanced initialization triggered after 3 seconds');
        startInitialization();
    } else {
        console.log('Enhanced game successfully initialized, no fallback needed');
    }
}, 3000);

// Strategy 4: Periodic check for very stubborn cases
let periodicCheck = setInterval(function() {
    if (gameInitialized) {
        clearInterval(periodicCheck);
        console.log('Enhanced game initialized, stopping periodic checks');
    } else if (document.readyState === 'complete') {
        console.log('Document complete but game not initialized, attempting emergency initialization');
        startInitialization();
    }
}, 2000);

// Clear periodic check after 30 seconds to avoid infinite checking
setTimeout(function() {
    if (periodicCheck) {
        clearInterval(periodicCheck);
        console.log('Stopped periodic initialization checks after 30 seconds');
    }
}, 30000);

console.log('Enhanced main.js loaded successfully - all initialization strategies set up');