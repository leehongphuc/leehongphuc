// main.js
import { Book } from "./book.js";
import { BookList } from "./bookList.js";
import { validateBook } from "./validation.js";

// Tạo danh sách sách (mặc định có 1 vài cuốn ban đầu)
const bookList = new BookList([
  new Book(
    1,
    "Hoàng Tử Bé",
    "Antoine de Saint-Exupéry",
    "Văn học",
    35000,
    "active",
    "2024-01-15"
  ),
  new Book(
    2,
    "Thám Tử Lừng Danh Conan - Tập 101",
    "Aoyama Gosho",
    "Thiếu nhi",
    23750,
    "active",
    "2024-01-10"
  ),
  new Book(
    3,
    "Toán Học 12 - Nâng Cao",
    "Bộ Giáo Dục",
    "Giáo khoa",
    40500,
    "active",
    "2024-01-05"
  ),
  new Book(
    4,
    "Nhà Giả Kim",
    "Paulo Coelho",
    "Văn học",
    55300,
    "active",
    "2024-01-01"
  ),
  new Book(
    5,
    "Dế Mèn Phiêu Lưu Ký",
    "Tô Hoài",
    "Thiếu nhi",
    28000,
    "inactive",
    "2023-12-28"
  ),
]);

// ================== Các hàm xử lý ==================

// Thông báo chức năng đang phát triển
function showDevelopmentAlert() {
  alert("Chức năng đang được phát triển!");
}

// ===== Modal =====
function openAddBookModal() {
  document.getElementById("addBookModal").classList.add("modal--open");
}
function closeAddBookModal() {
  document.getElementById("addBookModal").classList.remove("modal--open");
}

function openViewModal(bookId) {
  const modal = document.getElementById("viewBookModal");
  modal.classList.add("modal--open");

  const book = bookList.getBookById(bookId);
  if (book) {
    document.getElementById("viewBookContent").innerHTML = `
            <div class="book-details">
                <h3>${book.title}</h3>
                <p><strong>Tác giả:</strong> ${book.author}</p>
                <p><strong>Danh mục:</strong> ${book.category}</p>
                <p><strong>Giá:</strong> ${book.price.toLocaleString()} ₫</p>
                <p><strong>Trạng thái:</strong> ${
                  book.status === "active" ? "Đang bán" : "Ngừng bán"
                }</p>
                <p><strong>Ngày tạo:</strong> ${book.createdDate}</p>
            </div>
        `;
  }
}
function closeViewModal() {
  document.getElementById("viewBookModal").classList.remove("modal--open");
}

function openEditModal(bookId) {
  document.getElementById("editBookModal").classList.add("modal--open");
}
function closeEditModal() {
  document.getElementById("editBookModal").classList.remove("modal--open");
}

// Đóng modal khi click overlay
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("modal__overlay")) {
    e.target.closest(".modal").classList.remove("modal--open");
  }
});

// ===== Render bảng sách =====
function renderBooks() {
  const tbody = document.querySelector(".data-table tbody");
  tbody.innerHTML = ""; // clear cũ

  bookList.getBooks().forEach((book) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>
                <div class="book-info">
                    <div class="book-info__details">
                        <h4 class="book-info__title">${book.title}</h4>
                        <p class="book-info__author">Tác giả: ${book.author}</p>
                    </div>
                </div>
            </td>
            <td>${book.category}</td>
            <td><strong>${book.price.toLocaleString()} ₫</strong></td>
            <td>
                <span class="status-badge ${
                  book.status === "active"
                    ? "status-badge--active"
                    : "status-badge--inactive"
                }">
                    ${book.status === "active" ? "Đang bán" : "Ngừng bán"}
                </span>
            </td>
            <td>${book.createdDate}</td>
            <td>
                <button class="action-btn action-btn--view" title="Xem chi tiết" onclick="openViewModal(${
                  book.id
                })">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn action-btn--edit" title="Chỉnh sửa" onclick="openEditModal(${
                  book.id
                })">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn action-btn--delete" title="Xóa" onclick="deleteBook(${
                  book.id
                })">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    tbody.appendChild(row);
  });
}

// ===== Xóa sách =====
function deleteBook(bookId) {
  if (confirm("Bạn có chắc muốn xóa sách này?")) {
    bookList.removeBook(bookId);
    renderBooks();
  }
}
window.deleteBook = deleteBook;

// ===== Xử lý thêm sách mới =====
document.addEventListener("DOMContentLoaded", () => {
  renderBooks(); // render mặc định

  const addForm = document.querySelector("#addBookModal .book-form");
  addForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const newBook = new Book(
      Date.now(), // id tạm thời
      addForm.querySelector('input[placeholder="Nhập tên sách"]').value,
      addForm.querySelector('input[placeholder="Nhập tên tác giả"]').value,
      addForm.querySelectorAll("select.form-select")[0].value, // danh mục
      parseFloat(
        addForm.querySelector('input[placeholder="Nhập giá bán"]').value
      ),
      addForm.querySelectorAll("select.form-select")[1].value, // trạng thái
      addForm.querySelector('input[type="date"]').value
    );

    const errorMsg = validateBook(newBook);
    if (errorMsg) {
      alert(errorMsg);
      return;
    }

    bookList.addBook(newBook);
    renderBooks();
    alert("Thêm sách thành công!");
    closeAddBookModal();
    addForm.reset();
  });
});

// ================== Gắn vào window để HTML gọi được ==================
window.showDevelopmentAlert = showDevelopmentAlert;
window.openAddBookModal = openAddBookModal;
window.closeAddBookModal = closeAddBookModal;
window.openViewModal = openViewModal;
window.closeViewModal = closeViewModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
