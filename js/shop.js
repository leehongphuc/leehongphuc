// Rely on global functions provided by game.js to avoid duplication

function claimGift(type, name, image, tier, quality, locked) {
    const gameState = window.initializeGameState();
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
    if (!(window.canEquipTier ? window.canEquipTier(tier) : true)) {
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
    if (typeof window.saveGameState === 'function') window.saveGameState();
    if (window.updateInventoryDisplay) {
        window.updateInventoryDisplay();
    }
    updateGiftDisplay();
    console.log('Gift claimed:', JSON.stringify(newItem, null, 2));
    alert(`Đã nhận ${fullName} vào Túi Đồ!`);
}

function openGiftBox(currency, price, level) {
    const gameState = window.initializeGameState();
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
    if (typeof window.saveGameState === 'function') window.saveGameState();
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
    const gameState = window.initializeGameState();
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
    if (typeof window.initializeGameState === 'function') {
        window.initializeGameState();
    }
    updateGiftDisplay();
    updateShopDisplay();
});