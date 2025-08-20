// validation.js
export function validateBook(book) {
  if (!book.id || book.id.toString().trim() === "") {
    return "Mã sách không được để trống!";
  }
  if (!book.title || book.title.trim() === "") {
    return "Tên sách không được để trống!";
  }
  if (!book.author || book.author.trim() === "") {
    return "Tên tác giả không được để trống!";
  }
  if (!book.category || book.category.trim() === "") {
    return "Danh mục không được để trống!";
  }
  if (!book.price || isNaN(book.price) || book.price <= 0) {
    return "Giá sách phải lớn hơn 0!";
  }
  return null; // hợp lệ
}
