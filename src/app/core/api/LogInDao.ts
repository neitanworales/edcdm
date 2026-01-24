import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { SessionResponse } from "../models/SessionResponse";
import { environment } from "../../../environments/environment";
import { Utils } from "./Utils";

@Injectable({ providedIn: 'root' })
export class LogInDao {
    constructor(
        private http: HttpClient,
        private utils: Utils
    ) { }

    public login(email: string, password: string): Observable<SessionResponse> {
        return this.http.post<SessionResponse>(environment.api + 'users/login', { username: email, password }, { headers: this.utils.getHeaders() });
    }

    public getSession(): Observable<SessionResponse> {
        let session = this.utils.getSessionFromStorage();
        return this.http.post<SessionResponse>(environment.api + '/session', { "token": session?.token }, { headers: this.utils.getHeaders() });
    }
} 