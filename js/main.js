// main.js - Enhanced initialization with 3 locations and 8 enemy levels

console.log('main.js được tải');

// Global variables
let gameInitialized = false;
let selectedEnemyIndex = null;
let selectedLocation = null;
let initializationTimeout = null;

// Location data
const locations = {
    huyen_thiet: {
        name: 'Cổ Mạch Khoáng',
        image: 'images/huyen_thiet.png',
        rewardType: 'Huyền Thiết',
        rewardBase: 8, // Cấp 1 = 8 huyền thiết
        rewardIncrement: 1 // Mỗi cấp tăng 1
    },
    vang: {
        name: 'Kim Long Bảo Tàng',
        image: 'images/vang.png',
        rewardType: 'Vàng',
        rewardBase: 100, // Cấp 1 = 100 vàng
        rewardIncrement: 157 // Mỗi cấp tăng 157 (cấp 8 = 1200)
    },
    exp: {
        name: 'Thông Thiên Tháp',
        image: 'images/exp.png',
        rewardType: 'Kinh Nghiệm',
        rewardBase: 100, // Cấp 1 = 100 exp
        rewardIncrement: 100 // Mỗi cấp tăng 100 (cấp 8 = 800)
    }
};

// Enhanced enemy data with 8 levels for each location
function getEnemies(locationType) {
    const location = locations[locationType];
    if (!location) return [];
    
    const enemies = [];
    for (let level = 1; level <= 8; level++) {
        const baseHP = 800 + (level - 1) * 400; // HP tăng dần
        const baseDamage = 80 + (level - 1) * 40; // Damage tăng dần
        const baseDefense = 15 + (level - 1) * 5; // Defense tăng dần
        
        const reward = location.rewardBase + (level - 1) * location.rewardIncrement;
        
        enemies.push({
            name: `Quái Vật Cấp ${level}`,
            image: `images/dungeon_${level}.png`,
            hp: baseHP,
            maxHp: baseHP,
            physicalDamage: baseDamage,
            magicDamage: Math.floor(baseDamage * 0.5),
            criticalChance: 5 + (level - 1) * 2,
            criticalDamage: 150 + (level - 1) * 10,
            physicalDefense: baseDefense,
            magicDefense: Math.floor(baseDefense * 0.8),
            agility: 0.8 + (level - 1) * 0.2,
            expReward: 50 + (level - 1) * 25,
            goldReward: 100 + (level - 1) * 50,
            spiritStonesReward: 50 + (level - 1) * 25,
            materialDrop: { 
                name: location.rewardType, 
                chance: 0.5 + (level - 1) * 0.05, 
                amount: reward 
            }
        });
    }
    
    return enemies;
}

// Show location selection
function showLocationSelection() {
    const locationSection = document.getElementById('select-location-section');
    const enemySection = document.getElementById('select-enemy-section');
    
    if (locationSection) {
        locationSection.classList.add('active');
        locationSection.classList.add('screen-transition');
    }
    
    if (enemySection) {
        enemySection.classList.remove('active');
        enemySection.classList.add('screen-transition');
    }
    
    // Remove animation class after animation completes
    setTimeout(() => {
        if (locationSection && locationSection.classList) {
            locationSection.classList.remove('screen-transition');
        }
        if (enemySection && enemySection.classList) {
            enemySection.classList.remove('screen-transition');
        }
    }, 500);
    
    selectedLocation = null;
    selectedEnemyIndex = null;
    console.log('Showing location selection');
}

