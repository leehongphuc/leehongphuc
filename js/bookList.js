// bookList.js
import { Book } from "./book.js";

export class BookList {
  constructor() {
    this.books = [];
  }

  addBook(book) {
    this.books.push(book);
  }

  removeBook(bookId) {
    this.books = this.books.filter((b) => b.id !== bookId);
  }

  updateBook(bookId, newBookData) {
    const index = this.books.findIndex((b) => b.id === bookId);
    if (index !== -1) {
      this.books[index] = { ...this.books[index], ...newBookData };
    }
  }

  getBookById(bookId) {
    return this.books.find((b) => b.id === bookId);
  }

  getBooks() {
    return this.books;
  }
}
