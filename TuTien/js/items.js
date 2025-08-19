console.log('items.js loaded, defining giftBoxItems...');

const giftBoxItems = [
    // Vũ khí (Weapon)
    { type: 'weapon', name: 'Dạ Phiến', image: 'images/quat_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'weapon', name: 'Bạch Phiến', image: 'images/quat_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'weapon', name: 'Thiên Ma Ô', image: 'images/o_2.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },

   
    // Ngoa (Boots)
    { type: 'boots', name: 'Ngoa', image: 'images/giay_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'boots', name: 'Thanh Ngọa', image: 'images/giay_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'boots', name: 'Tử Ngọa', image: 'images/giay_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'boots', name: 'Lục Ngọa', image: 'images/giay_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'boots', name: 'Bạch Vân Ngọa', image: 'images/giay_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'boots', name: 'Kim Long Ngọa', image: 'images/giay_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'boots', name: 'Ngọa Long Ngọa', image: 'images/giay_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'boots', name: 'Tử Lôi Ngọa', image: 'images/giay_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'boots', name: 'Tử Lôi Ngọa', image: 'images/giay_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'boots', name: 'Tử Lôi Ngọa', image: 'images/giay_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Giáp (Armor)
    { type: 'armor', name: 'Giáp', image: 'images/giap_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'armor', name: 'Thanh Bào', image: 'images/giap_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'armor', name: 'Tử Bào', image: 'images/giap_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'armor', name: 'Lục Bào', image: 'images/giap_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'armor', name: 'Lục Vân Bào', image: 'images/giap_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'armor', name: 'Kim Long Bào', image: 'images/giap_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'armor', name: 'Ngọa Long Bào', image: 'images/giap_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'armor', name: 'Tử Lôi Bào', image: 'images/giap_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'armor', name: 'Tử Lôi Bào', image: 'images/giap_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'armor', name: 'Tử Lôi Bào', image: 'images/giap_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Hào (Helmet)
    { type: 'helmet', name: 'Thanh Hào', image: 'images/mu_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'helmet', name: 'Thanh Khôi', image: 'images/mu_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'helmet', name: 'Tử Khôi', image: 'images/mu_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'helmet', name: 'Lục Khôi', image: 'images/mu_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'helmet', name: 'Bạch Vân Khôi', image: 'images/mu_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'helmet', name: 'Kim Long Khôi', image: 'images/mu_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'helmet', name: 'Ngọa Long Khôi', image: 'images/mu_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'helmet', name: 'Tử Lôi Khôi', image: 'images/mu_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'helmet', name: 'Tử Lôi Khôi', image: 'images/mu_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'helmet', name: 'Tử Lôi Khôi', image: 'images/mu_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Đai (Belt)
    { type: 'belt', name: 'Đai', image: 'images/that_lung_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'belt', name: 'Thanh Đai', image: 'images/that_lung_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'belt', name: 'Tử Đai', image: 'images/that_lung_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'belt', name: 'Lục Đai', image: 'images/that_lung_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'belt', name: 'Bạch Vân Đai', image: 'images/that_lung_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'belt', name: 'Kim Long Đai', image: 'images/that_lung_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'belt', name: 'Ngọa Long Đai', image: 'images/that_lung_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'belt', name: 'Tử Lôi Đai', image: 'images/that_lung_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'belt', name: 'Tử Lôi Đai', image: 'images/that_lung_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'belt', name: 'Tử Lôi Đai', image: 'images/that_lung_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Bội (Jade)
    { type: 'jade', name: 'Bội', image: 'images/ngoc_boi_1.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' },
    { type: 'jade', name: 'Thanh Bội', image: 'images/ngoc_boi_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'jade', name: 'Tử Bội', image: 'images/ngoc_boi_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'jade', name: 'Lục Bội', image: 'images/ngoc_boi_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'jade', name: 'Bạch Vân Bội', image: 'images/ngoc_boi_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'jade', name: 'Kim Long Bội', image: 'images/ngoc_boi_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'jade', name: 'Ngọa Long Bội', image: 'images/ngoc_boi_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'jade', name: 'Tử Lôi Bội', image: 'images/ngoc_boi_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'jade', name: 'Tử Lôi Bội', image: 'images/ngoc_boi_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'jade', name: 'Tử Lôi Bội', image: 'images/ngoc_boi_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Phù (Necklace)
    { type: 'necklace', name: 'Thanh Phù', image: 'images/day_chuyen_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'necklace', name: 'Tử Phù', image: 'images/day_chuyen_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'necklace', name: 'Lục Phù', image: 'images/day_chuyen_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'necklace', name: 'Bạch Vân Phù', image: 'images/day_chuyen_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'necklace', name: 'Kim Long Phù', image: 'images/day_chuyen_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'necklace', name: 'Ngọa Long Phù', image: 'images/day_chuyen_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'necklace', name: 'Tử Lôi Phù', image: 'images/day_chuyen_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'necklace', name: 'Tử Lôi Phù', image: 'images/day_chuyen_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'necklace', name: 'Tử Lôi Phù', image: 'images/day_chuyen_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Giới Chỉ (Ring)
    { type: 'ring', name: 'Thanh Giới Chỉ', image: 'images/nhan_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'ring', name: 'Tử Giới Chỉ', image: 'images/nhan_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'ring', name: 'Lục Giới Chỉ', image: 'images/nhan_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'ring', name: 'Bạch Vân Giới Chỉ', image: 'images/nhan_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'ring', name: 'Kim Long Giới Chỉ', image: 'images/nhan_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'ring', name: 'Ngọa Long Giới Chỉ', image: 'images/nhan_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'ring', name: 'Tử Lôi Giới Chỉ', image: 'images/nhan_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'ring', name: 'Tử Lôi Giới Chỉ', image: 'images/nhan_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'ring', name: 'Tử Lôi Giới Chỉ', image: 'images/nhan_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Găng tay (Gloves)
    { type: 'gloves', name: 'Thanh Thủ', image: 'images/gang_tay_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'gloves', name: 'Tử Thủ', image: 'images/gang_tay_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'gloves', name: 'Lục Thủ', image: 'images/gang_tay_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'gloves', name: 'Bạch Vân Thủ', image: 'images/gang_tay_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'gloves', name: 'Kim Long Thủ', image: 'images/gang_tay_6.png', tier: 'Cực Phẩm', quality: 'Lục Phẩm' },
    { type: 'gloves', name: 'Ngọa Long Thủ', image: 'images/gang_tay_7.png', tier: 'Cực Phẩm', quality: 'Thất Phẩm' },
    { type: 'gloves', name: 'Tử Lôi Thủ', image: 'images/gang_tay_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'gloves', name: 'Tử Lôi Thủ', image: 'images/gang_tay_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'gloves', name: 'Tử Lôi Thủ', image: 'images/gang_tay_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Pháp bảo (Artifact)
    { type: 'artifact', name: 'Bố Trận Kỳ', image: 'images/phap_bao_2.png', tier: 'Trung Phẩm', quality: 'Nhị Phẩm' },
    { type: 'artifact', name: 'Âm Dương Hoàn', image: 'images/phap_bao_3.png', tier: 'Trung Phẩm', quality: 'Tam Phẩm' },
    { type: 'artifact', name: 'Phật Ma Trụy', image: 'images/phap_bao_4.png', tier: 'Thượng Phẩm', quality: 'Tứ Phẩm' },
    { type: 'artifact', name: 'Huyết Lôi Lô', image: 'images/phap_bao_5.png', tier: 'Thượng Phẩm', quality: 'Ngũ Phẩm' },
    { type: 'artifact', name: 'Phong Ma Pháp', image: 'images/phap_bao_8.png', tier: 'Cực Phẩm', quality: 'Bát Phẩm' },
    { type: 'artifact', name: 'Phong Ma Pháp', image: 'images/phap_bao_8.png', tier: 'Cực Phẩm', quality: 'Cửu Phẩm' },
    { type: 'artifact', name: 'Phong Ma Pháp', image: 'images/phap_bao_8.png', tier: 'Cực Phẩm', quality: 'Vương Cấp' },

    // Nguyên liệu (Material)
    { type: 'material', name: 'Huyền Thiết', image: 'images/huyen_thiet.png', tier: 'Hạ Phẩm', quality: 'Nhất Phẩm' }
];

// Export giftBoxItems để các file khác sử dụng
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { giftBoxItems };
} else {
    window.giftBoxItems = giftBoxItems;
}
console.log('giftBoxItems defined:', window.giftBoxItems);