// Show enemy selection for specific location
function showEnemySelection(locationType) {
    selectedLocation = locationType;
    const location = locations[locationType];
    
    if (!location) {
        console.error('Invalid location type:', locationType);
        return;
    }
    
    // Update location title
    const titleElement = document.getElementById('location-title');
    if (titleElement) {
        titleElement.textContent = `Chọn Quái Vật - ${location.name}`;
    }
    
    // Generate enemy options
    const enemies = getEnemies(locationType);
    const enemyContainer = document.getElementById('enemy-container');
    
    if (enemyContainer) {
        enemyContainer.innerHTML = '';
        
        enemies.forEach((enemy, index) => {
            const enemyElement = document.createElement('div');
            enemyElement.className = 'enemy-option';
            enemyElement.id = `enemy-${index}`;
            enemyElement.onclick = () => showEnemyInfo(index);
            
            enemyElement.innerHTML = `
                <div class="enemy-img">
                    <img src="${enemy.image}" alt="${enemy.name}" onerror="this.style.display='none'; this.parentNode.innerHTML='Dungeon ${index + 1}';">
                </div>
                <span>${enemy.name}</span>
                <div class="enemy-stats">HP: ${enemy.hp} | Vật lý: ${enemy.physicalDamage} | Phép thuật: ${enemy.magicDamage}</div>
            `;
            
            enemyContainer.appendChild(enemyElement);
        });
    }
    
    // Switch sections
    const locationSection = document.getElementById('select-location-section');
    const enemySection = document.getElementById('select-enemy-section');
    
    if (locationSection) {
        locationSection.classList.remove('active');
        locationSection.classList.add('screen-transition');
    }
    
    if (enemySection) {
        enemySection.classList.add('active');
        enemySection.classList.add('screen-transition');
    }
    
    // Remove animation class after animation completes
    setTimeout(() => {
        if (locationSection && locationSection.classList) {
            locationSection.classList.remove('screen-transition');
        }
        if (enemySection && enemySection.classList) {
            enemySection.classList.remove('screen-transition');
        }
    }, 500);
    
    console.log('Showing enemy selection for location:', locationType);
}

// Enhanced show enemy info with detailed stats
function showEnemyInfo(index) {
    if (selectedLocation === null) {
        console.error('No location selected');
        return;
    }
    
    const enemies = getEnemies(selectedLocation);
    
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
                <span class="stat-value">${enemy.hp}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Phòng thủ Vật Lý:</span>
                <span class="stat-value">${enemy.physicalDefense}%</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Phòng thủ Phép Thuật:</span>
                <span class="stat-value">${enemy.magicDefense}%</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Nhanh nhẹn:</span>
                <span class="stat-value">${enemy.agility}</span>
            </div>
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

// Back to enemy selection
function backToEnemySelect() {
    const sections = ['enemy-info-section', 'battle-section'];
    const enemySection = document.getElementById('select-enemy-section');
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.classList.remove('active');
            section.classList.add('screen-transition');
        }
    });
    
    if (enemySection) {
        enemySection.classList.add('active');
        enemySection.classList.add('screen-transition');
        // Remove animation class after animation completes
        setTimeout(() => {
            if (enemySection.classList) {
                enemySection.classList.remove('screen-transition');
            }
        }, 500);
    }
    
    selectedEnemyIndex = null;
    
    // Show title and stats again when returning from battle
    const mainTitle = document.getElementById('main-title');
    const statsDisplay = document.getElementById('stats-display');
    if (mainTitle) mainTitle.style.display = 'block';
    if (statsDisplay) statsDisplay.style.display = 'block';
    
    console.log('Returned to enemy selection');
}

// Back to location selection
function backToLocationSelect() {
    const sections = ['enemy-info-section', 'battle-section', 'select-enemy-section'];
    const locationSection = document.getElementById('select-location-section');
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.classList.remove('active');
            section.classList.add('screen-transition');
        }
    });
    
    if (locationSection) {
        locationSection.classList.add('active');
        locationSection.classList.add('screen-transition');
        // Remove animation class after animation completes
        setTimeout(() => {
            if (locationSection.classList) {
                locationSection.classList.remove('screen-transition');
            }
        }, 500);
    }
    
    selectedLocation = null;
    selectedEnemyIndex = null;
    
    // Show title and stats again when returning to location selection
    const mainTitle = document.getElementById('main-title');
    const statsDisplay = document.getElementById('stats-display');
    if (mainTitle) mainTitle.style.display = 'block';
    if (statsDisplay) statsDisplay.style.display = 'block';
    
    console.log('Returned to location selection');
}

