import { Injectable } from "@angular/core";
import { Utils } from "./Utils";
import { HttpClient } from "@angular/common/http";
import { ModuleResponse } from "../models/responses/ModuleResponse";
import { environment } from "../../../environments/environment";
import { Observable } from "rxjs";
import { Module } from "../models/Module";
import { Lesson } from "../models/Lesson";

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

    get(id: number): Observable<ModuleResponse> {
        return this.http.get<ModuleResponse>(environment.api + 'modules/' + id, { headers: this.utils.getHeaders() });
    }

    create(module: Partial<Module>): Observable<{ id: number }> {
        return this.http.post<{ id: number }>(environment.api + 'modules', module, { headers: this.utils.getHeaders() });
    }

    update(id: number, module: Partial<Module>): Observable<{ updated: boolean }> {
        return this.http.put<{ updated: boolean }>(environment.api + 'modules/' + id, module, { headers: this.utils.getHeaders() });
    }

    lessons(moduleId: number): Observable<Lesson[]> {
        return this.http.get<Lesson[]>(environment.api + 'modules/' + moduleId + '/lessons', { headers: this.utils.getHeaders() });
    }
}