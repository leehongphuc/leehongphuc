function initializeGameState() {
    if (!window.gameState) {
        console.warn('gameState not found in window. Initializing from game.js or localStorage.');
        // Đồng bộ với cấu trúc gameState trong game.js
        window.gameState = JSON.parse(localStorage.getItem('gameState')) || {
            level: 1,
            exp: 0,
            maxExp: 100,
            levelTier: "Luyện Khí",
            levelSubTier: "Tầng 1",
            potentialPoints: 0,
            stats: {
                physicalDamage: { base: 100, bonus: 0 },
                magicDamage: { base: 100, bonus: 0 },
                criticalChance: { base: 5, bonus: 0 },
                criticalDamage: { base: 200, bonus: 0 },
                hp: { base: 1000, bonus: 0 },
                physicalDefense: { base: 20, bonus: 0 },
                magicDefense: { base: 20, bonus: 0 },
                agility: { base: 1.0, bonus: 0 }
            },
            equipment: {
                weapon: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { physicalDamage: 0, magicDamage: 0, criticalChance: 0, agility: 0 }, enhanceLevel: 0 },
                necklace: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { hp: 0, criticalChance: 0 }, enhanceLevel: 0 },
                ring: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { criticalChance: 0 }, enhanceLevel: 0 },
                gloves: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { physicalDamage: 0 }, enhanceLevel: 0 },
                boots: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { agility: 0, hp: 0 }, enhanceLevel: 0 },
                armor: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { hp: 0, physicalDefense: 0, magicDefense: 0 }, enhanceLevel: 0 },
                helmet: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { hp: 0 }, enhanceLevel: 0 },
                belt: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { hp: 0 }, enhanceLevel: 0 },
                jade: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { hp: 0 }, enhanceLevel: 0 },
                artifact: { name: "", equipped: false, image: "", tier: "", quality: "", locked: false, stats: { physicalDefense: 0, magicDefense: 0 }, enhanceLevel: 0 }
            },
            inventory: [],
            gold: 100,
            spiritStones: 2000,
            claimedGifts: []
        };
    }
    return window.gameState;
}

function saveGameState() {
    try {
        localStorage.setItem('gameState', JSON.stringify(window.gameState));
        console.log('Game state saved:', JSON.stringify(window.gameState, null, 2));
    } catch (e) {
        console.error('Error saving game state:', e);
    }
}

function canEquipTier(tier) {
    const tiers = ["Luyện Khí", "Trúc Cơ", "Kim Đan", "Nguyên Anh", "Hóa Thần"];
    const requiredTier = {
        "Hạ Phẩm": 0,
        "Trung Phẩm": 1,
        "Tam Phẩm": 2,
        "Thượng Phẩm": 2,
        "Cực Phẩm": 3
    };
    return tiers.indexOf(window.gameState.levelTier) >= requiredTier[tier];
}

function claimGift(type, name, image, tier, quality, locked) {
    const gameState = initializeGameState();
    if (!window.giftBoxItems) {
        console.error('window.giftBoxItems is not defined! Ensure items.js is loaded and giftBoxItems is initialized.');
        alert('Lỗi: Không tìm thấy danh sách vật phẩm!');
        return;
    }
    const fullName = type === 'necklace' ? `Dây Chuyền` : type === 'weapon' ? `${name}${locked ? ' (Khóa)' : ''}` : name;
    if (gameState.claimedGifts.includes(fullName)) {
        alert(`Bạn đã nhận ${fullName}!`);
        return;
    }
    if (!canEquipTier(tier)) {
        alert(`Cần đạt ${tier === "Tam Phẩm" || tier === "Thượng Phẩm" ? "Kim Đan" : tier === "Cực Phẩm" ? "Nguyên Anh" : tier === "Trung Phẩm" ? "Trúc Cơ" : "Luyện Khí"} trở lên để nhận ${fullName}!`);
        return;
    }
    if (!window.getRandomStatForType) {
        console.error('window.getRandomStatForType is not defined! Ensure game.js is loaded.');
        alert('Lỗi: Không tìm thấy hàm tạo chỉ số ngẫu nhiên!');
        return;
    }
    const stats = window.getRandomStatForType(type, quality);
    const newItem = { type, name: fullName, image, tier, quality, locked, stats, enhanceLevel: 0 };
    gameState.inventory.push(newItem);
    gameState.claimedGifts.push(fullName);
    saveGameState();
    if (window.updateInventoryDisplay) {
        window.updateInventoryDisplay();
    }
    updateGiftDisplay();
    console.log('Gift claimed:', JSON.stringify(newItem, null, 2));
    alert(`Đã nhận ${fullName} vào Túi Đồ!`);
}

