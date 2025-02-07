import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class CommentService {
  private baseUrl = 'http://127.0.0.1:8000/api/posts';

  constructor(private http: HttpClient) {}

  getComments(postId: number) {
    return this.http.get(`${this.baseUrl}/${postId}/comments`);
  }

  addComment(postId: number, comment: { content: string }) {
    return this.http.post(`${this.baseUrl}/${postId}/comments`, comment);
  }

  deleteComment(postId: number, commentId: number) {
    return this.http.delete(`${this.baseUrl}/${postId}/comments/${commentId}`);
  }
}
