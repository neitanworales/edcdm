import { HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Router } from "@angular/router";
import { Session } from "../models/Session";

@Injectable({ providedIn: 'root' })
export class Utils {

    constructor(
        private router: Router,
        //private sessionStorage: SessionStorageService
    ) { }

    public getHeaders(): HttpHeaders {
        //const user = JSON.parse(localStorage.getItem('currentUser')!);
        return new HttpHeaders({
            'Content-Type': 'application/json',
            //'Authorization': `Bearer ${user.token}`
        });
    }

    public getSessionFromStorage(): Session | undefined {
        if (localStorage.getItem('session') == null) {
            this.router.navigate(["/login"]);
            return undefined;
        } else {
            //let session: Session = this.sessionStorage.getSession()!;
            //return session;
            return new Session();
        }
    }
}