console.log('forge.js được tải');

function updateForgeDisplay() {
    console.log('Bắt đầu cập nhật hiển thị Lò Rèn...');
    
    if (typeof initializeGameState !== 'function') {
        console.error('Lỗi: initializeGameState không được định nghĩa! Đảm bảo game.js được tải trước forge.js');
        alert('Lỗi: Không thể khởi tạo trạng thái game!');
        return;
    }
    
    const gameState = initializeGameState();

    const equipmentGrid = document.getElementById('equipment-grid');
    if (!equipmentGrid) {
        console.error('Lỗi: Không tìm thấy phần tử equipment-grid trong trang Lò Rèn!');
        alert('Lỗi: Không tìm thấy lưới trang bị!');
        return;
    }

    if (!window.giftBoxItems) {
        console.error('Lỗi: window.giftBoxItems không được định nghĩa! Đảm bảo items.js được tải trước forge.js');
        if (typeof giftBoxItems !== 'undefined') {
            window.giftBoxItems = giftBoxItems;
            console.log('Đã khôi phục window.giftBoxItems từ global giftBoxItems');
        } else {
            alert('Lỗi: Không tìm thấy danh sách vật phẩm!');
            return;
        }
    }

    equipmentGrid.innerHTML = '';
    console.log('Danh sách vật phẩm trong giftBoxItems:', JSON.stringify(window.giftBoxItems, null, 2));

    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(item => equipmentTypes.includes(item.type));
    
    if (items.length === 0) {
        console.warn('Không có vật phẩm trang bị nào trong giftBoxItems!');
        equipmentGrid.innerHTML = '<p>Không có trang bị nào để chế tạo!</p>';
        return;
    }

    const uniqueItems = [];
    const seen = new Set();
    
    items.forEach(item => {
        const key = `${item.type}-${item.quality}`;
        if (!seen.has(key)) {
            seen.add(key);
            uniqueItems.push(item);
        }
    });

    console.log('Số lượng vật phẩm duy nhất để chế tạo:', uniqueItems.length);

    uniqueItems.forEach((item, index) => {
        const qualityClass = item.quality ? `quality-${item.quality.toLowerCase().replace(/\s+/g, '-')}` : '';
        const equipmentItem = document.createElement('div');
        equipmentItem.className = `equipment-item ${qualityClass}`;
        equipmentItem.setAttribute('data-index', index);

        const craftCost = calculateCraftCost(item.quality);
        const canAfford = gameState.gold >= craftCost.gold && 
                         gameState.spiritStones >= craftCost.spiritStones && 
                         (gameState.materials['Huyền Thiết'] || 0) >= craftCost.huyenThiet;

        equipmentItem.innerHTML = `
            <div class="equipment-image ${!canAfford ? 'disabled-item' : ''}">
                <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" 
                     class="item-image" onerror="this.src='images/placeholder.png'" onclick="showCraftItemDetails(${index})">
            </div>
            <div class="equipment-info">
                <div class="equipment-type" style="color: ${qualityColors[item.quality] || '#ffffff'}">${item.name}</div>
                <div class="equipment-action">
                    <button class="equip-btn ${!canAfford ? 'disabled disabled-item' : ''}" onclick="showCraftItemDetails(${index})">
                        Chế Tạo
                    </button>
                </div>
            </div>
        `;

        equipmentGrid.appendChild(equipmentItem);
    });

    updateResourcesDisplay(gameState);
    console.log('Hiển thị Lò Rèn được cập nhật với', uniqueItems.length, 'vật phẩm duy nhất');
}

function updateResourcesDisplay(gameState) {
    const resourcesGrid = document.getElementById('resources-grid');
    if (resourcesGrid) {
        resourcesGrid.innerHTML = `
            <div class="resource-item">
                <img src="images/vang.png" alt="Kim tệ" class="resource-image" 
                     onerror="this.src='images/placeholder.png'">
                <span>${gameState.gold.toLocaleString()}</span>
            </div>
            <div class="resource-item">
                <img src="images/linh_thach.png" alt="Linh thạch" class="resource-image" 
                     onerror="this.src='images/placeholder.png'">
                <span>${gameState.spiritStones.toLocaleString()}</span>
            </div>
            <div class="resource-item">
                <img src="images/huyen_thiet.png" alt="Huyền Thiết" class="resource-image" 
                     onerror="this.src='images/placeholder.png'">
                <span>${gameState.materials['Huyền Thiết'] || 0}</span>
            </div>
        `;
    }
}