function openGiftBox(currency, price, level) {
    const gameState = initializeGameState();
    if (!window.giftBoxItems) {
        console.error('window.giftBoxItems is not defined! Ensure items.js is loaded and giftBoxItems is initialized.');
        alert('Lỗi: Không tìm thấy danh sách vật phẩm!');
        return;
    }
    if (currency === 'gold' && gameState.gold < price) {
        alert(`Không đủ vàng để mua! Cần ${price} vàng.`);
        return;
    }
    if (currency === 'spiritStones' && gameState.spiritStones < price) {
        alert(`Không đủ linh thạch để mua! Cần ${price} linh thạch.`);
        return;
    }
    const giftBag = {
        type: 'giftBag',
        name: level === 1 ? 'Vàng' : level === 2 ? 'Linh Thạch' : 'Túi Quà Nhị Phẩm',
        image: level === 1 ? 'images/vang.png' : level === 2 ? 'images/linh_thach.png' : 'images/qua.png',
        tier: level === 1 ? 'Hạ Phẩm' : 'Trung Phẩm',
        quality: level === 1 ? 'Nhất Phẩm' : 'Nhị Phẩm',
        locked: false,
        stats: {},
        enhanceLevel: 0,
        level: level
    };
    if (currency === 'gold') {
        gameState.gold -= price;
    } else {
        gameState.spiritStones -= price;
    }
    gameState.inventory.push(giftBag);
    console.log('Gift bag added to inventory:', JSON.stringify(giftBag, null, 2));
    console.log('Current inventory:', JSON.stringify(gameState.inventory, null, 2));
    saveCaller('openGiftBox', giftBag);
    saveGameState();
    if (window.updateInventoryDisplay) {
        window.updateInventoryDisplay();
    }
    updateShopDisplay();
    alert(`Đã nhận ${giftBag.name} vào Túi Đồ!`);
}

function updateGiftDisplay() {
    const giftItems = [
        { id: 'gift-o', name: 'Đả Cẩu Bổng', buttonId: 'claim-o' },
        { id: 'gift-necklace', name: 'Dây Chuyền', buttonId: 'claim-necklace' }
    ];
    giftItems.forEach(item => {
        const giftItem = document.getElementById(item.id);
        const claimBtn = document.getElementById(item.buttonId);
        if (giftItem && claimBtn && window.gameState.claimedGifts.includes(item.name)) {
            claimBtn.textContent = 'Đã nhận';
            claimBtn.disabled = true;
        }
    });
}

function updateShopDisplay() {
    const gameState = initializeGameState();
    const goldAmount = document.getElementById('gold-amount');
    const spiritStonesAmount = document.getElementById('spirit-stones-amount');
    if (goldAmount) goldAmount.textContent = gameState.gold;
    if (spiritStonesAmount) spiritStonesAmount.textContent = gameState.spiritStones;
    console.log('Shop display updated. Gold:', gameState.gold, 'Spirit Stones:', gameState.spiritStones);
}

function showShopSection(sectionName) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    document.getElementById(sectionName + '-section').classList.add('active');
    document.querySelector(`button[onclick="showShopSection('${sectionName}')"]`).classList.add('active');
    updateShopDisplay();
}

function saveCaller(action, data) {
    console.log(`Action: ${action}, Data:`, JSON.stringify(data, null, 2));
}

document.addEventListener('DOMContentLoaded', () => {
    initializeGameState();
    updateGiftDisplay();
    updateShopDisplay();
});