// Start attack with enhanced validation
function startAttack() {
    if (selectedEnemyIndex === null || selectedLocation === null) {
        console.error('No enemy or location selected');
        alert('Lỗi: Chưa chọn quái vật hoặc địa điểm!');
        return;
    }
    
    console.log('Starting enhanced battle with enemy index:', selectedEnemyIndex, 'at location:', selectedLocation);
    
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
    
    // Hide title and stats when entering battle
    const mainTitle = document.getElementById('main-title');
    const statsDisplay = document.getElementById('stats-display');
    if (mainTitle) mainTitle.style.display = 'none';
    if (statsDisplay) statsDisplay.style.display = 'none';
    
    // Start enhanced battle with canvas - with retry mechanism
    console.log('Attempting to start battle, checking startBattle availability...');
    console.log('window.startBattle type:', typeof window.startBattle);
    
    function tryStartBattle(retryCount = 0) {
        if (typeof window.startBattle === 'function') {
            console.log('startBattle found, starting battle...');
            window.startBattle(selectedEnemyIndex, selectedLocation);
        } else if (retryCount < 20) { // Increased retry count
            console.log(`startBattle not ready, retrying... (${retryCount + 1}/20)`);
            setTimeout(() => tryStartBattle(retryCount + 1), 200); // Increased delay
        } else {
            console.error('startBattle function not available after retries');
            // Try to load battle.js dynamically if not available
            if (!document.querySelector('script[src*="battle.js"]')) {
                console.log('Attempting to load battle.js dynamically...');
                const script = document.createElement('script');
                script.src = 'js/battle.js';
                script.onload = () => {
                    if (typeof window.startBattle === 'function') {
                        window.startBattle(selectedEnemyIndex, selectedLocation);
                    } else {
                        alert('Lỗi: Chức năng chiến đấu chưa sẵn sàng!');
                        backToEnemySelect();
                    }
                };
                script.onerror = () => {
                    alert('Lỗi: Không thể tải chức năng chiến đấu!');
                    backToEnemySelect();
                };
                document.head.appendChild(script);
            } else {
                alert('Lỗi: Chức năng chiến đấu chưa sẵn sàng!');
                backToEnemySelect();
            }
        }
    }
    
    tryStartBattle();
}

// Initialize click events with enhanced error handling
function initializeClickEvents() {
    console.log('Initializing enhanced click events...');
    
    // Location selection events
    document.querySelectorAll('.location-option').forEach((option, index) => {
        option.addEventListener('click', () => {
            const locationType = option.getAttribute('data-location');
            showEnemySelection(locationType);
        });
    });
    
    // Back to location button
    const backToLocationBtn = document.getElementById('back-to-location-btn');
    if (backToLocationBtn) {
        backToLocationBtn.addEventListener('click', backToLocationSelect);
    }
    
    // Back to enemy selection button
    const backToSelectBtn = document.getElementById('back-to-select-btn');
    if (backToSelectBtn) {
        backToSelectBtn.addEventListener('click', backToEnemySelect);
    }
    
    // Attack button
    const attackBtn = document.getElementById('attack-btn');
    if (attackBtn) {
        attackBtn.addEventListener('click', startAttack);
    }
    
    // Return button from battle - REMOVED: No longer needed
    // const returnBtn = document.getElementById('return-btn');
    // if (returnBtn) {
    //     returnBtn.addEventListener('click', backToEnemySelect);
    // }
    
    console.log('Enhanced click events initialized successfully');
}

// Event handlers to prevent duplicate listeners - no longer needed
// function handleEnemy0Click() {
//     console.log('Enemy 0 clicked');
//     showEnemyInfo(0);
// }

// function handleEnemy1Click() {
//     console.log('Enemy 1 clicked');
//     showEnemyInfo(1);
// }

// Enhanced function checking with detailed logging
function checkRequiredFunctions() {
    const requiredFunctions = [
        'initializeGameState', 
        'updateDisplay'
        // 'startBattle' // Removed from required check as it's loaded from battle.js
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
        // updateEnemySelectionDisplay(); // This function is no longer needed
        
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
    if (typeof window.getEnemies !== 'function') missingItems.push('Enemy System');
    
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
            <p>Enemy System: ${typeof window.getEnemies === 'function' ? 'OK' : 'Missing'}</p>
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

// Make functions and variables globally accessible
window.showEnemyInfo = showEnemyInfo;
window.backToSelect = backToEnemySelect; // Renamed to backToEnemySelect
window.startAttack = startAttack;
window.initializeGame = initializeGame;
window.retryInitialization = retryInitialization;
window.getEnemies = getEnemies;
window.locations = locations;
// window.updateEnemySelectionDisplay = updateEnemySelectionDisplay; // This function is no longer needed

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