function calculateCraftCost(quality) {
    const baseCost = {
        gold: 50,
        spiritStones: 50,
        huyenThiet: 50
    };
    
    const qualityMultipliers = {
        'Nhất Phẩm': 1,
        'Nhị Phẩm': 1.5,
        'Tam Phẩm': 2,
        'Tứ Phẩm': 2.5,
        'Ngũ Phẩm': 3,
        'Lục Phẩm': 3.5,
        'Thất Phẩm': 4,
        'Bát Phẩm': 4.5,
        'Cửu Phẩm': 5,
        'Vương Cấp': 6
    };
    
    const multiplier = qualityMultipliers[quality] || 1;
    
    return {
        gold: Math.floor(baseCost.gold * multiplier),
        spiritStones: Math.floor(baseCost.spiritStones * multiplier),
        huyenThiet: Math.floor(baseCost.huyenThiet * multiplier)
    };
}

function showCraftItemDetails(index) {
    console.log('Hiển thị chi tiết vật phẩm tại chỉ số:', index);
    
    if (!window.giftBoxItems) {
        console.error('Lỗi: window.giftBoxItems không được định nghĩa!');
        alert('Lỗi: Không tìm thấy danh sách vật phẩm!');
        return;
    }

    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(item => equipmentTypes.includes(item.type));
    
    const uniqueItems = [];
    const seen = new Set();
    items.forEach(item => {
        const key = `${item.type}-${item.quality}`;
        if (!seen.has(key)) {
            seen.add(key);
            uniqueItems.push(item);
        }
    });

    const item = uniqueItems[index];
    if (!item) {
        console.error('Không tìm thấy vật phẩm tại chỉ số:', index);
        alert('Lỗi: Không tìm thấy vật phẩm!');
        return;
    }

    const gameState = initializeGameState();
    const modal = document.getElementById('item-details-modal');
    const modalContent = document.getElementById('item-details-content');
    
    if (!modal || !modalContent) {
        console.error('Không tìm thấy modal hoặc nội dung modal');
        alert('Lỗi: Không tìm thấy modal!');
        return;
    }

    const craftCost = calculateCraftCost(item.quality);
    const canAfford = gameState.gold >= craftCost.gold && 
                     gameState.spiritStones >= craftCost.spiritStones && 
                     (gameState.materials['Huyền Thiết'] || 0) >= craftCost.huyenThiet;

    const qualityStyle = qualityColors[item.quality] ? `style="color: ${qualityColors[item.quality]}"` : '';
    
    // Tạo thông tin stats của vật phẩm với format mới
    let statsText = '';
    if (item.stats) {
        Object.entries(item.stats).forEach(([stat, value]) => {
            const statName = stat === 'physicalDamage' ? 'Tấn công Vật Lý' :
                stat === 'magicDamage' ? 'Tấn công Phép Thuật' :
                stat === 'criticalChance' ? 'Chí mạng' :
                stat === 'criticalDamage' ? 'Sát thương chí mạng' :
                stat === 'hp' ? 'Sinh lực' :
                stat === 'physicalDefense' ? 'Phòng thủ vật lý' :
                stat === 'magicDefense' ? 'Phòng thủ phép thuật' : 'Nhanh nhẹn';
            
            // Format giá trị theo yêu cầu (ví dụ: 0.88 ~ 1.2)
            let statValue;
            if (stat === 'agility') {
                // Nhanh nhẹn hiển thị dạng range
                const baseValue = parseFloat(value);
                const minValue = (baseValue * 0.8).toFixed(2);
                const maxValue = (baseValue * 1.2).toFixed(2);
                statValue = `${minValue} ~ ${maxValue}`;
            } else if (['criticalChance', 'physicalDefense', 'magicDefense'].includes(stat)) {
                statValue = `${value}%`;
            } else {
                statValue = value;
            }
            
            statsText += `<div class="stat-line">${statName}: ${statValue}</div>`;
        });
    }

    modalContent.innerHTML = `
        <span class="close-modal" onclick="closeItemDetails()">&times;</span>
        <div class="item-detail ${item.quality ? `quality-${item.quality.toLowerCase().replace(/\s+/g, '-')}` : ''}">
            <div class="item-header">
                <div class="item-basic-info">
                    <div class="item-name" ${qualityStyle}>${item.name}</div>
                    <div class="item-tier">Cấp bậc: ${item.tier}</div>
                    <div class="item-quality">Phẩm chất: ${item.quality}</div>
                    <div class="item-stats">${statsText}</div>
                </div>
            </div>
            
            <div class="craft-materials-section">
                <h4>Vật phẩm cần chế tạo:</h4>
                <div class="craft-materials">
                    <div class="material-item ${gameState.gold < craftCost.gold ? 'insufficient' : ''}">
                        <img src="images/vang.png" alt="Kim tệ" class="material-image" 
                             onerror="this.src='images/placeholder.png'">
                        <div class="material-name">Kim tệ</div>
                    </div>
                    <div class="material-item ${gameState.spiritStones < craftCost.spiritStones ? 'insufficient' : ''}">
                        <img src="images/linh_thach.png" alt="Linh thạch" class="material-image" 
                             onerror="this.src='images/placeholder.png'">
                        <div class="material-name">Linh thạch</div>
                    </div>
                    <div class="material-item ${(gameState.materials['Huyền Thiết'] || 0) < craftCost.huyenThiet ? 'insufficient' : ''}">
                        <img src="images/huyen_thiet.png" alt="Huyền Thiết" class="material-image" 
                             onerror="this.src='images/placeholder.png'">
                        <div class="material-name">Huyền Thiết</div>
                    </div>
                </div>
            </div>
            
            <button class="craft-btn ${!canAfford ? 'disabled' : ''}" onclick="craftItem(${index})" ${canAfford ? '' : 'disabled'}>
                ${canAfford ? 'Chế Tạo' : 'Thiếu nguyên liệu'}
            </button>
        </div>
    `;
    
    modal.style.display = 'block';
    console.log('Hiển thị chi tiết vật phẩm chế tạo:', JSON.stringify(item, null, 2));
}

