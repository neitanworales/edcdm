import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { environment } from "../../../environments/environment";
import { Utils } from "./Utils";
import { SessionResponse } from "../models/responses/SessionResponse";
import { AppUser } from "../models/AppUser";
import { DefaultResponse } from "../models/responses/DefaultResponse";
import { EmailExistResponse } from "../models/responses/EmailExistResponse";

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
        return this.http.post<SessionResponse>(environment.api + 'session', { "token": session?.token }, { headers: this.utils.getHeaders() });
    }

    public register(user: AppUser): Observable<DefaultResponse> {
        return this.http.post<DefaultResponse>(environment.api + 'users/register', user, { headers: this.utils.getHeaders() });
    }

    checkEmailExists(email: string): Observable<EmailExistResponse> {
        return this.http.post<EmailExistResponse>(environment.api + 'users/check-email', { email }, { headers: this.utils.getHeaders() });
    }
} 