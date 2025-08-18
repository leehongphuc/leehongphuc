console.log('game.js được tải');

let gameState;

function initializeGameState() {
    if (!gameState) {
        gameState = {
            level: 1,
            exp: 0,
            maxExp: 100,
            levelTier: "Luyện Khí",
            levelSubTier: "Tầng 1",
            potentialPoints: 0,
            stats: {
                physicalDamage: { base: 100, bonus: 0, potentialBonus: 0 },
                magicDamage: { base: 100, bonus: 0, potentialBonus: 0 },
                criticalChance: { base: 5, bonus: 0, potentialBonus: 0 },
                criticalDamage: { base: 200, bonus: 0, potentialBonus: 0 },
                hp: { base: 1000, bonus: 0, potentialBonus: 0 },
                physicalDefense: { base: 20, bonus: 0, potentialBonus: 0 },
                magicDefense: { base: 20, bonus: 0, potentialBonus: 0 },
                agility: { base: 1.0, bonus: 0, potentialBonus: 0 }
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
            gold: 1000,
            spiritStones: 3000,
            materials: { 'Huyền Thiết': 700 },
            claimedGifts: []
        };

        if (typeof localStorage !== 'undefined') {
            try {
                const saved = localStorage.getItem('gameState');
                if (saved) {
                    const loadedState = JSON.parse(saved);
                    gameState = { ...gameState, ...loadedState };
                    
                    Object.keys(gameState.stats).forEach(stat => {
                        if (!gameState.stats[stat].hasOwnProperty('potentialBonus')) {
                            gameState.stats[stat].potentialBonus = 0;
                        }
                    });
                    
                    if (!gameState.materials) {
                        gameState.materials = { 'Huyền Thiết': 50 };
                    }
                }
            } catch (e) {
                console.warn('Không thể tải từ localStorage:', e);
            }
        }

        window.gameState = gameState;
        console.log('game.js: Khởi tạo gameState:', gameState);
    }
    return gameState;
}

const typeNames = {
    weapon: 'Vũ khí',
    necklace: 'Dây',
    ring: 'Nhẫn',
    gloves: 'Găng tay',
    boots: 'Giày',
    armor: 'Giáp',
    helmet: 'Nón',
    belt: 'Thắt Lưng',
    jade: 'Ngọc Bội',
    artifact: 'Pháp bảo'
};
window.typeNames = typeNames;

const qualityColors = {
    'Nhất Phẩm': '#ffffff',
    'Nhị Phẩm': 'green',
    'Tam Phẩm': 'blue',
    'Tứ Phẩm': 'purple',
    'Ngũ Phẩm': 'orange',
    'Lục Phẩm': 'red',
    'Thất Phẩm': 'gold',
    'Bát Phẩm': 'cyan',
    'Cửu Phẩm': 'magenta',
    'Vương Cấp': 'darkviolet'
};

const qualityRanges = {
    'Nhất Phẩm': { physicalDamage: [80, 130], magicDamage: [80, 130], criticalChance: [1.2, 1.5], agility: [0.05, 0.19], hp: [700, 1300], physicalDefense: [3.6, 4.6], magicDefense: [3.6, 4.6] },
    'Nhị Phẩm': { physicalDamage: [280, 430], magicDamage: [280, 430], criticalChance: [4.7, 6.8], agility: [0.18, 0.34], hp: [2500, 4000], physicalDefense: [13, 16.6], magicDefense: [13, 16.6] },
    'Tam Phẩm': { physicalDamage: [480, 730], magicDamage: [480, 730], criticalChance: [8.2, 12.1], agility: [0.31, 0.49], hp: [4300, 6700], physicalDefense: [22.4, 28.6], magicDefense: [22.4, 28.6] },
    'Tứ Phẩm': { physicalDamage: [680, 1030], magicDamage: [680, 1030], criticalChance: [11.7, 17.4], agility: [0.44, 0.64], hp: [6100, 9400], physicalDefense: [31.8, 40.6], magicDefense: [31.8, 40.6] },
    'Ngũ Phẩm': { physicalDamage: [880, 1330], magicDamage: [880, 1330], criticalChance: [15.2, 22.7], agility: [0.57, 0.79], hp: [7900, 12100], physicalDefense: [41.2, 52.6], magicDefense: [41.2, 52.6] },
    'Lục Phẩm': { physicalDamage: [1080, 1630], magicDamage: [1080, 1630], criticalChance: [18.7, 28], agility: [0.7, 0.94], hp: [9700, 14800], physicalDefense: [50.6, 64.6], magicDefense: [50.6, 64.6] },
    'Thất Phẩm': { physicalDamage: [1280, 1930], magicDamage: [1280, 1930], criticalChance: [22.2, 33.3], agility: [0.83, 1.09], hp: [11500, 17500], physicalDefense: [60, 76.6], magicDefense: [60, 76.6] },
    'Bát Phẩm': { physicalDamage: [1480, 2230], magicDamage: [1480, 2230], criticalChance: [25.7, 38.6], agility: [0.96, 1.24], hp: [13300, 20200], physicalDefense: [69.4, 88.6], magicDefense: [69.4, 88.6] },
    'Cửu Phẩm': { physicalDamage: [1500, 2000], magicDamage: [1500, 2000], criticalChance: [29.2, 43.9], agility: [1.09, 1.39], hp: [15100, 22900], physicalDefense: [78.8, 100.6], magicDefense: [78.8, 100.6] },
    'Vương Cấp': { physicalDamage: [2000, 3000], magicDamage: [2000, 3000], criticalChance: [18, 36], agility: [1.2, 2.5], hp: [20000, 30000], physicalDefense: [23, 42], magicDefense: [23, 42] }
};

// Expose quality ranges for other modules (e.g., forge) to render display ranges
if (typeof window !== 'undefined') {
    window.qualityRanges = qualityRanges;
}

function getRandomStatForType(type, quality) {
    const ranges = qualityRanges[quality] || qualityRanges['Nhất Phẩm'];
    const weaponStats = [
        { stat: 'physicalDamage', value: Math.floor(Math.random() * (ranges.physicalDamage[1] - ranges.physicalDamage[0] + 1)) + ranges.physicalDamage[0] },
        { stat: 'magicDamage', value: Math.floor(Math.random() * (ranges.magicDamage[1] - ranges.magicDamage[0] + 1)) + ranges.magicDamage[0] },
        { stat: 'criticalChance', value: (Math.random() * (ranges.criticalChance[1] - ranges.criticalChance[0]) + ranges.criticalChance[0]).toFixed(2) },
        { stat: 'agility', value: (Math.random() * (ranges.agility[1] - ranges.agility[0]) + ranges.agility[0]).toFixed(2) }
    ];
    const armorStats = [
        { stat: 'hp', value: Math.floor(Math.random() * (ranges.hp[1] - ranges.hp[0] + 1)) + ranges.hp[0] },
        { stat: 'physicalDefense', value: (Math.random() * (ranges.physicalDefense[1] - ranges.physicalDefense[0]) + ranges.physicalDefense[0]).toFixed(2) },
        { stat: 'magicDefense', value: (Math.random() * (ranges.magicDefense[1] - ranges.magicDefense[0]) + ranges.magicDefense[0]).toFixed(2) }
    ];

    let selectedStats = [];
    if (type === 'weapon') {
        const damageStat = Math.random() < 0.5 ? weaponStats.find(s => s.stat === 'physicalDamage') : weaponStats.find(s => s.stat === 'magicDamage');
        selectedStats = [damageStat, weaponStats.find(s => s.stat === 'criticalChance'), weaponStats.find(s => s.stat === 'agility')];
    } else if (type === 'artifact') {
        const weaponStat = weaponStats[Math.floor(Math.random() * weaponStats.length)];
        const armorStat = armorStats[Math.floor(Math.random() * armorStats.length)];
        selectedStats = [weaponStat, armorStat];
        if (Math.random() < 0.05) {
            const remainingStats = [...weaponStats, ...armorStats].filter(s => 
                s.stat !== weaponStat.stat && s.stat !== armorStat.stat && 
                !(s.stat === 'physicalDamage' && selectedStats.some(stat => stat.stat === 'magicDamage')) &&
                !(s.stat === 'magicDamage' && selectedStats.some(stat => stat.stat === 'physicalDamage'))
            );
            if (remainingStats.length > 0) {
                selectedStats.push(remainingStats[Math.floor(Math.random() * remainingStats.length)]);
            }
        }
    } else if (type === 'armor') {
        selectedStats = [
            armorStats.find(s => s.stat === 'hp'),
            armorStats.find(s => s.stat === 'physicalDefense'),
            armorStats.find(s => s.stat === 'magicDefense')
        ];
    } else if (['necklace', 'ring', 'gloves', 'boots'].includes(type)) {
        selectedStats = [weaponStats[Math.floor(Math.random() * weaponStats.length)]];
        if (Math.random() < 0.05) {
            const remainingStats = weaponStats.filter(s => s.stat !== selectedStats[0].stat);
            if (remainingStats.length > 0) {
                selectedStats.push(remainingStats[Math.floor(Math.random() * remainingStats.length)]);
            }
        }
    } else {
        selectedStats = [armorStats[Math.floor(Math.random() * armorStats.length)]];
        if (Math.random() < 0.05) {
            const remainingStats = armorStats.filter(s => s.stat !== selectedStats[0].stat);
            if (remainingStats.length > 0) {
                selectedStats.push(remainingStats[Math.floor(Math.random() * remainingStats.length)]);
            }
        }
    }

    const stats = {};
    selectedStats.forEach(({ stat, value }) => {
        stats[stat] = stat === 'agility' || stat === 'criticalChance' || stat === 'physicalDefense' || stat === 'magicDefense' ? parseFloat(value) : Math.floor(value);
    });
    return stats;
}
window.getRandomStatForType = getRandomStatForType;

function saveGameState() {
    if (typeof localStorage !== 'undefined') {
        try {
            localStorage.setItem('gameState', JSON.stringify(gameState));
            console.log('Lưu trạng thái game:', JSON.stringify(gameState, null, 2));
        } catch (e) {
            console.error('Lỗi khi lưu trạng thái game:', e);
        }
    } else {
        console.log('localStorage không khả dụng, trạng thái game không được lưu');
    }
}

function openGiftBag(index) {
    const gameState = initializeGameState();

    console.log('Mở rương tại chỉ số:', index);
    console.log('Túi đồ hiện tại:', JSON.stringify(gameState.inventory, null, 2));

    const item = gameState.inventory[index];
    if (!item || item.type !== 'giftBag') {
        console.error('Rương không hợp lệ tại chỉ số:', index, 'Vật phẩm:', JSON.stringify(item, null, 2));
        alert('Lỗi: Rương không hợp lệ!');
        return;
    }

    if (item.locked) {
        console.error('Rương bị khóa:', JSON.stringify(item, null, 2));
        alert('Lỗi: Rương đã bị khóa!');
        return;
    }

    if (!window.giftBoxItems) {
        console.error('window.giftBoxItems không được định nghĩa! Đang cố khởi tạo...');
        
        if (typeof giftBoxItems !== 'undefined') {
            window.giftBoxItems = giftBoxItems;
        } else {
            console.error('giftBoxItems không khả dụng. Đảm bảo items.js được tải trước game.js');
            alert('Lỗi: Không tìm thấy danh sách vật phẩm! Vui lòng tải lại trang và đảm bảo items.js được tải trước game.js.');
            return;
        }
    }

    console.log('Danh sách giftBoxItems khả dụng:', JSON.stringify(window.giftBoxItems, null, 2));

    const filteredItems = item.level === 1
        ? window.giftBoxItems.filter(g => g.quality === 'Nhất Phẩm' && ['weapon', 'boots', 'armor', 'helmet', 'belt', 'jade'].includes(g.type))
        : window.giftBoxItems.filter(g => g.quality === 'Nhị Phẩm');

    console.log('Vật phẩm lọc cho cấp', item.level, ':', JSON.stringify(filteredItems, null, 2));

    if (filteredItems.length === 0) {
        console.error('Không có vật phẩm hợp lệ cho cấp rương:', item.level);
        alert('Lỗi: Không có vật phẩm nào trong rương!');
        return;
    }

    const randomIndex = Math.floor(Math.random() * filteredItems.length);
    const randomItem = filteredItems[randomIndex];
    const fullName = randomItem.type === 'necklace' ? 'Dây Chuyền' : randomItem.name;

    const newItem = {
        type: randomItem.type,
        name: fullName,
        image: randomItem.image,
        tier: randomItem.tier,
        quality: randomItem.quality,
        locked: false,
        stats: getRandomStatForType(randomItem.type, randomItem.quality),
        enhanceLevel: 0
    };

    gameState.inventory.splice(index, 1);
    gameState.inventory.push(newItem);

    console.log('Mở rương thành công, vật phẩm thêm:', JSON.stringify(newItem, null, 2));
    console.log('Túi đồ sau khi cập nhật:', JSON.stringify(gameState.inventory, null, 2));

    saveGameState();
    if (typeof window.updateInventoryDisplay === 'function') {
        window.updateInventoryDisplay();
    } else {
        updateInventoryDisplay();
    }

    alert(`Đã mở ${item.name} và nhận ${fullName} vào Túi Đồ!`);
}

function showItemDetails(index, isEquipped = false, fromCharacterEquip = false) {
    const gameState = initializeGameState();
    const item = isEquipped ? gameState.equipment[Object.keys(gameState.equipment)[index]] : gameState.inventory[index];
    if (!item) {
        console.error('Không tìm thấy vật phẩm tại chỉ số:', index, 'isEquipped:', isEquipped);
        alert('Lỗi: Không tìm thấy vật phẩm!');
        return;
    }
    const modal = document.getElementById('item-details-modal');
    const modalContent = document.getElementById('item-details-content');
    if (!modal || !modalContent) {
        console.error('Không tìm thấy modal hoặc nội dung modal');
        alert('Lỗi: Không tìm thấy modal!');
        return;
    }
    let statsText = item.type === 'giftBag' ? 'Rương: Chứa trang bị ngẫu nhiên' : item.type === 'material' ? 'Nguyên liệu: Dùng để chế tạo trang bị' : '';
    if (item.type !== 'giftBag' && item.type !== 'material') {
        statsText = Object.entries(item.stats).map(([stat, value]) => {
            const statName = stat === 'physicalDamage' ? 'Tấn công Vật Lý' :
                stat === 'magicDamage' ? 'Tấn công Phép Thuật' :
                stat === 'criticalChance' ? 'Chí mạng' :
                stat === 'criticalDamage' ? 'Sát thương chí mạng' :
                stat === 'hp' ? 'Sinh lực' :
                stat === 'physicalDefense' ? 'Phòng thủ vật lý' :
                stat === 'magicDefense' ? 'Phòng thủ phép thuật' : 'Nhanh nhẹn';
            return `${statName}: ${value}${['criticalChance', 'physicalDefense', 'magicDefense'].includes(stat) ? '%' : stat === 'agility' ? '' : ''}`;
        }).join('<br>');
    }
    let actionButton = '';
    if (item.type === 'giftBag' && !isEquipped) {
        actionButton = `<button class="open-gift-btn" onclick="openGiftBag(${index})">Mở Rương</button>`;
    } else if (!isEquipped && item.type !== 'giftBag' && item.type !== 'material') {
        if (item.equipped) {
            actionButton = `<button class="unequip-btn" onclick="unequip('${item.type}')">Tháo</button>`;
        } else if (fromCharacterEquip) {
            actionButton = `<button class="equip-btn" onclick="equipItem(${index})">Mang</button>`;
        } else {
            actionButton = `<button class="equip-btn" onclick="equipItem(${index})">Mang</button>`;
        }
    }
    const qualityStyle = qualityColors[item.quality] ? `style="color: ${qualityColors[item.quality]}"` : '';
    modalContent.innerHTML = `
        <span class="close-modal" onclick="closeItemDetails()">&times;</span>
        <div class="item-detail">
            <img src="${item.image}" alt="${item.name}" style="width:85px;height:85px;" onerror="this.src='images/placeholder.png'">
            <div class="item-info">
                <div class="item-name" ${qualityStyle}>${item.name}</div>
                <div class="item-tier">Cấp bậc: ${item.tier}</div>
                <div class="item-quality">Phẩm chất: <span ${qualityStyle}>${item.quality}</span></div>
                ${item.type !== 'giftBag' && item.type !== 'material' ? `<div class="item-enhance">Tinh Luyện: ${item.enhanceLevel} lần</div>` : ''}
                <div class="item-stats">${statsText}</div>
                ${actionButton}
            </div>
        </div>
    `;
    modal.style.display = 'block';
    console.log('Hiển thị chi tiết vật phẩm:', JSON.stringify(item, null, 2), 'fromCharacterEquip:', fromCharacterEquip);
}

function closeItemDetails() {
    const modal = document.getElementById('item-details-modal');
    if (modal) modal.style.display = 'none';
    console.log('Đóng modal');
}

function equipItem(index) {
    const gameState = initializeGameState();
    const item = gameState.inventory[index];
    if (!item) {
        console.error('Không tìm thấy vật phẩm tại chỉ số:', index, 'Túi đồ:', JSON.stringify(gameState.inventory, null, 2));
        alert('Lỗi: Không tìm thấy vật phẩm!');
        return false;
    }
    if (item.type === 'giftBag' || item.type === 'material') {
        alert(`${item.type === 'giftBag' ? 'Rương' : 'Nguyên liệu'} không thể trang bị! ${item.type === 'giftBag' ? 'Hãy mở rương trước.' : 'Hãy sử dụng trong Lò Rèn.'}`);
        return false;
    }
    if (!canEquipTier(item.tier)) {
        alert(`Cần đạt ${item.tier === "Tam Phẩm" || item.tier === "Thượng Phẩm" ? "Kim Đan" : item.tier === "Cực Phẩm" ? "Nguyên Anh" : item.tier === "Trung Phẩm" ? "Trúc Cơ" : "Luyện Khí"} trở lên để trang bị ${item.name}!`);
        return false;
    }

    console.log('Trang bị vật phẩm:', JSON.stringify(item, null, 2));
    console.log('Trang bị hiện tại trước:', JSON.stringify(gameState.equipment[item.type], null, 2));

    if (gameState.equipment[item.type].equipped) {
        const currentItem = { ...gameState.equipment[item.type] };
        delete currentItem.equipped;
        // Tìm vật phẩm cũ trong túi đồ và đánh dấu không trang bị
        for (let i = 0; i < gameState.inventory.length; i++) {
            if (gameState.inventory[i].type === item.type && gameState.inventory[i].equipped) {
                gameState.inventory[i].equipped = false;
                break;
            }
        }
        gameState.inventory.push(currentItem);
    }

    gameState.equipment[item.type] = {
        name: item.name,
        equipped: true,
        image: item.image,
        tier: item.tier,
        quality: item.quality,
        locked: item.locked,
        stats: { ...item.stats },
        enhanceLevel: item.enhanceLevel || 0
    };
    // Đánh dấu vật phẩm là đã trang bị thay vì xóa khỏi túi đồ
    gameState.inventory[index].equipped = true;

    saveGameState();
    updateEquipmentBonuses();

    const equipmentGrid = document.getElementById('equipment-grid');
    if (equipmentGrid) updateEquipmentDisplay();
    else window.location.href = 'nhanvat.html';

    if (!equipmentGrid) updateInventoryDisplay();
    closeItemDetails();
    closeModal();

    console.log('Trang bị vật phẩm thành công:', JSON.stringify(gameState.equipment[item.type], null, 2));
    console.log('Túi đồ sau khi trang bị:', JSON.stringify(gameState.inventory, null, 2));
    return true;
}

function unequip(type) {
    const gameState = initializeGameState();
    if (!gameState.equipment[type] || !gameState.equipment[type].equipped) {
        alert(`Chưa có vật phẩm nào được trang bị ở vị trí ${type}!`);
        return false;
    }

    const item = gameState.equipment[type];
    console.log(`Tháo vật phẩm: ${type}`, JSON.stringify(item, null, 2));

    // Tìm vật phẩm trong túi đồ và đánh dấu không trang bị
    for (let i = 0; i < gameState.inventory.length; i++) {
        if (gameState.inventory[i].type === type && gameState.inventory[i].equipped) {
            gameState.inventory[i].equipped = false;
            break;
        }
    }

    gameState.equipment[type] = {
        name: "",
        equipped: false,
        image: "",
        tier: "",
        quality: "",
        locked: false,
        stats: {},
        enhanceLevel: 0
    };

    saveGameState();
    updateEquipmentBonuses();

    const equipmentGrid = document.getElementById('equipment-grid');
    if (equipmentGrid) updateEquipmentDisplay();
    else updateInventoryDisplay();

    closeItemDetails();

    console.log('Tháo vật phẩm:', type, 'Túi đồ sau:', JSON.stringify(gameState.inventory, null, 2));
    return true;
}

function showInventoryForType(type) {
    const gameState = initializeGameState();
    const modal = document.getElementById('inventory-modal');
    const modalGrid = document.getElementById('modal-grid');
    if (!modal || !modalGrid) {
        console.error('Không tìm thấy modal hoặc lưới modal');
        alert('Lỗi: Không tìm thấy modal!');
        return;
    }
    modalGrid.innerHTML = '';
    const items = gameState.inventory.filter(item => item.type === type && item.type !== 'giftBag' && item.type !== 'material');
    if (items.length === 0) {
        modalGrid.innerHTML = '<p>Không có vật phẩm phù hợp trong Túi Đồ.</p>';
    } else {
        items.forEach((item, index) => {
            const globalIndex = gameState.inventory.indexOf(item);
            const qualityNum = Object.keys(qualityColors).indexOf(item.quality) + 1;
            const modalItem = document.createElement('div');
            modalItem.className = `modal-item quality-${qualityNum}`;
            modalItem.innerHTML = `
                <div class="modal-image">
                    <img src="${item.image}" alt="${item.name}" onerror="this.src='images/placeholder.png'" onclick="showItemDetails(${globalIndex}, false, true)">
                </div>
            `;
            modalGrid.appendChild(modalItem);
        });
    }
    modal.style.display = 'block';
    console.log('Hiển thị túi đồ cho loại:', type, 'Vật phẩm:', JSON.stringify(items, null, 2));
}

function closeModal() {
    const modal = document.getElementById('inventory-modal');
    if (modal) modal.style.display = 'none';
}

function addPotential(statType) {
    const gameState = initializeGameState();
    if (gameState.potentialPoints <= 0) {
        alert('Không đủ Điểm Tiềm Năng!');
        return;
    }

    if (!gameState.stats[statType].hasOwnProperty('potentialBonus')) {
        gameState.stats[statType].potentialBonus = 0;
    }

    let increment = 0;
    switch (statType) {
        case 'physicalDamage':
        case 'magicDamage':
            increment = 50;
            break;
        case 'hp':
            increment = 200;
            break;
        case 'physicalDefense':
        case 'magicDefense':
            increment = 2;
            break;
        case 'agility':
            increment = 0.02;
            break;
        default:
            console.error('Loại chỉ số không hợp lệ hoặc không thể sửa đổi:', statType);
            return;
    }

    gameState.stats[statType].potentialBonus += increment;
    gameState.potentialPoints--;

    saveGameState();
    updateStatsDisplay();
    console.log('Thêm tiềm năng:', statType, 'Tiềm năng mới:', gameState.stats[statType].potentialBonus);
}

function getTurnOrder(playerAgility, enemyAgility) {
    return playerAgility >= enemyAgility ? 'player' : 'enemy';
}

function gainExp(amount) {
    const gameState = initializeGameState();
    gameState.exp += amount;
    while (gameState.exp >= gameState.maxExp) {
        gameState.exp -= gameState.maxExp;
        gameState.level++;
        gameState.potentialPoints += 5;
        if (gameState.level <= 13) {
            gameState.levelTier = "Luyện Khí";
            gameState.levelSubTier = `Tầng ${gameState.level}`;
            gameState.maxExp = gameState.level * 100;
        } else if (gameState.level <= 16) {
            gameState.levelTier = "Trúc Cơ";
            gameState.levelSubTier = gameState.level === 14 ? "Sơ Kỳ" : gameState.level === 15 ? "Trung Kỳ" : "Hậu Kỳ";
            gameState.maxExp = 1000;
        } else if (gameState.level <= 19) {
            gameState.levelTier = "Kim Đan";
            gameState.levelSubTier = gameState.level === 17 ? "Sơ Kỳ" : gameState.level === 18 ? "Trung Kỳ" : "Hậu Kỳ";
            gameState.maxExp = 5000;
        } else if (gameState.level <= 22) {
            gameState.levelTier = "Nguyên Anh";
            gameState.levelSubTier = gameState.level === 20 ? "Sơ Kỳ" : gameState.level === 21 ? "Trung Kỳ" : "Hậu Kỳ";
            gameState.maxExp = 10000;
        } else {
            gameState.levelTier = "Hóa Thần";
            gameState.levelSubTier = gameState.level === 23 ? "Sơ Kỳ" : gameState.level === 24 ? "Trung Kỳ" : "Hậu Kỳ";
            gameState.maxExp = 20000;
        }
    }
    saveGameState();
    updateDisplay();
    console.log('Nhận kinh nghiệm:', amount, 'Cấp mới:', gameState.level, 'Kinh nghiệm mới:', gameState.exp);
}

function resetGame() {
    if (confirm('Bạn có chắc muốn xóa toàn bộ dữ liệu game? Hành động này không thể hoàn tác!')) {
        if (typeof localStorage !== 'undefined') {
            localStorage.removeItem('gameState');
        }
        location.reload();
        console.log('Đặt lại trạng thái game');
    }
}

// Kiểm tra và xử lý level up
function checkAndProcessLevelUp(gameState) {
    while (gameState.exp >= gameState.maxExp) {
        // Level up
        gameState.level++;
        gameState.exp -= gameState.maxExp;
        gameState.potentialPoints += 5; // Tăng điểm tiềm năng
        
        // Tính maxExp mới
        gameState.maxExp = Math.floor(gameState.maxExp * 1.2);
        
        // Cập nhật level tier và sub tier
        if (gameState.level <= 10) {
            gameState.levelTier = "Luyện Khí";
            gameState.levelSubTier = `Tầng ${gameState.level}`;
        } else if (gameState.level <= 20) {
            gameState.levelTier = "Trúc Cơ";
            gameState.levelSubTier = `Tầng ${gameState.level - 10}`;
        } else if (gameState.level <= 30) {
            gameState.levelTier = "Kim Đan";
            gameState.levelSubTier = `Tầng ${gameState.level - 20}`;
        } else if (gameState.level <= 40) {
            gameState.levelTier = "Nguyên Anh";
            gameState.levelSubTier = `Tầng ${gameState.level - 30}`;
        } else {
            gameState.levelTier = "Hóa Thần";
            gameState.levelSubTier = `Tầng ${gameState.level - 40}`;
        }
        
        // Tăng stats cơ bản
        gameState.stats.hp.base += 100;
        gameState.stats.physicalDamage.base += 10;
        gameState.stats.magicDamage.base += 10;
        gameState.stats.physicalDefense.base += 2;
        gameState.stats.magicDefense.base += 2;
        
        console.log(`🎉 Level up! Đạt cấp ${gameState.level} - ${gameState.levelTier} ${gameState.levelSubTier}`);
        console.log(`⭐ Nhận được 5 điểm tiềm năng. Tổng: ${gameState.potentialPoints}`);
    }
}

function updateDisplay() {
    const gameState = initializeGameState();
    
    // Kiểm tra và xử lý level up
    checkAndProcessLevelUp(gameState);
    
    const levelDisplay = document.getElementById('level-display');
    if (levelDisplay) {
        levelDisplay.textContent = `${gameState.levelTier} ${gameState.levelSubTier} (Cấp ${gameState.level})`;
    }
    
    const expDisplay = document.getElementById('exp-display');
    if (expDisplay) {
        expDisplay.textContent = `${gameState.exp}/${gameState.maxExp}`;
    }
    
    const goldDisplay = document.getElementById('gold-display');
    if (goldDisplay) {
        goldDisplay.textContent = gameState.gold;
    }
    
    const spiritDisplay = document.getElementById('spirit-display');
    if (spiritDisplay) {
        spiritDisplay.textContent = gameState.spiritStones;
    }
    
    const potentialPointsDisplay = document.getElementById('potential-points');
    if (potentialPointsDisplay) {
        potentialPointsDisplay.textContent = `⭐ Điểm Tiềm Năng: ${gameState.potentialPoints}`;
    }
    
    const expProgress = document.getElementById('exp-progress');
    if (expProgress) {
        const percentage = Math.min((gameState.exp / gameState.maxExp) * 100, 100);
        expProgress.style.width = `${percentage}%`;
    }
    
    updateStatsDisplay();
}

function updateStatsDisplay() {
    const gameState = initializeGameState();

    Object.keys(gameState.stats).forEach(stat => {
        if (!gameState.stats[stat].hasOwnProperty('potentialBonus')) {
            gameState.stats[stat].potentialBonus = 0;
        }
    });

    const equipmentBonus = {
        physicalDamage: 0,
        magicDamage: 0,
        criticalChance: 0,
        criticalDamage: 0,
        hp: 0,
        physicalDefense: 0,
        magicDefense: 0,
        agility: 0
    };

    Object.values(gameState.equipment).forEach(item => {
        if (item && item.equipped && item.stats) {
            Object.entries(item.stats).forEach(([stat, value]) => {
                if (equipmentBonus.hasOwnProperty(stat)) {
                    equipmentBonus[stat] += value;
                }
            });
        }
    });

    const currentStats = {
        physicalDamage: gameState.stats.physicalDamage.base + equipmentBonus.physicalDamage + (gameState.stats.physicalDamage.potentialBonus || 0),
        magicDamage: gameState.stats.magicDamage.base + equipmentBonus.magicDamage + (gameState.stats.magicDamage.potentialBonus || 0),
        criticalChance: gameState.stats.criticalChance.base + equipmentBonus.criticalChance + (gameState.stats.criticalChance.potentialBonus || 0),
        criticalDamage: gameState.stats.criticalDamage.base + equipmentBonus.criticalDamage + (gameState.stats.criticalDamage.potentialBonus || 0),
        hp: gameState.stats.hp.base + equipmentBonus.hp + (gameState.stats.hp.potentialBonus || 0),
        physicalDefense: gameState.stats.physicalDefense.base + equipmentBonus.physicalDefense + (gameState.stats.physicalDefense.potentialBonus || 0),
        magicDefense: gameState.stats.magicDefense.base + equipmentBonus.magicDefense + (gameState.stats.magicDefense.potentialBonus || 0),
        agility: gameState.stats.agility.base + equipmentBonus.agility + (gameState.stats.agility.potentialBonus || 0)
    };

    const finalStats = {
        physicalDamage: currentStats.physicalDamage + 50,
        magicDamage: currentStats.magicDamage + 50,
        criticalChance: currentStats.criticalChance,
        criticalDamage: currentStats.criticalDamage,
        hp: currentStats.hp + 200,
        physicalDefense: currentStats.physicalDefense + 2,
        magicDefense: currentStats.magicDefense + 2,
        agility: currentStats.agility + 0.02
    };

    const statsDisplay = document.getElementById('stats-grid');
    if (statsDisplay) {
        statsDisplay.innerHTML = `
            <div class="stat-item">
                <span class="stat-name">Tấn công Vật Lý</span>
                <div class="stat-values">
                    <span class="stat-base">${Math.floor(currentStats.physicalDamage)}</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${Math.floor(finalStats.physicalDamage)}</span>
                    <button class="add-point-btn" onclick="addPotential('physicalDamage')">+</button>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Tấn công Phép Thuật</span>
                <div class="stat-values">
                    <span class="stat-base">${Math.floor(currentStats.magicDamage)}</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${Math.floor(finalStats.magicDamage)}</span>
                    <button class="add-point-btn" onclick="addPotential('magicDamage')">+</button>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Chí mạng</span>
                <div class="stat-values">
                    <span class="stat-base">${currentStats.criticalChance.toFixed(1)}%</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${finalStats.criticalChance.toFixed(1)}%</span>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Sát thương chí mạng</span>
                <div class="stat-values">
                    <span class="stat-base">${Math.floor(currentStats.criticalDamage)}%</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${Math.floor(finalStats.criticalDamage)}%</span>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Sinh lực</span>
                <div class="stat-values">
                    <span class="stat-base">${Math.floor(currentStats.hp)}</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${Math.floor(finalStats.hp)}</span>
                    <button class="add-point-btn" onclick="addPotential('hp')">+</button>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Phòng thủ vật lý</span>
                <div class="stat-values">
                    <span class="stat-base">${currentStats.physicalDefense.toFixed(1)}%</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${finalStats.physicalDefense.toFixed(1)}%</span>
                    <button class="add-point-btn" onclick="addPotential('physicalDefense')">+</button>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Phòng thủ phép thuật</span>
                <div class="stat-values">
                    <span class="stat-base">${currentStats.magicDefense.toFixed(1)}%</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${finalStats.magicDefense.toFixed(1)}%</span>
                    <button class="add-point-btn" onclick="addPotential('magicDefense')">+</button>
                </div>
            </div>
            <div class="stat-item">
                <span class="stat-name">Nhanh nhẹn</span>
                <div class="stat-values">
                    <span class="stat-base">${currentStats.agility.toFixed(2)}</span>
                    <span class="stat-arrow">→</span>
                    <span class="stat-enhanced">${finalStats.agility.toFixed(2)}</span>
                    <button class="add-point-btn" onclick="addPotential('agility')">+</button>
                </div>
            </div>
        `;
    }
}

function updateEquipmentDisplay() {
    const gameState = initializeGameState();
    console.log('=== Cập nhật hiển thị trang bị ===');
    console.log('Trang bị hiện tại:', JSON.stringify(gameState.equipment, null, 2));

    const equipmentGrid = document.getElementById('equipment-grid');
    if (!equipmentGrid) {
        console.warn('Không tìm thấy phần tử equipment-grid! Bỏ qua cập nhật.');
        return;
    }

    equipmentGrid.innerHTML = '';

    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    equipmentTypes.forEach((type, index) => {
        const item = gameState.equipment[type];
        console.log(`Xử lý ${type}:`, JSON.stringify(item, null, 2));

        const equipmentItem = document.createElement('div');
        const qualityNum = item.quality ? Object.keys(qualityColors).indexOf(item.quality) + 1 : 1;
        equipmentItem.className = `equipment-item ${item.equipped && item.name ? `quality-${qualityNum}` : ''}`;
        equipmentItem.setAttribute('data-type', type);

        const qualityStyle = item.quality && qualityColors[item.quality] ? `style="color: ${qualityColors[item.quality]}"` : '';

        if (item && item.equipped && item.name) {
            console.log(`${type} đã được trang bị, cập nhật hiển thị...`);
            equipmentItem.innerHTML = `
                <div class="equipment-image">
                    <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" 
                         onerror="this.src='images/placeholder.png'" onclick="showItemDetails(${index}, true)">
                </div>
                <div class="equipment-info">
                    <div class="equipment-type" ${qualityStyle}>${item.name}</div>
                    <div class="equipment-quality">${item.quality}</div>
                </div>
                <div class="equipment-action">
                    <button class="unequip-btn" onclick="unequip('${type}')">Tháo</button>
                </div>
            `;
        } else {
            console.log(`${type} chưa được trang bị, hiển thị ô trống...`);
            equipmentItem.innerHTML = `
                <div class="equipment-image">
                    <div class="placeholder-image"></div>
                </div>
                <div class="equipment-info">
                    <div class="equipment-type">${typeNames[type]}</div>
                    <div class="equipment-status">Chưa trang bị</div>
                </div>
                <div class="equipment-action">
                    <button class="equip-link" onclick="showInventoryForType('${type}')">Mang</button>
                </div>
            `;
        }

        equipmentGrid.appendChild(equipmentItem);
    });

    console.log('equipment-grid cập nhật, HTML:', equipmentGrid.innerHTML);
    updateEquipmentBonuses();
}

function updateInventoryEquipmentGrid() {
    const gameState = initializeGameState();
    const equipmentGrid = document.getElementById('equipment-grid');
    if (equipmentGrid) {
        console.log('Cập nhật lưới trang bị trong túi đồ...');
        equipmentGrid.innerHTML = '';

        gameState.inventory
            .filter(item => ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'].includes(item.type))
            .forEach((item, index) => {
                const globalIndex = gameState.inventory.indexOf(item);
                const qualityClass = item.quality ? `quality-${item.quality.toLowerCase().replace(/\s+/g, '-')}` : '';
                const inventoryItem = document.createElement('div');
                const qualityStyle = qualityColors[item.quality] ? `style="color: ${qualityColors[item.quality]}"` : '';
                inventoryItem.className = `inventory-item ${qualityClass}`;

                let actionsHtml = '';
                if (item.locked) {
                    actionsHtml = `<button class="lock-btn" onclick="toggleLock(${globalIndex})">🔒</button>`;
                } else {
                    actionsHtml = `
                        <button class="lock-btn" onclick="toggleLock(${globalIndex})">🔓</button>
                        <button class="delete-btn" onclick="deleteItem(${globalIndex})">🗑️</button>
                    `;
                }

                inventoryItem.innerHTML = `
                    <div class="inventory-image">
                        <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" 
                             onerror="this.src='images/placeholder.png'" onclick="showItemDetails(${globalIndex})">
                    </div>
                    <div class="inventory-info">
                        <div class="inventory-name" ${qualityStyle}>${item.name}</div>
                        <div class="inventory-status">${item.equipped ? 'Đã Trang Bị' : 'Trong Túi Đồ'}</div>
                    </div>
                    <div class="inventory-actions">
                        ${actionsHtml}
                    </div>
                `;
                equipmentGrid.appendChild(inventoryItem);
            });
    }
}

function updateInventoryDisplay() {
    const gameState = initializeGameState();
    console.log('Cập nhật hiển thị túi đồ...');
    console.log('Túi đồ hiện tại:', JSON.stringify(gameState.inventory, null, 2));

    updateInventoryEquipmentGrid();

    const itemsGrid = document.getElementById('items-grid');
    if (itemsGrid) {
        itemsGrid.innerHTML = '';
        gameState.inventory
            .filter(item => item.type === 'giftBag')
            .forEach((item, index) => {
                const globalIndex = gameState.inventory.indexOf(item);
                const qualityClass = item.quality ? `quality-${item.quality.toLowerCase().replace(/\s+/g, '-')}` : '';
                const inventoryItem = document.createElement('div');
                const qualityStyle = qualityColors[item.quality] ? `style="color: ${qualityColors[item.quality]}"` : '';
                inventoryItem.className = `inventory-item ${qualityClass}`;
                inventoryItem.innerHTML = `
                    <div class="inventory-image">
                        <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" 
                             onerror="this.src='images/placeholder.png'" onclick="showItemDetails(${globalIndex})">
                    </div>
                                    <div class="inventory-info">
                    <div class="inventory-name" ${qualityStyle}>${item.name}</div>
                    <div class="inventory-type">Rương</div>
                </div>
                    <div class="inventory-actions">
                        ${item.locked ? `<button class="lock-btn" onclick="toggleLock(${globalIndex})">🔒</button>` : `
                            <button class="lock-btn" onclick="toggleLock(${globalIndex})">🔓</button>
                            <button class="open-gift-btn" onclick="openGiftBag(${globalIndex})">Mở Rương</button>
                        `}
                    </div>
                `;
                itemsGrid.appendChild(inventoryItem);
            });
    }

    const materialsGrid = document.getElementById('materials-grid');
    if (materialsGrid) {
        materialsGrid.innerHTML = '';
        Object.entries(gameState.materials).forEach(([name, count], index) => {
            // Loại bỏ "Kinh Nghiệm" khỏi nguyên liệu
            if (name === 'Kinh Nghiệm') return;
            
            const material = window.giftBoxItems.find(item => item.type === 'material' && item.name === name);
            const qualityClass = material && material.quality ? `quality-${material.quality.toLowerCase().replace(/\s+/g, '-')}` : '';
            const qualityStyle = material && qualityColors[material.quality] ? `style="color: ${qualityColors[material.quality]}"` : '';
            const materialItem = document.createElement('div');
            materialItem.className = `inventory-item ${qualityClass}`;
            materialItem.innerHTML = `
                <div class="inventory-image">
                    <img src="${material ? material.image : 'images/placeholder.png'}" alt="${name}" 
                         onerror="this.src='images/placeholder.png'" onclick="showItemDetails(${gameState.inventory.findIndex(i => i.name === name && i.type === 'material')})">
                </div>
                <div class="inventory-info">
                    <div class="inventory-name" ${qualityStyle}>${name}</div>
                    <div class="inventory-amount">Số lượng: ${count}</div>
                </div>
            `;
            materialsGrid.appendChild(materialItem);
        });
    }

    const resourcesGrid = document.getElementById('resources-grid');
    if (resourcesGrid) {
        resourcesGrid.innerHTML = `
            <div class="resource-item">
                <span>Kim tệ: ${gameState.gold}</span>
            </div>
            <div class="resource-item">
                <span>Linh thạch: ${gameState.spiritStones}</span>
            </div>
            <div class="resource-item">
                <span>Huyền Thiết: ${gameState.materials['Huyền Thiết']}</span>
            </div>
        `;
    }
}
window.updateInventoryDisplay = updateInventoryDisplay;

function toggleLock(index) {
    const gameState = initializeGameState();
    const item = gameState.inventory[index];
    if (item) {
        item.locked = !item.locked;
        saveGameState();
        updateInventoryDisplay();
    }
}

function deleteItem(index) {
    const gameState = initializeGameState();
    if (confirm('Bạn có chắc muốn xóa vật phẩm này?')) {
        const item = gameState.inventory[index];
        if (item && !item.locked) {
            if (gameState.equipment[item.type] && gameState.equipment[item.type].equipped && gameState.equipment[item.type].name === item.name) {
                alert('Lỗi: Vật phẩm đang được trang bị, vui lòng tháo trước khi xóa!');
                return;
            }
            gameState.inventory.splice(index, 1);
            saveGameState();
            updateInventoryDisplay();
        } else if (item && item.locked) {
            alert('Lỗi: Vật phẩm đã bị khóa, không thể xóa!');
        }
    }
}

function updateEquipmentBonuses() {
    const gameState = initializeGameState();

    Object.keys(gameState.stats).forEach(stat => {
        if (!gameState.stats[stat].hasOwnProperty('potentialBonus')) {
            gameState.stats[stat].potentialBonus = 0;
        }
        gameState.stats[stat].bonus = 0;
    });

    Object.values(gameState.equipment).forEach(item => {
        if (item && item.equipped && item.stats) {
            Object.entries(item.stats).forEach(([stat, value]) => {
                if (gameState.stats[stat]) {
                    gameState.stats[stat].bonus += value;
                }
            });
        }
    });

    Object.keys(gameState.stats).forEach(stat => {
        gameState.stats[stat].bonus += gameState.stats[stat].potentialBonus || 0;
    });

    updateStatsDisplay();
    console.log('Cập nhật bonus trang bị:', JSON.stringify(gameState.stats, null, 2));
}

function saveCaller(action, data) {
    console.log(`Hành động: ${action}, Dữ liệu:`, JSON.stringify(data, null, 2));
}

function canEquipTier(tier) {
    const gameState = initializeGameState();
    const tiers = ["Luyện Khí", "Trúc Cơ", "Kim Đan", "Nguyên Anh", "Hóa Thần"];
    const requiredTier = {
        "Hạ Phẩm": 0,
        "Trung Phẩm": 1,
        "Tam Phẩm": 2,
        "Thượng Phẩm": 2,
        "Cực Phẩm": 3
    };
    return tiers.indexOf(gameState.levelTier) >= requiredTier[tier] || requiredTier[tier] === undefined;
}
window.canEquipTier = canEquipTier;

function showSection(sectionId) {
    console.log('Chuyển sang section:', sectionId);
    
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => button.classList.remove('active'));
    
    // Show selected section
    const selectedSection = document.getElementById(sectionId + '-section');
    if (selectedSection) {
        selectedSection.classList.add('active');
        console.log(`Đã kích hoạt section: ${sectionId}`);
    } else {
        console.error(`Không tìm thấy section: ${sectionId}-section`);
    }
    
    // Add active class to selected tab button
    const selectedButton = document.querySelector(`.tab-button[data-section="${sectionId}"]`);
    if (selectedButton) {
        selectedButton.classList.add('active');
        console.log(`Đã kích hoạt tab button: ${sectionId}`);
    } else {
        console.error(`Không tìm thấy tab button với data-section: ${sectionId}`);
    }
    
    // Update displays based on section
    if (sectionId === 'stats') {
        updateStatsDisplay();
    } else if (sectionId === 'equipment') {
        updateEquipmentDisplay();
    }
}
// Make functions globally available
window.showSection = showSection;
window.addPotential = addPotential;
window.updateDisplay = updateDisplay;
window.updateStatsDisplay = updateStatsDisplay;
window.updateEquipmentDisplay = updateEquipmentDisplay;
window.initializeGameState = initializeGameState;

window.showSection = showSection;
console.log('showSection được định nghĩa:', typeof window.showSection);

document.addEventListener('DOMContentLoaded', () => {
    initializeGameState();
    const equipmentGrid = document.getElementById('equipment-grid');
    const isShopPage = document.getElementById('gold-section') || document.getElementById('spiritStones-section');
    const isForgePage = document.querySelector('.forge-section');
    if (equipmentGrid && !isShopPage && !isForgePage) {
        console.log('Khởi tạo trang nhân vật...');
        updateDisplay();
        updateEquipmentDisplay();
    } else if (!isShopPage && !isForgePage) {
        console.log('Khởi tạo trang túi đồ...');
        updateInventoryDisplay();
    }

    // Thêm sự kiện cho các nút tab
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const sectionId = button.getAttribute('data-section');
            showSection(sectionId);
        });
    });
});