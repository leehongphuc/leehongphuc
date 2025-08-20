// book.js
export class Book {
  constructor(id, title, author, category, price, status, createdDate) {
    this.id = id;
    this.title = title;
    this.author = author;
    this.category = category;
    this.price = price;
    this.status = status; // "active" hoặc "inactive"
    this.createdDate = createdDate;
  }
}
