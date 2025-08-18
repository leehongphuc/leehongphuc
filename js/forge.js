console.log('forge.js được tải');

function ensureGameState() {
    if (typeof initializeGameState !== 'function') {
        console.error('initializeGameState chưa sẵn sàng');
        return null;
    }
    try {
        return initializeGameState();
    } catch (e) {
        console.error('Lỗi khởi tạo gameState:', e);
        return null;
    }
}

function calculateCraftCost(quality) {
    const baseCost = { gold: 50, spiritStones: 50, huyenThiet: 50 };
    const multipliers = {
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
    const m = multipliers[quality] || 1;
    return {
        gold: Math.floor(baseCost.gold * m),
        spiritStones: Math.floor(baseCost.spiritStones * m),
        huyenThiet: Math.floor(baseCost.huyenThiet * m)
    };
}

function updateForgeDisplay() {
    const gameState = ensureGameState();
    if (!gameState) return;
    if (!window.giftBoxItems) {
        console.error('giftBoxItems chưa sẵn sàng');
        return;
    }
    const grid = document.getElementById('equipment-grid');
    if (!grid) return;
    grid.innerHTML = '';

    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(i => equipmentTypes.includes(i.type));

    const seen = new Set();
    const unique = [];
    for (const it of items) {
        const key = `${it.type}-${it.quality}`;
        if (!seen.has(key)) { seen.add(key); unique.push(it); }
    }

    unique.forEach((item, idx) => {
        const card = document.createElement('div');
        const qClass = item.quality ? `quality-${item.quality.toLowerCase().replace(/\s+/g,'-')}` : '';
        card.className = `equipment-item ${qClass}`;
        card.innerHTML = `
            <div class="equipment-left">
                <div class="equipment-image">
                    <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" onerror="this.src='images/placeholder.png'">
                </div>
                <div class="equipment-info">
                    <div class="equipment-type" style="color: ${window.qualityColors ? (window.qualityColors[item.quality] || '#ffffff') : '#ffffff'}">${item.name}</div>
                </div>
            </div>
            <div class="equipment-right">
                <button class="craft-text-btn">Chế tạo</button>
            </div>
        `;
        card.style.cursor = 'pointer';
        card.onclick = () => showCraftItemDetails(idx);
        grid.appendChild(card);
    });
}

function showCraftItemDetails(index) {
    if (!window.giftBoxItems) return alert('Chưa có danh sách vật phẩm');
    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(i => equipmentTypes.includes(i.type));

    const seen = new Set();
    const unique = [];
    for (const it of items) {
        const key = `${it.type}-${it.quality}`;
        if (!seen.has(key)) { seen.add(key); unique.push(it); }
    }
    const item = unique[index];
    if (!item) return alert('Không tìm thấy vật phẩm');

    const gameState = ensureGameState();
    if (!gameState) return;

    const modal = document.getElementById('item-details-modal');
    const content = document.getElementById('item-details-content');
    if (!modal || !content) return;

    const fmt = (n, d=0) => Number(n).toFixed(d).replace('.', ',');
    const dash = ' – ';
    const ranges = window.qualityRanges ? window.qualityRanges[item.quality] : null;

    let statsHtml = '';
    if (ranges) {
        const hp = ranges.hp, pd = ranges.physicalDefense, md = ranges.magicDefense;
        const dmg = ranges.physicalDamage, crit = ranges.criticalChance, agi = ranges.agility;

        if (item.type === 'weapon') {
            statsHtml += `<div class="stat-line"><span class="stat-label">Sát thương vật lý</span><span class="stat-value">${fmt(dmg[0])} ~ ${fmt(dmg[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Sát thương phép thuật</span><span class="stat-value">${fmt(dmg[0])} ~ ${fmt(dmg[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Chí mạng</span><span class="stat-value">${fmt(crit[0])}% ~ ${fmt(crit[1])}%</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Tốc độ</span><span class="stat-value">${fmt(agi[0],1)} ~ ${fmt(agi[1],1)}</span></div>`;
        } else if (item.type === 'armor') {
            statsHtml += `<div class="stat-line"><span class="stat-label">Sinh lực</span><span class="stat-value">${fmt(hp[0])} ~ ${fmt(hp[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ vật lý</span><span class="stat-value">${fmt(pd[0])}% ~ ${fmt(pd[1])}%</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ phép thuật</span><span class="stat-value">${fmt(md[0])}% ~ ${fmt(md[1])}%</span></div>`;
        } else if (['ring','gloves','boots','necklace'].includes(item.type)) {
            statsHtml += `<div class="stat-desc">Ngẫu nhiên 1 dòng</div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Sinh lực</span><span class="stat-value">${fmt(hp[0])} ~ ${fmt(hp[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ vật lý</span><span class="stat-value">${fmt(pd[0])} ~ ${fmt(pd[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ phép thuật</span><span class="stat-value">${fmt(md[0])} ~ ${fmt(md[1])}</span></div>`;
            statsHtml += `<div class="stat-desc">Có tỷ lệ 5% ra thêm 1 dòng</div>`;
        } else if (item.type === 'artifact') {
            statsHtml += `<div class="stat-desc">Nhận ngẫu nhiên 1 dòng Vũ khí</div>`;
            statsHtml += `<div class="stat-desc">Nhận ngẫu nhiên 1 dòng Phòng Thủ</div>`;
            statsHtml += `<div class="stat-desc">Có tỷ lệ 5% ra thêm 1 dòng Vũ Khí hoặc Phòng Thủ</div>`;
        } else if (['helmet','belt','jade'].includes(item.type)) {
            statsHtml += `<div class="stat-desc">Ngẫu nhiên 1 dòng</div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Sinh lực</span><span class="stat-value">${fmt(hp[0])} ~ ${fmt(hp[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ vật lý</span><span class="stat-value">${fmt(pd[0])} ~ ${fmt(pd[1])}</span></div>`;
            statsHtml += `<div class="stat-line"><span class="stat-label">Phòng thủ phép thuật</span><span class="stat-value">${fmt(md[0])} ~ ${fmt(md[1])}</span></div>`;
            statsHtml += `<div class="stat-desc">Có tỷ lệ 5% ra thêm 1 dòng</div>`;
        }
    }

    const cost = calculateCraftCost(item.quality);
    const canGold = gameState.gold >= cost.gold;
    const canSS = gameState.spiritStones >= cost.spiritStones;
    const haveHT = (gameState.materials['Huyền Thiết'] || 0);
    const canHT = haveHT >= cost.huyenThiet;

    content.innerHTML = `
        <span class="close-modal" onclick="closeItemDetails()">&times;</span>
        <div class="item-detail">
            <div class="item-image-section">
                <img src="${item.image || 'images/placeholder.png'}" alt="${item.name}" class="item-detail-image" onerror="this.src='images/placeholder.png'">
            </div>
            <div class="item-header">
                <div class="item-basic-info">
                    <div class="item-name" style="color:${window.qualityColors ? (window.qualityColors[item.quality] || '#fff') : '#fff'}">${item.name}</div>
                    <div class="info-line"><span class="info-label">Cấp bậc:</span><span class="info-value">${item.tier}</span></div>
                    <div class="info-line"><span class="info-label">Phẩm chất:</span><span class="info-value" style="color:${window.qualityColors ? (window.qualityColors[item.quality] || '#fff') : '#fff'}">${item.quality}</span></div>
                    <div class="item-stats">${statsHtml}</div>
                </div>
            </div>
        </div>
        <div class="craft-materials-section">
            <h4>Vật phẩm cần chế tạo:</h4>
            <div class="craft-materials">
                <div class="material-item ${canGold ? '' : 'insufficient'}">
                    <img src="images/vang.png" alt="Kim tệ" class="material-image" onerror="this.src='images/placeholder.png'">
                    <div class="material-amount ${canGold ? '' : 'insufficient'}">${gameState.gold}/${cost.gold}</div>
                </div>
                <div class="material-item ${canSS ? '' : 'insufficient'}">
                    <img src="images/linh_thach.png" alt="Linh thạch" class="material-image" onerror="this.src='images/placeholder.png'">
                    <div class="material-amount ${canSS ? '' : 'insufficient'}">${gameState.spiritStones}/${cost.spiritStones}</div>
                </div>
                <div class="material-item ${canHT ? '' : 'insufficient'}">
                    <img src="images/huyen_thiet.png" alt="Huyền Thiết" class="material-image" onerror="this.src='images/placeholder.png'">
                    <div class="material-amount ${canHT ? '' : 'insufficient'}">${haveHT}/${cost.huyenThiet}</div>
                </div>
            </div>
            <div class="craft-button-section">
                <button class="craft-btn ${canGold && canSS && canHT ? '' : 'disabled'}" 
                        onclick="craftItem('${item.type}', '${item.quality}', ${index})" 
                        ${canGold && canSS && canHT ? '' : 'disabled'}>
                    ${canGold && canSS && canHT ? 'Chế tạo' : 'Không đủ nguyên liệu'}
                </button>
            </div>
        </div>
    `;
    modal.style.display = 'block';
}

function craftItem(itemType, itemQuality, itemIndex) {
    const gameState = ensureGameState();
    if (!gameState) return alert('Lỗi trạng thái game');

    if (!window.giftBoxItems) return alert('Chưa có danh sách vật phẩm');
    
    const equipmentTypes = ['weapon', 'necklace', 'ring', 'gloves', 'boots', 'armor', 'helmet', 'belt', 'jade', 'artifact'];
    const items = window.giftBoxItems.filter(i => equipmentTypes.includes(i.type));
    
    const seen = new Set();
    const unique = [];
    for (const it of items) {
        const key = `${it.type}-${it.quality}`;
        if (!seen.has(key)) { seen.add(key); unique.push(it); }
    }
    
    const item = unique[itemIndex];
    if (!item) return alert('Không tìm thấy vật phẩm');

    const cost = calculateCraftCost(itemQuality);
    
    // Kiểm tra nguyên liệu
    const canGold = gameState.gold >= cost.gold;
    const canSS = gameState.spiritStones >= cost.spiritStones;
    const haveHT = (gameState.materials['Huyền Thiết'] || 0);
    const canHT = haveHT >= cost.huyenThiet;

    if (!canGold || !canSS || !canHT) {
        return alert('Không đủ nguyên liệu để chế tạo!');
    }

    // Trừ nguyên liệu
    gameState.gold -= cost.gold;
    gameState.spiritStones -= cost.spiritStones;
    gameState.materials['Huyền Thiết'] -= cost.huyenThiet;

    // Tạo vật phẩm mới với stats ngẫu nhiên
    const newItem = createRandomItem(item.type, item.quality);
    
    // Thêm vào inventory
    if (!gameState.inventory) gameState.inventory = [];
    gameState.inventory.push(newItem);

    // Lưu game state
    if (typeof localStorage !== 'undefined') {
        localStorage.setItem('gameState', JSON.stringify(gameState));
    }

    alert(`Chế tạo thành công ${newItem.name}!`);
    closeItemDetails();
    
    // Cập nhật hiển thị nếu có
    if (typeof window.updateCharacterDisplay === 'function') {
        window.updateCharacterDisplay();
    }
}

function createRandomItem(type, quality) {
    if (!window.giftBoxItems) return null;
    
    // Tìm template item
    const template = window.giftBoxItems.find(item => item.type === type && item.quality === quality);
    if (!template) return null;

    // Tạo stats ngẫu nhiên dựa trên quality
    const ranges = window.qualityRanges ? window.qualityRanges[quality] : null;
    if (!ranges) return { ...template, stats: {} };

    let stats = {};
    
    // Tạo stats dựa trên loại trang bị
    if (type === 'weapon') {
        // Vũ khí: damage, crit, agility
        const physicalDamage = Math.floor(Math.random() * (ranges.physicalDamage[1] - ranges.physicalDamage[0] + 1)) + ranges.physicalDamage[0];
        const criticalChance = (Math.random() * (ranges.criticalChance[1] - ranges.criticalChance[0]) + ranges.criticalChance[0]).toFixed(1);
        const agility = (Math.random() * (ranges.agility[1] - ranges.agility[0]) + ranges.agility[0]).toFixed(2);
        
        stats = {
            physicalDamage: physicalDamage,
            criticalChance: parseFloat(criticalChance),
            agility: parseFloat(agility)
        };
    } else if (type === 'armor') {
        // Giáp: hp, physical defense, magic defense
        const hp = Math.floor(Math.random() * (ranges.hp[1] - ranges.hp[0] + 1)) + ranges.hp[0];
        const physicalDefense = (Math.random() * (ranges.physicalDefense[1] - ranges.physicalDefense[0]) + ranges.physicalDefense[0]).toFixed(1);
        const magicDefense = (Math.random() * (ranges.magicDefense[1] - ranges.magicDefense[0]) + ranges.magicDefense[0]).toFixed(1);
        
        stats = {
            hp: hp,
            physicalDefense: parseFloat(physicalDefense),
            magicDefense: parseFloat(magicDefense)
        };
    } else {
        // Các loại khác: ngẫu nhiên 1 stat chính
        const statTypes = ['hp', 'physicalDefense', 'magicDefense'];
        const randomStat = statTypes[Math.floor(Math.random() * statTypes.length)];
        
        if (randomStat === 'hp') {
            stats[randomStat] = Math.floor(Math.random() * (ranges.hp[1] - ranges.hp[0] + 1)) + ranges.hp[0];
        } else {
            stats[randomStat] = parseFloat((Math.random() * (ranges[randomStat][1] - ranges[randomStat][0]) + ranges[randomStat][0]).toFixed(1));
        }
    }

    return {
        ...template,
        stats: stats,
        enhanceLevel: 0,
        equipped: false,
        locked: false
    };
}

function closeItemDetails() {
    const modal = document.getElementById('item-details-modal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    // Nếu có lưới lò rèn thì khởi tạo hiển thị lò rèn
    if (document.querySelector('.forge-section')) {
        updateForgeDisplay();
    }
});

if (typeof window !== 'undefined') {
    window.updateForgeDisplay = updateForgeDisplay;
    window.showCraftItemDetails = showCraftItemDetails;
    window.closeItemDetails = closeItemDetails;
    window.craftItem = craftItem;
    window.createRandomItem = createRandomItem;
}