function craftItem(index) {
    console.log('Bắt đầu chế tạo vật phẩm tại chỉ số:', index);
    
    if (!window.giftBoxItems) {
        console.error('Lỗi: window.giftBoxItems không được định nghĩa!');
        alert('Lỗi: Không tìm thấy danh sách vật phẩm!');
        return;
    }

    const gameState = initializeGameState();
    
    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(item => equipmentTypes.includes(item.type));
    
    const uniqueItems = [];
    const seen = new Set();
    items.forEach(item => {
        const key = `${item.type}-${item.quality}`;
        if (!seen.has(key)) {
            seen.add(key);
            uniqueItems.push(item);
        }
    });

    const item = uniqueItems[index];
    if (!item) {
        console.error('Không tìm thấy vật phẩm tại chỉ số:', index);
        alert('Lỗi: Không tìm thấy vật phẩm!');
        return;
    }

    const craftCost = calculateCraftCost(item.quality);
    console.log('Chi phí chế tạo:', JSON.stringify(craftCost, null, 2));
    console.log('Tài nguyên hiện tại:', {
        gold: gameState.gold,
        spiritStones: gameState.spiritStones,
        huyenThiet: gameState.materials['Huyền Thiết'] || 0
    });

    if (gameState.gold < craftCost.gold || 
        gameState.spiritStones < craftCost.spiritStones || 
        (gameState.materials['Huyền Thiết'] || 0) < craftCost.huyenThiet) {
        
        const missingResources = [];
        if (gameState.gold < craftCost.gold) {
            missingResources.push(`${craftCost.gold - gameState.gold} Kim tệ`);
        }
        if (gameState.spiritStones < craftCost.spiritStones) {
            missingResources.push(`${craftCost.spiritStones - gameState.spiritStones} Linh thạch`);
        }
        if ((gameState.materials['Huyền Thiết'] || 0) < craftCost.huyenThiet) {
            missingResources.push(`${craftCost.huyenThiet - (gameState.materials['Huyền Thiết'] || 0)} Huyền Thiết`);
        }
        
        console.warn('Không đủ tài nguyên để chế tạo. Thiếu:', missingResources.join(', '));
        alert('Không đủ tài nguyên để chế tạo!\nThiếu: ' + missingResources.join(', '));
        return;
    }

    gameState.gold -= craftCost.gold;
    gameState.spiritStones -= craftCost.spiritStones;
    if (!gameState.materials['Huyền Thiết']) gameState.materials['Huyền Thiết'] = 0;
    gameState.materials['Huyền Thiết'] -= craftCost.huyenThiet;

    const newItem = {
        type: item.type,
        name: item.name,
        image: item.image,
        tier: item.tier,
        quality: item.quality,
        locked: false,
        stats: getRandomStatForType(item.type, item.quality),
        enhanceLevel: 0
    };

    gameState.inventory.push(newItem);
    
    saveGameState();
    
    updateForgeDisplay();
    closeItemDetails();

    console.log('Chế tạo thành công, vật phẩm mới:', JSON.stringify(newItem, null, 2));
    console.log('Tài nguyên sau khi chế tạo:', {
        gold: gameState.gold,
        spiritStones: gameState.spiritStones,
        huyenThiet: gameState.materials['Huyền Thiết']
    });
    
    alert(`🎉 Chế tạo thành công!\n${item.name} (${item.quality}) đã được thêm vào Túi Đồ.`);
}

function closeItemDetails() {
    const modal = document.getElementById('item-details-modal');
    if (modal) modal.style.display = 'none';
    console.log('Đóng modal chi tiết vật phẩm');
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('Khởi tạo trang Lò Rèn...');
    
    setTimeout(() => {
        updateForgeDisplay();
    }, 100);
});

if (typeof window !== 'undefined') {
    window.updateForgeDisplay = updateForgeDisplay;
    window.craftItem = craftItem;
    window.showCraftItemDetails = showCraftItemDetails;
    window.closeItemDetails = closeItemDetails;
}