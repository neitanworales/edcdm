import { Injectable } from "@angular/core";
import { Utils } from "./Utils";
import { HttpClient } from "@angular/common/http";
import { ModuleResponse } from "../models/responses/ModuleResponse";
import { environment } from "../../../environments/environment.development";
import { Observable } from "rxjs";

@Injectable({ providedIn: 'root' })
export class ModuleDao {
    constructor(
        private http: HttpClient,
        private utils: Utils
    ) {}

    list(): Observable<ModuleResponse> {
        console.log('Fetching module list from API');
        return this.http.get<ModuleResponse>(environment.api + 'modules', { headers: this.utils.getHeaders() });
    }
}