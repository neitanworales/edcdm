import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { environment } from "../../../environments/environment";
import { AppUser } from "../models/AppUser";
import { Utils } from "./Utils";
import { UserResponse } from "../models/UserResponse";

@Injectable({ providedIn: 'root' })
export class UserDao {
  constructor(
    private http: HttpClient, 
    private utils: Utils) 
    {}

  list(): Observable<UserResponse> {
    console.log('Fetching user list from API');
    return this.http.get<UserResponse>(environment.api + 'users', { headers: this.utils.getHeaders() });
  }

  create(user: { username: string; password: string; full_name?: string; role: AppUser['role'] }): Observable<{ id: number; message: string }> {
    return this.http.post<{ id: number; message: string }>(environment.api + 'users/register', user, { headers: this.utils.getHeaders() });
  }

  update(id: number, user: Partial<{ username: string; password: string; full_name: string; role: AppUser['role'] }>): Observable<{ message: string }> {
    return this.http.put<{ message: string }>(environment.api + 'users/' + id, user, { headers: this.utils.getHeaders() });
  }

  delete(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(environment.api + 'users/' + id, { headers: this.utils.getHeaders() });
  }
}