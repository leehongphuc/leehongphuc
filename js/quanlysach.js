// Thông báo chức năng đang phát triển
function showDevelopmentAlert() {
    alert('Chức năng đang được phát triển!');
}

// Mở modal thêm sách
function openAddBookModal() {
    const modal = document.getElementById('addBookModal');
    modal.classList.add('modal--open');
}

// Đóng modal thêm sách
function closeAddBookModal() {
    const modal = document.getElementById('addBookModal');
    modal.classList.remove('modal--open');
}

// Mở modal xem chi tiết
function openViewModal(bookId) {
    const modal = document.getElementById('viewBookModal');
    modal.classList.add('modal--open');

    // Populate modal with book data (example)
    const bookData = {
        title: 'Hoàng Tử Bé',
        author: 'Antoine de Saint-Exupéry',
        category: 'Văn học',
        price: '35.000 ₫',
        stock: 50,
        status: 'Đang bán'
    };

    document.getElementById('viewBookContent').innerHTML = `
            <div class="book-details">
                <h3>${bookData.title}</h3>
                <p><strong>Tác giả:</strong> ${bookData.author}</p>
                <p><strong>Danh mục:</strong> ${bookData.category}</p>
                <p><strong>Giá:</strong> ${bookData.price}</p>
                <p><strong>Tồn kho:</strong> ${bookData.stock}</p>
                <p><strong>Trạng thái:</strong> ${bookData.status}</p>
            </div>
        `;
}

// Đóng modal xem chi tiết
function closeViewModal() {
    const modal = document.getElementById('viewBookModal');
    modal.classList.remove('modal--open');
}

// Mở modal sửa sách
function openEditModal(bookId) {
    const modal = document.getElementById('editBookModal');
    modal.classList.add('modal--open');
}

// Đóng modal sửa sách
function closeEditModal() {
    const modal = document.getElementById('editBookModal');
    modal.classList.remove('modal--open');
}

// Đóng modal khi click overlay
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal__overlay')) {
        const modal = e.target.closest('.modal');
        modal.classList.remove('modal--open');
    }
});